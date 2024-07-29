<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\ReportRequest;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    //
    public function viewReportMsg($id) {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $report = ReportRequest::findorFail($id);
        $fullname = base64_decode($report->fullname);
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'View Report filed by ' .$fullname,
        ]);

        return view('admin.view-report', compact('report'));
        
    }
    public function sendReport(Request $request)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        Log::info('Incoming Report Request Data:', $request->all());

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_num' => 'required|string|max:11',
            'report_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'issue' => 'required|string',
        ]);

        $fullname = base64_encode($request->input('fullname'));
        $email = base64_encode($request->input('email'));
        $contact_num = base64_encode($request->input('contact_num'));
        $issue = base64_encode($request->input('issue'));

        $photoPath = null;

        if ($request->hasFile('report_photo')) {
            $photo = $request->file('report_photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('report_photo'), $photoName);
            $photoPath = 'report_photo/' . $photoName;
        }

        try {
            ReportRequest::create([
                'fullname' => $fullname,
                'email' => $email,
                'contact_num' => $contact_num,
                'report_photo' => $photoPath,
                'issue' => $issue,
                'status' => 'pending',
            ]);

            return redirect()->route('file-report.index')
                ->with('alert', ['type' => 'success', 'message' => 'Report submitted successfully! Please wait the  reply on your email']);
        } catch (\Exception $e) {
            Log::error('Failed to submit report: ' . $e->getMessage());

            return redirect()->route('file-report.index')
                ->with('alert', ['type' => 'error', 'message' => 'Failed to submit report. Please try again.']);
        }
    }
    public function sendReportReply(Request $request)
{
    $authUser = auth()->user();
    if (!in_array( $authUser->roles, ['admin', 'staff'])) {
        abort(403, 'Unauthorized');
    }
    $request->validate([
        'content' => 'required|string',
        'report_id' => 'required|integer|exists:report_requests,id',
    ]);

    try {
        $report = ReportRequest::findOrFail($request->input('report_id'));
        
        // Pass the full name to the mail class
        \Mail::to(base64_decode($report->email))->send(new \App\Mail\ReportReplyMail(
            $request->input('content'),
            base64_decode($report->fullname) 
        ));

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Sent reply to the email of ' . base64_decode($report->fullname),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Reply sent successfully!']);
    } catch (\Exception $e) {
        \Log::error('Failed to send reply: ' . $e->getMessage());

        return response()->json(['status' => 'error', 'message' => 'Failed to send reply. Please try again.']);
    }
}

public function markAsRead($id)
{
    $authUser = auth()->user();
    if (!in_array( $authUser->roles, ['admin', 'staff'])) {
        abort(403, 'Unauthorized');
    }
    $report = ReportRequest::findOrFail($id);

    if ($report->status === 'pending') {
        $report->status = 'resolved';
        $report->save();

        $fullname = base64_decode($report->fullname);
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Marked as Resolved the report filed by ' . $fullname,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Report marked as resolved.'
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Report is already resolved or has an invalid status.'
        ]);
    }
}
    public function deleteReport($id) {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $report = ReportRequest::findOrFail($id); 
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Deleted a report',
        ]);
        $report->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Report deleted successfully.'
        ]);
    }

}
