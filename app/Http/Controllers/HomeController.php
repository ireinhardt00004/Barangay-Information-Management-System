<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Footerz;
use App\Models\GeneralConf;
use App\Models\HeaderBtn;
use App\Models\Announcement;
use App\Models\HomeCard;
use App\Models\HomeImg;
use App\Models\Program;
use App\Models\Nav;
use App\Models\Page;
use App\Models\ServiceType;
use League\CommonMark\CommonMarkConverter;


class HomeController extends Controller
{
    //
    public function index() 
    {
        $footers = Footerz::all();
        $gens = GeneralConf::all();
        $headerbtns = HeaderBtn::all();
        $homecards = HomeCard::all();
        $homeimgs = HomeImg::all();


    return view('home', compact('footers','homeimgs', 'gens', 'headerbtns', 'homecards'));
    }

    public function about() {
        $footers = Footerz::all();
        $gens = GeneralConf::first();
        
        return view('about', compact('footers', 'gens'));
    }

    public function announcement() {

        $announcements = Announcement::latest()->paginate(10);
        $footers = Footerz::all();
        $gens = GeneralConf::all();
        return view('announcement', compact('announcements','footers', 'gens'));
    }
    
    public function service() {
        $gens = GeneralConf::all();
        $footers = Footerz::all();
        return view('service', compact('gens', 'footers'));
 
    }
    
    public function program() {
        $result = Program::latest()->get();
        $gens = GeneralConf::all();
        $footers = Footerz::all();
        return view ('program', compact('result', 'footers','gens'));
    }

    public function viewProgram($id)
    {
    $res = Program::findOrFail($id);

    // Decode the base64 ENcyrption
    $res->cover = base64_decode($res->cover);
    $res->title = base64_decode($res->title);
    $res->content = base64_decode($res->content);
    $footers = Footerz::all();
    $gens = GeneralConf::all();
    return view('view_program', compact('res','footers','gens'));
    }

    public function serviceTypes() {
        $result = ServiceType::all();
        $gens = GeneralConf::all();
        $footers = Footerz::all();
        return view ('service-type', compact('result', 'footers','gens'));
    }
    public function viewServiceType($id)
    {
    $res = ServiceType::findOrFail($id);

    // Decode the base64 ENcyrption
    $res->photo = base64_decode($res->photo);
    $res->request_type = base64_decode($res->request_type);
    $res->description = base64_decode($res->description);
    $footers = Footerz::all();
    $gens = GeneralConf::all();
    return view('view-servicetype', compact('res','footers','gens'));
    }

    public function appendPage($pageName)
    {
    // Fetch the page content from your database
    $page = Page::where('page_name', base64_encode($pageName))->firstOrFail();
    $pageContent = base64_decode($page->contents);

    // Convert Markdown to HTML
    $converter = new CommonMarkConverter();
    $pageViewHtml = $converter->convertToHtml($pageContent);

    // Fetch footer data
    $footers = Footerz::all();
    $gens = GeneralConf::all();

    // Pass the converted HTML content to the view
    return view('cstm_view', compact('pageViewHtml', 'footers', 'gens'));
    }
   

    
}
