<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', 'Dashboard@index')->middleware('auth');
Route::get('/welcome', 'ProfilesController@welcome')->middleware('auth');
Route::get('/upload', 'UploadController@index')->middleware('auth');
Route::get('/uploadmixed', 'UploadController@mixed')->middleware('auth');
Route::post('/upload_parse', 'UploadController@parseUpload')->middleware('auth')->name('upload_parse');
Route::post('/upload_mixed', 'UploadController@mixedUpload')->middleware('auth')->name('upload_mixed');
Route::post('/upload_process', 'UploadController@processUpload')->middleware('auth')->name('upload_process');
Route::get('/messages/{id}', 'StreamController@fetchMessages')->middleware('auth')->middleware('ability:viewStream');
Route::post('/messages/{id}', 'StreamController@sendMessage')->middleware('auth')->middleware('ability:viewStream');
Route::post('/sendMessageToAllProperties', 'StreamController@sendMessageToAll')->middleware('auth')->middleware('ability:viewStream');
Route::post('/sendMessageToProperty', 'StreamController@sendMessageToProperty')->middleware('auth')->middleware('ability:viewStream');
Route::get('/search/data', 'SearchController@data')->middleware('auth');
Route::get('/search', 'SearchController@index')->middleware('auth');
Route::get('/notificationsData/', 'ActivitiesController@notificationsData')->middleware('auth');
Route::get('/notifications/', 'ActivitiesController@notifications')->middleware('auth');
Route::get('/userid/', 'ProfilesController@userid')->middleware('auth');
Route::get('/payment', 'PaymentMethodController@index')->middleware('auth');
Route::post('/payment', 'PaymentMethodController@subscribe')->middleware('auth');

// Admin ops
Route::get('/admin', 'AdminController@index')->middleware('auth'); 
Route::get('/admin/users', 'AdminController@users')->middleware('auth'); 
Route::get('/admin/agents', 'AdminController@agents')->middleware('auth'); 
Route::get('/admin/landlords', 'AdminController@landlords')->middleware('auth'); 
Route::get('/admin/tenants', 'AdminController@tenants')->middleware('auth'); 
Route::post('/admin/user/search', 'AdminController@usersearch')->middleware('auth'); 
Route::delete('/admin/users/delete/{id}', 'AdminController@deleteuser')->middleware('auth');

// User Operations
Route::post('/profile/create', 'ProfilesController@create')->middleware('auth');
Route::post('/profile/update', 'ProfilesController@update')->middleware('auth');
Route::get('/profile/edit', 'ProfilesController@edit')->middleware('auth')->name('profile');
Route::get('/invite/user', 'InviteAgent@index')->middleware('auth');
Route::post('/invite/user', 'InviteAgent@store')->middleware('auth');
Route::get('/getActivities', 'ActivitiesController@getActivities')->middleware('auth');
Route::get('/getLatestStreams', 'StreamController@getLatestStreamActivity')->middleware('auth');

Route::get('/invite/tenants', 'Agents\InviteTenants@index')->middleware('auth')->middleware('ability:createTenant');
Route::post('/invite/tenants', 'Agents\InviteTenants@invite')->middleware('auth')->middleware('ability:createTenant');
Route::get('agent/invite', 'InviteAgent')->middleware('auth');
Route::post('agent/create', 'InviteAgent@CreateInvite')->middleware('auth');
Route::get('agency/agents', 'Agents\AgentsController@getAgentsOnAgency')->middleware('auth');
Route::get('agency/admin', 'Agents\AgencyController@admin')->middleware('auth');
Route::get('agency/admin/agents', 'Agents\AgencyController@agents')->middleware('auth');
Route::get('agency/properties', 'Agents\AgencyController@properties')->middleware('auth');
Route::get('agency/landlords', 'Agents\AgencyController@landlords')->middleware('auth');
Route::get('agency/tenants', 'Agents\AgencyController@tenants')->middleware('auth');
Route::get('agency/contractors', 'Agents\AgencyController@contractors')->middleware('auth');
Route::delete('agency/admin/delete/contractor/{uuid}', 'Agents\AgencyController@deleteContractor')->middleware('auth');
Route::delete('agency/admin/delete/agent/{uuid}', 'Agents\AgencyController@deleteAgent')->middleware('auth');
Route::post('/agency/admin/search/agents', 'Agents\AgencyController@searchAgent')->middleware('auth');
Route::post('/agency/admin/search/contractors', 'Agents\AgencyController@searchContractor')->middleware('auth');
// Issues
Route::get('/issues/create', 'Issues\IssueController@create')->middleware('auth')->middleware('ability:createIssue');
Route::get('/issue/{uuid}', 'Issues\IssueController@show')->middleware('auth')->middleware('ability:indexIssue');
Route::get('/issues/', 'Issues\IssueController@index')->middleware('auth')->middleware('ability:indexIssue');
Route::get('/propertyissues/{uuid}', 'Issues\IssueController@indexByProperty')->middleware('auth')->middleware('ability:createIssue');
Route::get('/getIssues/{uuid?}', 'Issues\IssueController@getIssues')->middleware('auth')->middleware('ability:createIssue');
Route::get('/exportIssues/{uuid?}', 'Issues\IssueController@exportIssues')->middleware('auth')->middleware('ability:createIssue');
Route::get('/issue/{uuid}/edit', 'Issues\IssueController@edit')->middleware('auth')->middleware('ability:editIssue');
Route::post('/issue/{uuid}/updateIssue', 'Issues\UpdateIssueController@updateIssue')->middleware('auth')->middleware('ability:updateIssue');
Route::post('/issue/store', 'Issues\IssueController@store')->middleware('auth')->middleware('ability:storeIssue');
Route::post('/issue/{uuid}/add-log-entry', 'Issues\UpdateIssueController@addLogEntry')->middleware('auth')->middleware('ability:updateIssue');
Route::get('/issue/{uuid}/bid', 'Issues\BidController@index')->middleware('auth')->middleware('ability:updateIssue');
Route::get('/issue/{issue}/acceptbid/{contractor}', 'Issues\BidController@accept')->middleware('auth')->middleware('ability:updateIssue');
Route::delete('/issue/{uuid}/delete', 'Issues\IssueController@destroy')->middleware('auth')->middleware('ability:deleteIssue');

// Routes for reminders
// Properties
Route::middleware('auth')->name('property')->prefix('property')->group(function(){
    Route::get('/', 'PropertiesController@index')->middleware('ability:viewProperty');
    Route::get('/data', 'PropertiesController@indexData')->middleware('ability:viewProperty');
    Route::get('/create', 'PropertiesController@create')->middleware('ability:createProperty');
    Route::post('/store', 'PropertiesController@store')->middleware('ability:storeProperty');
    Route::get('/{uuid}', 'PropertiesController@show')->middleware('ability:viewProperty');
    Route::delete('/{uuid}/delete', 'PropertiesController@delete')->middleware('ability:deleteProperty');
    Route::get('/{uuid}/edit', 'PropertiesController@edit')->middleware('ability:editProperty');
    Route::post('/{uuid}/update', 'PropertiesController@update')->middleware('ability:updateProperty');
    Route::get('/{uuid}/documents', 'Properties\DocumentController@index')->middleware('ability:updateProperty');
    Route::post('/{uuid}/document/upload', 'Properties\DocumentController@uploadFile')->middleware('ability:updateProperty');
    Route::get('/{uuid}/document/download/{path}', 'Properties\DocumentController@downloadFile');
    Route::get('/{uuid}/document/delete/{path}', 'Properties\DocumentController@deleteFile');
    Route::get('/rent/{uuid}', 'Properties\RentController@index');
    Route::post('/rent/{uuid}/paid', 'Properties\RentController@paid');
});
// Landlords
Route::middleware('auth')->name('landlord')->prefix('landlord')->group(function(){
    Route::get('/create', 'LandlordsController@create')->middleware('ability:createLandlord');
    Route::post('/store', 'LandlordsController@store')->middleware('ability:storeLandlord');
    Route::delete('/{uuid}/delete', 'LandlordsController@delete')->middleware('ability:deleteLandlord');
    Route::get('/{uuid}/edit', 'LandlordsController@edit')->middleware('ability:editLandlord');
    Route::get('/{uuid}', 'LandlordsController@show')->middleware('ability:indexLandlord');
    Route::post('/{uuid}/update', 'LandlordsController@update')->middleware('ability:updateLandlord');
    Route::get('/{uuid}/properties', 'PropertiesController@indexProperties')->middleware('ability:indexLandlord');
    Route::get('/{uuid}/report', 'LandlordsController@report')->middleware('ability:indexLandlord');
    Route::post('/{uuid}/generate-report', 'LandlordsController@generateReport')->middleware('ability:indexLandlord');
});
Route::get('/landlords', 'LandlordsController@index')->middleware('auth')->middleware('ability:indexLandlord');
Route::get('/landlords/getContacts', 'LandlordsController@getContactsForMenu')->middleware('auth')->middleware('ability:indexTenant');

//Stream

//Get Stream by topic ID
Route::get('/stream/{uuid}', 'StreamController@show')->middleware('auth')->middleware('ability:viewStream');
Route::get('/stream/getLatestVisit/{uuid}', 'StreamController@getLatestVisit')->middleware('auth')->middleware('ability:viewStream');
Route::get('/stream/getUsersWhoHaveRead/{uuid}/{id}', 'StreamController@getUsersWhoHaveReadMessage')->middleware('auth')->middleware('ability:viewStream');

//Post to Stream by topic ID
Route::post('/stream/{uuid}', 'StreamController@post')->middleware('auth')->middleware('ability:viewStream');

//Post a file to the stream
Route::post('/uploadMedia/{uuid}', 'StreamController@uploadMediaFile')->middleware('auth');

//Get Streams list
Route::get('/streams/data', 'StreamController@indexStreams')->middleware('auth')->middleware('ability:viewStream');

Route::get('/directmessage/{type}/{uuid}', 'DirectMessageController@showMessages')->middleware('auth');
Route::get('/dmPage', 'DirectMessageController@dmPage')->middleware('auth')->middleware('ability:indexTenant');
Route::get('/directmessageread/{uuid}', 'DirectMessageController@markMessageAsRead')->middleware('auth');
Route::get('/unreadMessages', 'DirectMessageController@getUnreadMessages')->middleware('auth');
Route::post('/senddirectmessage/', 'DirectMessageController@sendMessage')->middleware('auth');
// Tenants
Route::get('/tenant/create', 'TenantsController@create')->middleware('auth')->middleware('ability:createTenant');
Route::post('/tenant/store', 'TenantsController@store')->middleware('auth')->middleware('ability:storeTenant');
Route::post('/tenant/search', 'TenantsController@search')->middleware('auth')->middleware('ability:indexTenant');
Route::get('/tenant/{uuid}', 'TenantsController@show')->middleware('auth')->middleware('ability:indexTenant');
Route::delete('/tenant/{uuid}/delete', 'TenantsController@delete')->middleware('auth')->middleware('ability:deleteTenant');
Route::get('/tenant/{uuid}/edit', 'TenantsController@edit')->middleware('auth')->middleware('ability:editTenant');
Route::post('/tenant/{uuid}/update', 'TenantsController@update')->middleware('auth')->middleware('ability:editTenant');
Route::get('/tenants/{uuid}', 'TenantsController@indexByProperty')->middleware('auth')->middleware('ability:indexTenant');
Route::get('/tenants', 'TenantsController@index')->middleware('auth')->middleware('ability:indexTenant');
Route::get('/getTenantCounts', 'TenantsController@getTenantCounts')->middleware('auth')->middleware('ability:indexTenant');
Route::get('/tenant/{uuid}/documents', 'Tenants\DocumentsController@index')->middleware('auth')->middleware('ability:indexTenant');
Route::get('/yourDocuments', 'Tenants\DocumentsController@ownDocuments')->middleware('auth');
Route::post('/tenant/{uuid}/document/upload', 'Tenants\DocumentsController@uploadFile')->middleware('auth');
Route::get('tenant/{uuid}/document/download/{path}', 'Tenants\DocumentsController@downloadFile')->middleware('auth');
Route::get('/getMyDocuments', 'Tenants\DocumentsController@getDocumentsForTenant')->middleware('auth');
Route::get('/getDocuments/{type}/{uuid}', 'BaseDocumentController@getDocumentsByAjax')->middleware('auth');
Route::get('tenant/{uuid}/document/delete/{path}', 'Tenants\DocumentsController@deleteFile')->middleware('auth');
Route::get('getAgents', 'Tenants\ContactController@getAgents')->middleware('auth');

Route::get('documents', 'DocumentsController@index')->middleware('auth')->middleware('ability:documentStorage');
Route::get('getDocumentCounts', 'DocumentsController@getDocumentCounts')->middleware('auth')->middleware('ability:documentStorage');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.update');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Auth::routes();
Route::get('/autologin', function (Request $request) {
    if (! $request->hasValidSignature()) {
        abort(403);
    }
    Auth::loginUsingId($request->input('user_id'));
    return redirect('/')->with('message',  'Logged in successfully');
})->name('autologin');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterAgentController@showRegistrationForm')->name('register');
Route::get('registerForm', 'Auth\RegisterAgentController@showRegistrationForm');

// TIG - New Register Route
Route::namespace('Auth')->prefix('register')->group(function(){
    Route::post('/step1', 'RegisterAgentController@PostStep1');
    Route::get('/step2', 'RegisterAgentController@Step2');
    Route::post('/step2', 'RegisterAgentController@PostStep2');
    Route::get('/step3', 'RegisterAgentController@Step3');
    Route::post('/step3', 'RegisterAgentController@PostStep3');
    Route::get('/step4', 'RegisterAgentController@Step4');
    Route::post('/step4', 'RegisterAgentController@PostStep4');
    Route::post('/create', 'RegisterAgentController@create');

    Route::get('/tenant', 'RegisterTenantController@tenant');
    Route::post('/tenant', 'RegisterTenantController@PostTenantStep1');
    Route::get('/tenantStep2', 'RegisterTenantController@TenantStep2');
    Route::post('/tenantStep2', 'RegisterTenantController@PostTenantStep2');
    Route::get('/tenantStep3', 'RegisterTenantController@tenantStep3');
    Route::post('/createTenant', 'RegisterTenantController@createTenant');

    Route::get('/landlord', 'RegisterLandlordController@landlord');
    Route::post('/landlord', 'RegisterLandlordController@postLandlordStep1');
    Route::get('/landlordStep2', 'RegisterLandlordController@landlordStep2');
    Route::post('/landlordStep2', 'RegisterLandlordController@postLandlordStep2');
    Route::get('/landlordStep3', 'RegisterLandlordController@landlordStep3');
    Route::post('/createLandlord', 'RegisterLandlordController@createLandlord');

    Route::get('/contractor', 'RegisterContractorController@register');
    Route::post('/contractor', 'RegisterContractorController@PostStep1');
    Route::get('/contractorstep2', 'RegisterContractorController@Step2');
    Route::post('/contractorstep2', 'RegisterContractorController@PostStep2');
    Route::get('/contractorstep3', 'RegisterContractorController@Step3');
    Route::post('/contractorstep3', 'RegisterContractorController@PostStep3');
    Route::get('/contractorstep4', 'RegisterContractorController@Step4');
    Route::post('/contractorstep4', 'RegisterContractorController@PostStep4');
    Route::get('/contractorstep5', 'RegisterContractorController@Step5');
    Route::post('/contractorcreate', 'RegisterContractorController@create');
});

// Invitation Routes...
Route::get('invite/{refCode}', 'Auth\RegisterController@showInviteForm');
Route::post('invite/register', 'Auth\RegisterController@createUserFromInvite')->name('createUserFromInvite');


Route::get('/home', 'HomeController@index')->name('home');
Route::post('stripewebhooks','StripeHookController@handleWebhook');

// Routes for reminders
Route::middleware('auth')->middleware('ability:createreminders')->name('reminders.')->prefix('reminders')->group(function(){
    Route::get('/', 'ReminderController@index')->name('index');
    Route::get('manage/{type}/{uuid}', 'ReminderController@manage')->name('manage');
    Route::get('manage', 'ReminderController@manageAll')->name('manage');
    Route::get('view/{type}/{uuid}', 'ReminderController@view')->name('view');
    Route::get('create/{type}/{uuid}', 'ReminderController@create')->name('create');
    Route::post('api/add','ReminderController@addReminder')->name('api.add');
    Route::delete('delete/{id}', 'ReminderController@deleteReminder')->name('delete');
    Route::get('edit/{id}', 'ReminderController@editReminder')->name('edit');
    Route::post('edit/post', 'ReminderController@updateReminder')->name('edit.post');
    Route::post('create/post', 'ReminderController@createReminder')->name('edit.post');
});

// Routes for hints management
Route::middleware('auth')->name('hints.')->prefix('hints')->group(function(){
    Route::get('/', 'HintsController@index')->name('index');
    Route::get('edit/{id}', 'HintsController@edit')->name('edit');
    Route::post('edit/post', 'HintsController@updateHint')->name('edit.post');
});

// Routes for adverts
Route::middleware('auth')->name('adverts')->prefix('adverts')->group(function(){
    Route::post('recordClick/{id}','AdvertController@recordClick')->name('recordClick');
});

// Routes for rules
Route::middleware('auth')->name('rules')->prefix('rules')->group(function(){
    Route::get('/','CommunityRules@index')->name('index');
});
