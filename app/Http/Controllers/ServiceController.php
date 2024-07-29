<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceType;
use App\Models\ActivityLog;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
class ServiceController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $serviceTypes = ServiceType::all();
        return view('service.index', compact('serviceTypes'));
    }
    public function create()
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Return the create view
        return view('service.create'); 
    }

    public function store(Request $request)
    {
        try {
            $authUser = auth()->user();
            if (!in_array( $authUser->roles, ['admin', 'staff'])) {
                abort(403, 'Unauthorized');
            }
            // Validate the request data
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'request_type' => 'required|string|max:255',
                'description' => 'required|string',
            ]);
    
            // Handle file upload
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                
                // Debugging information
                \Log::info('Uploaded file name: ' . $file->getClientOriginalName());
                \Log::info('Uploaded file mime type: ' . $file->getMimeType());
                
                // Generate a unique file name and store the file in the public directory
                $filename = time() . '_' . $file->getClientOriginalName();
                $coverPath = $file->move(public_path('service_type_imgs'), $filename);
                
                // Debugging information
                \Log::info('File stored at: ' . $coverPath);
                
                // Encode the filename in base64
                $encodedFilename = base64_encode($filename);
            } else {
                return back()->withErrors(['photo' => 'No file uploaded']);
            }
    
            // Create a new program record
            $srtype = ServiceType::create([
                'photo' => $encodedFilename,
                'request_type' => base64_encode($request->request_type),
                'description' => base64_encode($request->description),
                'user_id' => auth()->user()->id,
            ]);
    
            // Decode the title for logging purposes
            $title = base64_decode($srtype->request_type);
    
            // Log the creation activity
            ActivityLog::create([
                'user_id' => auth()->user()->id,
                'activity' => 'Created a new Service Type, '.$title
            ]);
    
            // Redirect back with success message
            return redirect()->route('servicetype.adminview')->with('success', 'Service Type created successfully.');
        } catch (QueryException $e) {
            // Log the exception message along with request data
            \Log::error('Error occurred while registering service type: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e
            ]);
    
            // Redirect back with error message
            return redirect()->back()->with('error', 'An error occurred while registering the program.');
        }
    }
    
    public function edit($id)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $service = ServiceType::findOrFail($id);
        $service->request_type = base64_decode($service->request_type);
        $service->description = base64_decode($service->description);
        $service->photo = base64_decode($service->photo);
        return view('service.edit', [
            'service' => $service,
            'photo' => $service->photo,
            'request_type' => $service->request_type,
            'description' => $service->description,
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Validate the request data
        $request->validate([
            'request_type' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // Find the program
        $service = ServiceType::findOrFail($id);
        $title = base64_decode($service->request_type);

        // Log the deletion activity
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Edited a service named, '.$title
        ]);
        // Handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            
            // Debugging information
            \Log::info('Uploaded file name: ' . $file->getClientOriginalName());
            \Log::info('Uploaded file mime type: ' . $file->getMimeType());
            
            // Get the old cover path
            $oldCoverPath = base64_decode($service->cover);
            if ($oldCoverPath && file_exists(public_path('service_type_imgs/' . $oldCoverPath))) {
                // Delete the old image if it exists
                unlink(public_path('service_type_imgs/' . $oldCoverPath));
            }
    
            // Store the new file in the public directory
            $filename = $file->getClientOriginalName();
            $coverPath = $file->move(public_path('service_type_imgs'), $filename);
            
            // Debugging information
            \Log::info('File stored at: ' . $coverPath);
        } else {
            // Retain old cover path if no new file is uploaded
            $filename = base64_decode($service->photo);
        }
        $service->update([
            'photo' => base64_encode($filename),
            'request_type' => base64_encode($request->input('request_type')),
            'description' => base64_encode($request->input('description')),
        ]);
        return redirect()->route('servicetype.adminview')->with('success', 'service updated successfully.');
    }
    
    public function delete(Request $request, $id)
{
    try {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Fetch the service to be deleted
        $service = ServiceType::findOrFail($id);
        $title = base64_decode($service->request_type);

        // Log the deletion activity
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Deleted a service type named, '.$title
        ]);

        // Delete the service (soft delete)
        $service->forceDelete();
        return redirect()->route('servicetype.adminview')->with('success', 'Service Type deleted successfully.');
    } catch (\Exception $e) {
        \Log::error('Error deleting service: '.$e->getMessage());

        // Redirect back with error message
        return redirect()->route('servicetype.adminview')->with('error', 'Failed to delete the service types. Please try again.');
    }
}


}
