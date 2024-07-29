<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Service;
use App\Models\ActivityLog;

class RequestController extends Controller
{
    public function show($id)
    {
        $request = Service::findOrFail($id);
        $requestData = json_decode($request->data, true); 
        $request->data = $requestData;  
        $request->formatted_created_at = $request->created_at->format('F j, Y g:i A');
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity'=> 'Viewed request named, '.$request->request_type
        ]);
        return view('request.view', compact('request'));
    }
    public function showAdmin($id)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $request = Service::findOrFail($id);
        $requestData = json_decode($request->data, true); 
        $request->data = $requestData;  
        $request->formatted_created_at = $request->created_at->format('F j, Y g:i A');
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity'=> 'Viewed request named, '.$request->request_type
        ]);
        return view('request.view-req', compact('request'));
    }

    public function cancel($id)
    {   
        $request = Service::findOrFail($id);
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity'=> 'Deleted a request named, '.$request->request_type
        ]);
        $request->delete();

        return redirect()->route('requestfile.index')->with('success', 'Request canceled successfully.');
    }

    public function reportIndex()
    {
        return view('file-report');
    }
    public function index()
    {
        return view('track-request');
    }

    public function track(Request $request)
    {
        $data = null;

        if ($request->has('c') && !empty(trim($request->input('c')))) {
            $code = $request->input('c');
            $data = Service::where('tracking_code', $code)->with('users')->first();
        }

        return view('track-request', compact('data'));
    }
}
