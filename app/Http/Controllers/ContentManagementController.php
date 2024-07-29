<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeImg;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;
use App\Models\HeaderBtn;
use App\Models\GeneralConf;
use App\Models\HomeCard;
use App\Models\Footerz;
use App\Models\ServiceType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
class ContentManagementController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $generalConfig = GeneralConf::first(); 
        $homecards = HomeCard::all();
        $footers = Footerz::all();
        $serviceTypes = ServiceType::all(); 
        $homeimgs = HomeImg::all(); 
        $headerBtns = HeaderBtn::all();
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited CMS General page.'
        ]);
        return view('cms.general', compact('generalConfig', 'homecards', 'serviceTypes', 'footers', 'homeimgs', 'headerBtns'));
    }

    public function updateGeneral(Request $request)
{
    try {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Validate the request data
        $validated = $request->validate([
            'theme.primary' => 'required|string',
            'theme.bg' => 'required|string',
            'theme.text' => 'required|string',
            'em_contacts.city_hall' => 'nullable|string',
            'em_contacts.police' => 'nullable|string',
            'web_title' => 'required|string',
            'meta_desc' => 'nullable|string',
            'head_title' => 'nullable|string',
            'about_title' => 'nullable|string',
            'about_description' => 'nullable|string',
            'service_max_no' => 'nullable|string',
            'payment_amt' => 'nullable|string',
        ]);

        // Find the existing GeneralConf record
        $generalConf = GeneralConf::first();

        if (!$generalConf) {
            // Create a new record if none exists
            $generalConf = new GeneralConf();
        }

        // Encode the theme values to base64
        $validated['theme']['primary'] = base64_encode($validated['theme']['primary']);
        $validated['theme']['bg'] = base64_encode($validated['theme']['bg']);
        $validated['theme']['text'] = base64_encode($validated['theme']['text']);

        // Encode the emergency contact values to base64
        $validated['em_contacts']['city_hall'] = base64_encode($validated['em_contacts']['city_hall']);
        $validated['em_contacts']['police'] = base64_encode($validated['em_contacts']['police']);

        // Update the GeneralConf record
        $generalConf->theme = $validated['theme'];
        $generalConf->em_contacts = $validated['em_contacts'];
        $generalConf->title = $validated['web_title'];
        $generalConf->meta_desc = $validated['meta_desc'];
        $generalConf->head_title = $validated['head_title'];
        $generalConf->about_title = $validated['about_title'];
        $generalConf->about_desc = $validated['about_description'];
        $generalConf->max_requests = $validated['service_max_no'];
        $generalConf->payment_amt = $validated['payment_amt'];
        $generalConf->save();

        // Redirect or return a response
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Error occurred while saving configurations: ' . $e->getMessage(), [
            'request' => $request->all(),
            'exception' => $e
        ]);
        return response()->json(['error' => 'An error occurred while saving configurations.'], 500);
    }
}

    public function uploadHeadLogo(Request $request)
    {
        try {
            $authUser = auth()->user();
            if (!in_array( $authUser->roles, ['admin', 'staff'])) {
                abort(403, 'Unauthorized');
            }
            $request->validate([
                'imageFilez' => 'required|image|mimes:jpeg,png,gif|max:2048',
            ]);

            $file = $request->file('imageFilez');
            $filename = $file->getClientOriginalName();
            $publicPath = public_path('assets/head_logo');
            
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            $generalConf = GeneralConf::first();

            if ($generalConf) {
                if ($generalConf->logo) {
                    $oldFilePath = $publicPath . '/' . $generalConf->logo;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $file->move($publicPath, $filename);
                $generalConf->logo = $filename;
                $generalConf->save();
            } else {
                return response()->json(['error' => 'No GeneralConf record found.'], 404);
            }
            ActivityLog::create([
                'user_id' => auth()->user()->id,
                'activity' => 'Uploaded new Logo.'
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error occurred while uploading logo: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e
            ]);
            return response()->json(['error' => 'An error occurred while uploading the logo.'], 500);
        }
    }

    public function uploadHomeImage(Request $request)
{
    try {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Validate the request
        $request->validate([
            'imageFilez' => 'required|image|mimes:jpeg,png,gif|max:2048',
        ]);
        $file = $request->file('imageFilez');
        $filename = $file->getClientOriginalName();
        $base64Filename = base64_encode($filename);
        $publicPath = public_path('assets/imgs_uploads');
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        $file->move($publicPath, $filename);
        $homeImg = new HomeImg();
        $homeImg->name = $base64Filename;
        $homeImg->img_path = $base64Filename; 
        $homeImg->user_id = auth()->id(); 
        $homeImg->save();
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Uploaded new Home image.'
        ]);
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Error occurred while uploading image: ' . $e->getMessage(), [
            'request' => $request->all(),
            'exception' => $e
        ]);
        return response()->json(['error' => 'An error occurred while uploading the image.'], 500);
    }
}
    //delete home image
    public function deleteHomeImage(Request $request)
{
    try {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Validate the request
        $request->validate([
            'item_id' => 'required|integer|exists:home_imgs,id',
        ]);

        // Get the image ID
        $itemId = $request->input('item_id');

        // Find the image record in the database
        $homeImg = HomeImg::find($itemId);
        
        if (!$homeImg) {
            return response()->json(['error' => 'Image not found.'], 404);
        }

        // Delete the file from the public directory
        $filePath = public_path('assets/imgs_uploads/' . $homeImg->img_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete the record from the database
        $homeImg->forcedelete();
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Deleted a Home image.'
        ]);
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Error occurred while deleting image: ' . $e->getMessage(), [
            'request' => $request->all(),
            'exception' => $e
        ]);
        return response()->json(['error' => 'An error occurred while deleting the image.'], 500);
    }
}   
    public function storeHeadBtn(Request $request)
    {
        try {
            $authUser = auth()->user();
            if (!in_array( $authUser->roles, ['admin', 'staff'])) {
                abort(403, 'Unauthorized');
            }
            // Validate the request
            $request->validate([
                'btn_name' => 'required|string|max:255',
                'btn_url' => 'required|string|max:255', // Ensure this is a valid URL
                'btn_outline' => 'nullable|boolean', // Validate as boolean or null
            ]);
            $userID =auth()->user()->id;
            // Create a new HeadBtn record
            $headBtn = new HeaderBtn();
            $headBtn->name = $request->input('btn_name');
            $headBtn->link = $request->input('btn_url');
            $headBtn->user_id = $userID;
            // Convert 'on' to true, otherwise false or null
            $headBtn->outline = $request->input('btn_outline') === 'on';
    
            $headBtn->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error occurred while creating header button: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e
            ]);
            return response()->json(['error' => 'An error occurred while creating the header button.'], 500);
        }

    }
     public function deleteHeaderBtn(Request $request)
{
    try {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Validate the request
        $request->validate([
            'item_id' => 'required|integer|exists:header_btns,id',
        ]);

        // Get the image ID
        $itemId = $request->input('item_id');

        // Find the image record in the database
        $headerbtn = HeaderBtn::find($itemId);
    

        // Delete the record from the database
        $headerbtn->forcedelete();
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Deleted a Header Button.'
        ]);
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Error occurred while deleting image: ' . $e->getMessage(), [
            'request' => $request->all(),
            'exception' => $e
        ]);
        return response()->json(['error' => 'An error occurred while deleting the image.'], 500);
    }
}   
    public function updateHeadButton(Request $request)
    {
        try {
            $authUser = auth()->user();
            if (!in_array( $authUser->roles, ['admin', 'staff'])) {
                abort(403, 'Unauthorized');
            }
            // Validate the request
            $request->validate([
                'btn_id' => 'required|integer|exists:headbtns,id',
                'btn_name' => 'required|string|max:255',
                'btn_url' => 'required|url|max:255',
                'btn_outline' => 'nullable|boolean',
            ]);

            // Retrieve the current user's ID
            $userID = auth()->user()->id;

            // Find the header button record based on id and user_id
            $headBtn = HeaderBtn::where('id', $request->input('btn_id'))
                                ->where('user_id', $userID)
                                ->first();

            // Check if the record exists
            if (!$headBtn) {
                return response()->json(['error' => 'Record not found or does not belong to the user.'], 404);
            }

            // Update the record
            $headBtn->name = $request->input('btn_name');
            $headBtn->link = $request->input('btn_url');
            $headBtn->outline = $request->input('btn_outline') === 'on'; // Adjusted for checkbox handling
            $headBtn->save();

            // Return a success response
            return response()->json(['success' => true]);

        } catch (QueryException $e) {
            // Log and return an error response for query exceptions
            Log::error('Error occurred while updating header button: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e
            ]);
            return response()->json(['error' => 'An error occurred while updating the header button.'], 500);

        } catch (\Exception $e) {
            // Log and return an error response for other exceptions
            Log::error('Error occurred while updating header button: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e
            ]);
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function uploadNewCard(Request $request)
{
    try {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Validate the request
        $request->validate([
            'imageFilez' => 'required|image|mimes:jpeg,png,gif|max:2048',
            'title' => 'required|string|max:255',
            'link' => 'required|string|max:255',
        ]);

        // Handle file upload
        $file = $request->file('imageFilez');
        $filename = time() . '_' . $file->getClientOriginalName(); 
        $base64Filename = base64_encode($filename);
        $publicPath = public_path('header_cards');
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }
        $file->move($publicPath, $filename);

        // Create a new HomeCard record
        $homeCard = new HomeCard();
        $homeCard->img = $base64Filename;
        $homeCard->title = $request->input('title');
        $homeCard->link = $request->input('link');
        $homeCard->user_id = auth()->id();
        $homeCard->save();

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Uploaded new Home image.'
        ]);

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        // Log and return error response
        Log::error('Error occurred while uploading image: ' . $e->getMessage(), [
            'request' => $request->all(),
            'exception' => $e
        ]);
        return response()->json(['error' => 'An error occurred while uploading the image.'], 500);
    }
}
    public function deleteHeaderCard(Request $request)
    {
    try {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Validate the request
        $request->validate([
            'item_id' => 'required|integer|exists:home_cards,id',
        ]);

        // Get the image ID
        $itemId = $request->input('item_id');

        // Find the image record in the database
        $headercard = HomeCard::findorFail($itemId);
    

        // Delete the record from the database
        $headercard->forcedelete();
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Deleted a Home Card.'
        ]);
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Error occurred while deleting image: ' . $e->getMessage(), [
            'request' => $request->all(),
            'exception' => $e
        ]);
        return response()->json(['error' => 'An error occurred while deleting the image.'], 500);
    }
}   
public function storeFooterLinks(Request $request)
{
    try {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Validate the request
        $request->validate([
            'link_gov' => 'nullable|url|max:255',
            'link_social' => 'nullable|url|max:255',
            'link_contact' => 'nullable|url|max:255',
        ]);
        $userID = auth()->user()->id;
        // Create or update the Footer record
        // $footer = Footerz::firstOrNew(['user_id' => auth()->id()]); 
        $footer = new Footerz();
        $footer->gov = $request->input('link_gov');
        $footer->social = $request->input('link_social');
        $footer->contact = $request->input('link_contact');
        $footer->user_id = $userID;
        $footer->save();

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Added new footer links.'
        ]);

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        // Log and return error response
        Log::error('Error occurred while adding footer links: ' . $e->getMessage(), [
            'request' => $request->all(),
            'exception' => $e
        ]);
        return response()->json(['error' => 'An error occurred while adding the links.'], 500);
    }
}
    public function deleteFooterLinks(Request $request)
    {
    try {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Validate the request
        $request->validate([
            'item_id' => 'required|integer|exists:footerzs,id',
        ]);

        // Get the image ID
        $itemId = $request->input('item_id');

        // Find the image record in the database
        $headercard = Footerz::findorFail($itemId);
    

        // Delete the record from the database
        $headercard->forcedelete();
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Deleted a Footer link.'
        ]);
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Error occurred while deleting image: ' . $e->getMessage(), [
            'request' => $request->all(),
            'exception' => $e
        ]);
        return response()->json(['error' => 'An error occurred while deleting the image.'], 500);
    }
}   

}