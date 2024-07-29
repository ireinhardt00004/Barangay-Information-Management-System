<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Nav;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Validator;
use Exception;

class ContentManagementNavsController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Fetch all pages
        $pages = Page::all();
        $pages_options = '';

        foreach ($pages as $pdata) {
            $pages_options .= '<option value="' . $pdata->id . '">' . base64_decode($pdata->page_name) . '</option>';
        }

        $pages_options = base64_encode($pages_options);

        // Fetch all navs with related pages
        $navs = Nav::with('page')->get();
        $navs_data = [];

        foreach ($navs as $data) {
            $navs_data[] = [
                'nav' => $data->nav_name,
                'page' => base64_decode($data->page->page_name ?? ''),
                'id' => $data->id
            ];
        }
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Visited CMS Nav page.'
        ]);
        return view('cms.navs-link', compact('pages_options', 'navs_data'));
    }

    public function destroy($id)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $nav = Nav::findorFail($id);
        if ($nav) {
            $nav->forcedelete();
            return response()->json(['success' => true, 'message' => 'Navigation deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Navigation not found.']);
        }
    }
    public function store(Request $request)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        // Validate the form data
        $request->validate([
            'nav_name' => 'required|string|max:255',
            'nav_page' => 'required|exists:pages,id',
        ]);
        Nav::create([
            'nav_name' => $request->input('nav_name'),
            'page_id' => $request->input('nav_page'),
            'user_id' => auth()->id() 
        ]);
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Save new nav link.'
        ]);
        return response()->json(['success' => true]);
    }

    public function indexPage()
{
    $authUser = auth()->user();
    if (!in_array($authUser->roles, ['admin', 'staff'])) {
        abort(403, 'Unauthorized');
    }
    $pages = Page::all()->map(function ($page) {
        return [
            'id' => $page->id, 
            'page_name' => base64_decode($page->page_name),
            'contents' => strlen(base64_decode($page->contents)) > 40 
                ? substr(base64_decode($page->contents), 0, 37) . '...' 
                : base64_decode($page->contents),
        ];
    });

    return view('cms.page', compact('pages'));
}


    public function createNewPage(Request $request)
    {
        try {
            $authUser = auth()->user();
            if (!in_array( $authUser->roles, ['admin', 'staff'])) {
                abort(403, 'Unauthorized');
            }
            // Get the plain text values
            $pageName = $request->page_name;
            $mdContent = $request->mdcont;
           // Base64 encode the contents
           // $encodedContent = base64_encode($mdContent);
           $encodedContent = $mdContent;
            // Validate the inputs
            $validator = Validator::make([
                'page_name' => $pageName,
                'md_content' => $encodedContent,
            ], [
                'page_name' => 'required|string|max:255|unique:pages,page_name',
                'md_content' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create the new page
            $page = new Page();
            $page->page_name = $pageName;
            $page->contents = $encodedContent; 
            $page->save();

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $e) {
            Log::error('Error creating new page: ', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function createPage(){
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        return view('cms.create-page');
    }
   
    public function destroyPage($id)
{
    try {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
        $page = Page::findOrFail($id);
        $decodedPageName = base64_decode($page->page_name);
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Deleted a page named ' . $decodedPageName,
        ]);
        $page->forceDelete();

        return response()->json(['success' => true, 'message' => 'Page deleted successfully.']);
    } catch (\Exception $e) {
        Log::error('Page deletion error: ' . $e->getMessage());

        return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
}
    public function editPage($id)
    {
        $authUser = auth()->user();
        if (!in_array( $authUser->roles, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }
    $page = Page::findOrFail($id);

    $data = [
        'id' => $id,
        'page_name' => base64_decode($page->page_name),
        'content' => base64_decode($page->contents),
    ];

    return view('cms.edit-page', $data);
    }

    public function updatePage(Request $request)
    {
        $id = intval($request->input('id'));
        $page_name = base64_decode($request->input('page_name'));
        $markdownContent = base64_decode($request->input('mdcont'));

        $existingPage = Page::where('page_name', $page_name)->where('id', '!=', $id)->first();

        if ($existingPage) {
            return response('exists', 400);
        }

        $page = Page::findOrFail($id);
        $page->page_name = base64_encode($page_name);
        $page->contents = base64_encode($markdownContent);
        $page->save();

        // Log the update activity
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Updated a page named ' . $page_name,
        ]);

        return response('true');
    }

}
