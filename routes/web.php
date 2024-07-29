<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContentManagementController;
use App\Http\Controllers\ContentManagementNavsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\RequestFileController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\EventController;

// CHECK DATABASE CONNECTION
Route::get('/test-db-connection', function () {
    try {
        DB::connection()->getPdo();
        return "Database connection established!";
    } catch (\Exception $e) {
        return "Could not connect to the database. Error: " . $e->getMessage();
    }
});

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', function () {
    return view('auth.login');
});
// Test Page (can be removed or updated as needed)


// Route::get('/register-success', function () {
//     return view('success-msg');
//    });
Route::get('/load', function () {
     return view('email.registration_success');
    });
// Authenticated Routes
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/dashboard', function () {
    //     return view('dashboard');
    // });

// Admin Routes
Route::middleware(['auth'])->group(function () {
    //VERIFY USER
    Route::get('/validate-accounts', [AdminController::class, 'showUnverifiedUsers'])->name('admin.unverified.users');
    Route::post('/admin/verify-user/approve/{id}', [AdminController::class, 'approveUser'])->name('user.approve');
    Route::post('/admin/verify-user/decline/{id}', [AdminController::class, 'declineUser'])->name('user.decline');
    
    
    Route::get('/dashboard', [AdminController::class, 'showDashboard'])->name('dashboard');
    Route::get('/admin-staff',[AdminController::class,'viewStaff'])->name('staff.view');
    //register new staff
    Route::post('/post-newstaff', [UserRegistrationController::class, 'storeStaff'])->name('store.staff');
    //delete existing staff
    Route::post('/delete-staff', [UserRegistrationController::class, 'deleteStaff'])->name('delete.staff');
    //view residents
    Route::get('/admin-resident', [AdminController::class,'viewResidents'])->name('resident.view');
    Route::post('/delete-resident', [UserRegistrationController::class, 'deleteResident'])->name('delete.resident');
    //view admin setttings
    Route::get('admin-settings', [AdminController::class,'viewSettings'])->name('settings.view');
    //admin setting update
    Route::post('/post-adminsetting',[ProfileController::class,'update'])->name('adminprofile.update');
    //view request log
    Route::get('/admin-requestlogs', [AdminController::class,'viewRequest'])->name('requestlog-view');
    //view all logs
    Route::get('/admin-alllogs',[ActivityLogController::class,'adminLogs'])->name('all-logs');
    Route::post('/delete-alllogs',[ActivityLogController::class,'deleteAll'])->name('delete-all-logs');
    //MY ACTIVITY LOG
    Route::get('/activity-log',[ActivityLogController::class,'activityLog'])->name('my-activity');
    Route::post('/delete-my-logs',[ActivityLogController::class,'deleteLogs'])->name('delete-my-logxz');
    //user side
    Route::get('/user/activity-log',[ActivityLogController::class,'useractivityLog'])->name('my-useractivity');
    Route::post('/delete-my-userlogs',[ActivityLogController::class,'deleteuserLogs'])->name('delete-my-userlog');
    //VIEW REPORTS
    Route::get('/view-filedreport',[AdminController::class,'viewReport'])->name('view-reportfiled');
    Route::get('report/view/{id}',[ReportController::class,'viewReportMsg'])->name('viewreport.index');
    Route::post('/reply-report',[ReportController::class,'sendReportReply'])->name('send-report-reply');
    Route::post('/mark-as-read/{id}',[ReportController::class,'markAsRead'])->name('mark-report-as-read');
    Route::delete('/delete-report/{id}',[ReportController::class,'deleteReport'])->name('deleted.report');
    //PROGRAMS
    Route::get('/admin-programs', [ProgramController::class, 'index'])->name('program.adminview');
    Route::get('/programs/create', [ProgramController::class, 'create'])->name('program.create');
    Route::post('/programs/store', [ProgramController::class, 'store'])->name('program.store');
    Route::get('/programs/edit/{id}', [ProgramController::class, 'edit'])->name('program.edit');
    Route::post('/prog-update/{id}', [ProgramController::class, 'update'])->name('program.updatez');
    Route::delete('/programs/delete/{id}', [ProgramController::class, 'delete'])->name('programz.delete');

    //CHATS
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chats', [ChatController::class, 'resident'])->name('userchat.index');
    Route::delete('/delete-conv/{receiverId}',[ChatController::class,'deleteConversation'])->name('delete-conversation');
 
    //CONTENT MANAGEMENT SYSTEM GENERAL
    Route::get('/cms-general',[ContentManagementController::class,'index'])->name('general.cms');
    //upload home image
    Route::post('/post-homeimg', [ContentManagementController::class, 'uploadHomeImage'])->name('post.home-img');
    //deletehome image
    Route::post('/delete-homeimage', [ContentManagementController::class, 'deleteHomeImage'])->name('delete.homeimage');
    //upload head logo
    Route::post('/post-headlogo', [ContentManagementController::class, 'uploadHeadLogo'])->name('post-headlogo');
    //save header button
    Route::post('/post-headbtn', [ContentManagementController::class, 'storeHeadBtn'])->name('headbtn.store');
    //delete header button  
    Route::post('/delete-headerbutton', [ContentManagementController::class, 'deleteHeaderBtn'])->name('delete.headerbtn');
    //update header button
    Route::put('/update-headbtn', [ContentManagementController::class, 'updateHeadButton'])->name('update-headbtn');
    //Upload new card
    Route::post('/post-newcards', [ContentManagementController::class, 'uploadNewCard'])->name('post-newcards');
    //delete card
    Route::post('/delete-headercard', [ContentManagementController::class, 'deleteHeaderCard'])->name('delete.headercard');
    //save footer link
    Route::post('/post-footer-links', [ContentManagementController::class, 'storeFooterLinks'])->name('post-footer-links');
    //delete footer Link
    Route::post('/delete-footerlinks', [ContentManagementController::class, 'deleteFooterLinks'])->name('delete.footerlink');
    // GENERAL UPDATE THEME, ABOUT, SERVICE
    Route::put('/general/update', [ContentManagementController::class, 'updateGeneral'])->name('cms-general.update');

    //CONTENT MANAGEMENT SYSTEM NAV LINKS
    Route::get('/cms-navlinks',[ContentManagementNavsController::class,'index'])->name('navlink-cms');
    //save nav link
    Route::post('/navs/store', [ContentManagementNavsController::class, 'store'])->name('navs.store');
    Route::delete('/navs/delete/{id}', [ContentManagementNavsController::class, 'destroy'])->name('navs.delete');
    Route::put('/navs/update/{id}', [ContentManagementNavsController::class, 'update'])->name('navs.update');

    //CONTENT MANAGEMENT SYSTEM PAGE
    Route::get('/cms-pages', [ContentManagementNavsController::class, 'indexPage'])->name('cmspages.index');
    Route::get('/pages/create', [ContentManagementNavsController::class, 'createPage'])->name('cmspages.create');
    Route::post('/create-newpage', [ContentManagementNavsController::class, 'createNewPage'])->name('createNewPage');
    Route::delete('/cmspages/delete/{id}', [ContentManagementNavsController::class, 'destroyPage'])->name('cmspages.destroy');
    // Show the edit form
    Route::get('/cmspages/edit/{id}', [ContentManagementNavsController::class, 'editPage'])->name('cmspages.edit');
    // Update the page
    Route::post('/cmspages/update', [ContentManagementNavsController::class, 'updatePage'])->name('cmspages.update');

    //ANNOUNCEMENT
    // List announcements
    Route::get('/admin-announcements', [AnnouncementController::class, 'index'])->name('announcements.indexpage');
    // Create announcement
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    // Edit announcement
    Route::get('/announcements/edit/{id}', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::post('/announcements/update/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
    // Delete announcement
    Route::post('/announcements/destroy', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    //SERVICE TYPE
    Route::get('/admin-servicetype',[ServiceController::class,'index'])->name('servicetype.adminview');
    Route::get('/create-servicetype',[ServiceController::class,'create'])->name('servicetype.create');
    Route::post('/post-servicetype',[ServiceController::class,'store'])->name('servicetype.store');
    Route::get('/edit-servicetype/{id}',[ServiceController::class,'edit'])->name('servicetype.edit');
    Route::post('/update-servicetype/{id}',[ServiceController::class,'update'])->name('servicetype.update');
    Route::delete('/delete-servicetype/{id}',[ServiceController::class,'delete'])->name('servicetype.delete');

    //MONITORING
    Route::get('/mon-pending',[MonitoringController::class,'pending'])->name('pending.mon');
    Route::get('/mon-approved',[MonitoringController::class,'approved'])->name('approved.mon');
    Route::get('/mon-declined',[MonitoringController::class,'declined'])->name('declined.mon');
    Route::post('/service/delete', [MonitoringController::class, 'deleteRequest'])->name('service.delete');
    Route::post('/modify-request', [MonitoringController::class, 'modifyRequest'])->name('modify.request');
    Route::get('/view-request/{id}', [MonitoringController::class, 'viewRequest'])->name('view.request');
    Route::post('/modify-approved-request', [MonitoringController::class, 'modifyApprovedRequest'])->name('modify.approved.request');
    Route::post('/decline/request', [MonitoringController::class, 'declineRequest'])->name('decline.request');
    Route::post('/pending-request/modify', [MonitoringController::class, 'modifyPendingRequest'])->name('pendingmodify.request');

    //STAFF DASHBOARD
    Route::get('/staff-dashboard',[StaffController::class,'index'])->name('staff.dashboard');
    Route::get('/staff-settings',[StaffController::class,'staffSettings'])->name('staff.settingss');
    
    //RESIDENT DASHBOARD
    Route::get('/user-dashboard',[ResidentController::class,'index'])->name('userdashboard.index');
    Route::get('/user/programs',[ResidentController::class,'program'])->name('userview.prog');
    Route::get('/user/view_program/{id}', [ResidentController::class, 'viewProgram'])->name('userprogram.view');
    //request file index
    Route::get('/request-file',[RequestFileController::class,'index'])->name('requestfile.index');
    //make request
    Route::get('/make-request',[RequestFileController::class, 'create'])->name('make-requestfile');
    //SUBMITE REQUEST
    Route::post('/request/submit', [RequestFileController::class, 'store'])->name('requestfilezz.submit');
    //SHOW REQUEST DATA
    Route::get('/requests/{id}', [RequestController::class, 'show'])->name('request.show');
    //delete request file on user side 
    Route::delete('/request/cancel/{id}', [RequestController::class, 'cancel'])->name('request.cancel');
    //show request admin and staff
    Route::get('/view-requests/{id}', [RequestController::class, 'showAdmin'])->name('adminrequest.show');
    
    // RECORDS
    Route::get('/records', [RecordController::class, 'index'])->name('records.index');
    Route::get('/new-records', [RecordController::class, 'create'])->name('records.create');
    Route::post('/store-records', [RecordController::class, 'store'])->name('records.store');
    Route::get('/view-record/{id}', [RecordController::class, 'show'])->name('record.show');
    Route::get('/edit-record/{id}', [RecordController::class, 'edit'])->name('record.edit'); 
    Route::put('/records/{record}', [RecordController::class, 'update'])->name('records.update');
    Route::delete('/records/delete/{recordId}', [RecordController::class, 'destroy'])->name('records.destroy');
    Route::get('/records/export', [RecordController::class, 'exportToExcel'])->name('records.export');
    Route::get('/saved_to_xlxs/{recordId}', [RecordController::class, 'exportSpecificRecordToExcel'])->name('record-export');
    
    //EVENT CALENDAR
    Route::get('/admin-event/calendar',[EventController::class,'index'])->name('admin.event-calendar');
    Route::post('/store-events', [EventController::class,'store'])->name('events.store');
    Route::delete('/events/delete/{id}', [EventController::class, 'destroy'])->name('events.destroy');
    
    //view setting user
    Route::get('/user-settings',[ResidentController::class,'settings'])->name('settings.userview');
    //view news and announcement
    Route::get('/news-and-announcement',[ResidentController::class,'news'])->name('resident.news');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/track-request',[RequestController::class,'index'])->name('track-request.index');
Route::get('/track_request', [RequestController::class, 'track'])->name('track_request');
Route::view('/registration-success', 'success-msg')->name('registration.success');

//report
Route::get('/file-report',[RequestController::class,'reportIndex'])->name('file-report.index');
Route::post('/send-filereport',[ReportController::class,'sendReport'])->name('sendreport.request');
Route::get('/about', [HomeController::class, 'about'])->name('about.view');
Route::get('/announcements', [HomeController::class, 'announcement'])->name('announcement.view');
Route::get('/service', [HomeController::class, 'service'])->name('service.view');
Route::get('/service-types',[HomeController::class,'serviceTypes'])->name('view-servicetypes');
Route::get('/view_service_type/{id}', [HomeController::class, 'viewServiceType'])->name('servicetypezxs.view');
Route::get('/programs', [HomeController::class, 'program'])->name('program.index');
Route::get('/view_program/{id}', [HomeController::class, 'viewProgram'])->name('program.view');
Route::get('/{page_name}', [HomeController::class, 'appendPage'])->name('append.page');
Route::get('/forgot/password',[PasswordResetLinkController::class,'create'])->name('forgotmy.password');
require __DIR__.'/auth.php';
