<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Auth;
use App\Models\GeneralConf;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class RequestFileController extends Controller
{
    public function store(Request $request)
    {
        try {
            $userID = Auth::id();
            
            // Fetch the maximum allowed requests directly
            $maxRequests = GeneralConf::value('max_requests');
            
            if ($maxRequests === null) {
                return redirect()->back()->with('error', 'Max requests configuration not found.');
            }   
            
            $maxRequests = (int) $maxRequests;
            
            if ($maxRequests <= 0) {
                return redirect()->back()->with('error', 'Max requests configuration is invalid.');
            }   
            
            $userRequestCount = Service::where('user_id', $userID)->count();
            
            if ($userRequestCount >= $maxRequests) {
                return redirect()->back()->with('error', 'You have reached the maximum number of allowed requests.');
            }
    
            // Debugging: log the request type and data
            Log::info('Request type: ' . $request->input('request_type'));
            Log::info('Request data: ', $request->all());
    
            // Prepare data for the Service model
            $serviceData = [];
            $fields = [
                'residency_fullname', 'residency_houseAddress', 'residency_date', 'residency_purpose',
                'barangay_fullname', 'barangay_dob', 'barangay_age', 'barangay_pob', 'barangay_houseAddress', 'barangay_purpose',
                'indigency_fullname', 'indigency_houseAddress', 'indigency_purpose',
                'job_seeker_fullname', 'job_seeker_houseAddress', 'job_seeker_purpose',
                'business_name', 'business_address', 'owner_name',
                'barangay_id_surname', 'barangay_id_firstName', 'barangay_id_middleName', 'barangay_id_address', 'barangay_id_purpose',
            ];
    
            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $serviceData[$field] = $request->input($field, ''); // Default to empty string if not set
                }
            }
    
            // Create a new Service instance
            $service = new Service();
            $service->user_id = $userID;
            $service->request_type = $request->input('request_type'); 
            $service->tracking_code = $request->input('tracking_code'); 
            $service->status = 'pending';
            $service->comment = $request->input('comment', ''); 
            $service->data = json_encode($serviceData);
            $service->save();
    
            // Log the activity
            ActivityLog::create([
                'user_id' => $userID,
                'activity' => 'Sent a request for ' . $request->input('request_type', 'Unknown'),
            ]);
    
            return redirect()->back()->with('success', 'Request submitted successfully!');
        
        } catch (\Exception $e) {
            Log::error('RequestFileController store method error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while processing your request.');
        }
    }
    
    public function index()
    {
        $user = auth()->user();
        if ($user->roles !== 'resident') {
            abort(403, 'Unauthorized');
        }
        $userID = auth()->user()->id; 
        $requests = Service::where('user_id', $userID)
            ->latest() 
            ->paginate(10); 
        // Return view with the 'requests' variable
        return view('resident.request-file', compact('requests'));
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->roles !== 'resident') {
            abort(403, 'Unauthorized');
        }
        return view('resident.make-request');
    }
}
