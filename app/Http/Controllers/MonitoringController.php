<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Service;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestModified;

class MonitoringController extends Controller
{
    public function pending()
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $pending = 'pending';
        $services = Service::where('status', $pending)->latest()->get();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited Pending Monitoring page.',
        ]);

        // Transform the services data
        $services = $services->map(function($service) {
            $user = User::find($service->user_id);
            if ($user) {
                $service->full_name = $user->fname . ' ' . $user->middlename . ' ' . $user->lname;
            } else {
                $service->full_name = 'Unknown User';
            }
            $service->status = strtoupper($service->status);
            return $service;
        });

        // Prepare the JSON data
        $servicesJson = $services->toJson();

        return view('mon.pending', compact('servicesJson'));
    }

    public function approved()
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $approved = 'approved';
        $services = Service::where('status', $approved)->latest()->get();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited Approved Monitoring page.',
        ]);

        // Transform the services data
        $services = $services->map(function($service) {
            $user = User::find($service->user_id);
            if ($user) {
                $service->full_name = $user->fname . ' ' . $user->middlename . ' ' . $user->lname;
            } else {
                $service->full_name = 'Unknown User';
            }
            $service->status = strtoupper($service->status);
            return $service;
        });

        // Prepare the JSON data
        $servicesJson = $services->toJson();

        return view('mon.approved', compact('servicesJson'));
    }

    public function declined()
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $declined = 'declined';
        $services = Service::where('status', $declined)->latest()->get();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited Declined Monitoring page.',
        ]);

        // Transform the services data
        $services = $services->map(function($service) {
            $user = User::find($service->user_id);
            if ($user) {
                $service->full_name = $user->fname . ' ' . $user->middlename . ' ' . $user->lname;
            } else {
                $service->full_name = 'Unknown User';
            }
            $service->status = strtoupper($service->status);
            return $service;
        });

        // Prepare the JSON data
        $servicesJson = $services->toJson();

        return view('mon.declined', compact('servicesJson'));
    }

    public function modifyRequest(Request $request)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $service = Service::find($request->s_id);

        if (!$service) {
            return response()->json(['error' => 'Request not found.'], 404);
        }

        $requestName = $service->request_type;
        switch ($request->mtype) {
            case 2: // Delete request
                $service->forceDelete();

                // Create notification
                Notification::create([
                    'sender_id' => auth()->user()->id,
                    'receiver_id' => $service->user_id,
                    'message' => 'Your request "' . $requestName . '" has been deleted.'
                ]);

                // Send email notification
                Mail::to($service->users->email)->send(new RequestModified('deleted', $requestName));

                return response()->json(['success' => 'Request deleted successfully.']);
            case 3: // Move to pending
                $service->status = 'pending';
                $service->modified_by =  $authUser->id;
                $service->save();

                // Create notification
                Notification::create([
                    'sender_id' => auth()->user()->id,
                    'receiver_id' => $service->user_id,
                    'message' => 'Moved your request "' . $requestName . '" to Pending.'
                ]);

                // Send email notification
                Mail::to($service->users->email)->send(new RequestModified('pending', $requestName));

                return response()->json(['success' => 'Request moved to pending.']);
            case 4: // Move to approved
                $service->status = 'approved';
                $service->modified_by =  $authUser->id;
                $service->save();

                // Create notification
                Notification::create([
                    'sender_id' => auth()->user()->id,
                    'receiver_id' => $service->user_id,
                    'message' => 'Your request "' . $requestName . '" has been approved.'
                ]);

                // Send email notification
                Mail::to($service->users->email)->send(new RequestModified('approved', $requestName));

                return response()->json(['success' => 'Request approved.']);
            default:
                return response()->json(['error' => 'Invalid action.'], 400);
        }
    }

    public function modifyPendingRequest(Request $request)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'mtype' => 'required|integer',
            's_id' => 'required|integer',
            'reason' => 'nullable|string'
        ]);

        $service = Service::find($validated['s_id']);

        if (!$service) {
            return response()->json(['error' => 'Service not found.'], 404);
        }

        switch ($validated['mtype']) {
            case 2: // Decline
                $service->status = 'Declined';
                $service->modified_by =  $authUser->id;
                $service->comment = $validated['reason'] ?? null;
                $service->save();

                // Create notification
                Notification::create([
                    'sender_id' => auth()->user()->id,
                    'receiver_id' => $service->user_id,
                    'message' => 'Your request "' . $service->request_type . '" has been declined.'
                ]);

                // Send email notification
                Mail::to($service->users->email)->send(new RequestModified('declined', $service->request_type));

                break;

            case 3: // Approve
                $service->status = 'Approved';
                $service->modified_by =  $authUser->id;
                $service->save();

                // Create notification
                Notification::create([
                    'sender_id' => auth()->user()->id,
                    'receiver_id' => $service->user_id,
                    'message' => 'Your request "' . $service->request_type . '" has been approved.'
                ]);

                // Send email notification
                Mail::to($service->users->email)->send(new RequestModified('approved', $service->request_type));

                break;

            default:
                return response()->json(['error' => 'Invalid modification type.'], 400);
        }

        return response()->json(['success' => 'Request updated successfully.']);
    }

    public function viewRequest($id)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $service = Service::find($id);

        if (!$service) {
            return redirect()->back()->withErrors(['error' => 'Request not found.']);
        }

        return view('mon.view', compact('service'));
    }

    public function deleteRequest(Request $request)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $service = Service::find($request->id);
        if ($service) {
            $service->delete();

            // Create notification
            Notification::create([
                'sender_id' => auth()->user()->id,
                'receiver_id' => $service->user_id,
                'message' => 'Your request "' . $service->request_type . '" has been deleted.'
            ]);

            // Send email notification
            Mail::to($service->users->email)->send(new RequestModified('deleted', $service->request_type));

            return response()->json(['success' => 'Request deleted successfully.']);
        } else {
            return response()->json(['error' => 'Request not found.'], 404);
        }
    }

    public function modifyApprovedRequest(Request $request)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $service = Service::find($request->s_id);

        if (!$service) {
            return response()->json(['error' => 'Request not found.'], 404);
        }

        switch ($request->mtype) {
            case 2: // Decline request
                $service->status = 'declined';
                $service->modified_by =  $authUser->id;
                 // Save the comment if provided
                if ($request->has('comment')) {
                    $service->comment = $request->input('comment');
                }
                $service->save();

                // Create notification
                Notification::create([
                    'sender_id' => auth()->user()->id,
                    'receiver_id' => $service->user_id,
                    'message' => 'Your request "' . $service->request_type . '" has been declined.'
                ]);

                // Send email notification
                Mail::to($service->users->email)->send(new RequestModified('declined', $service->request_type));

                return response()->json(['success' => 'Request declined successfully.']);
            case 3: // Move to pending
                $service->status = 'pending';
                $service->modified_by =  $authUser->id;
                $service->save();

                // Create notification
                Notification::create([
                    'sender_id' => auth()->user()->id,
                    'receiver_id' => $service->user_id,
                    'message' => 'Your request "' . $service->request_type . '" has been moved to pending.'
                ]);

                // Send email notification
                Mail::to($service->users->email)->send(new RequestModified('pending', $service->request_type));

                return response()->json(['success' => 'Request moved to pending.']);
            default:
                return response()->json(['error' => 'Invalid action.'], 400);
        }
    }

    public function declineRequest(Request $request)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $service = Service::find($request->input('s_id'));

        if ($service) {
            $service->status = 'declined';
            $service->comment = $request->input('reason');
            $service->modified_by =  $authUser->id;
            $service->save();

            // Create notification
            Notification::create([
                'sender_id' => auth()->user()->id,
                'receiver_id' => $service->user_id,
                'message' => 'Your request "' . $service->request_type . '" has been declined with reason: ' . $request->input('reason'),
            ]);

            // Send email notification
            Mail::to($service->users->email)->send(new RequestModified('declined', $service->request_type, $request->input('reason')));

            return response()->json(['success' => 'Request declined successfully.']);
        } else {
            return response()->json(['error' => 'Request not found.'], 404);
        }
    }
}
