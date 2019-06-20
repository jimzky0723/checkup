<?php
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

Route::auth();

Route::get('/', 'HomeCtrl@index');
Route::get('home', 'HomeCtrl@index');
Route::get('home/chart','HomeCtrl@chart');
Route::get('home/count/{type}','HomeCtrl@count');

Route::get('population', 'PopulationCtrl@index');
Route::post('population', 'PopulationCtrl@searchPopulation');
Route::get('population/profiles/{id}', 'PopulationCtrl@familyMember');

Route::get('population/less', 'PopulationCtrl@less');
Route::post('population/less', 'PopulationCtrl@searchLess');
Route::get('population/service/{id}', 'PopulationCtrl@servicePopulation');

//Service
Route::get('services','ServiceCtrl@index');
Route::post('services','ServiceCtrl@search');
Route::post('services/save','ServiceCtrl@save');
Route::get('service/info/{id}','ServiceCtrl@info');
Route::post('services/update','ServiceCtrl@update');
//end service

//Dengvaxia
Route::match(['get', 'post'],'dengvaxia/profile','DengvaxiaCtrl@index');
Route::match(['get', 'post'],'dengvaxia/link','DengvaxiaCtrl@link');
Route::get('dengvaxia/count/{id}','DengvaxiaCtrl@countProfile');
Route::get('dengvaxia/link/{id}/{offset}','DengvaxiaCtrl@linkProfile');
Route::get('dengvaxia/finish/{id}','DengvaxiaCtrl@finish');
//End Dengvaxia

//REPORT
Route::get('report/status','ReportCtrl@status');
Route::post('report/status','ReportCtrl@status');
Route::get('report/monthly','ReportCtrl@monthly');
Route::post('report/monthly','ReportCtrl@monthly');
Route::get('report/online','ReportCtrl@online');
//end REPORT
//PROGRAMS
Route::get('bracket', 'BracketCtrl@index');
Route::post('bracket','BracketCtrl@search');
Route::post('bracket/assign','BracketCtrl@assign');
Route::get('bracket/remove/{id}','BracketCtrl@remove');
//end programs
Route::get('change/password',function(){
    return view('app');
});
//end PROGRAMS

//DOWNLOAD
Route::get('download','DownloadCtrl@index');
Route::post('download','DownloadCtrl@index');
Route::get('download/{id}','DownloadCtrl@generateDownload');
Route::get('download/user/{id}','DownloadCtrl@generateUserDownload');

//END DOWNLOAD

//parameters
Route::get('age','ParameterCtrl@getAge');
Route::get('delete','ParameterCtrl@delete');
//end parameter

//users
Route::get('users','UserCtrl@index');
Route::post('users','UserCtrl@search');
Route::post('users/save','UserCtrl@save');
Route::post('users/update','UserCtrl@update');
Route::get('users/info/{id}','UserCtrl@info');
Route::get('users/assign/{id}','UserCtrl@assign');
//end users

//location
Route::get('location/muncity/{id}','LocationCtrl@getMuncityByProvince');
Route::get('location/barangay/{id}','LocationCtrl@getBarangayByMuncity');

//end location

//feedback
Route::get('feedback','FeedbackCtrl@index');
Route::get('feedback/view/{id}','FeedbackCtrl@view');
Route::post('feedback/status','FeedbackCtrl@status');
//end feedback

//client
Route::get('user/home','ClientCtrl@index');
Route::get('user/home/chart','ClientCtrl@chart');

//count
Route::get('user/home/count','ClientCtrl@count');

//end count
//population
Route::get('user/population','ClientCtrl@population');
Route::post('user/population','ClientCtrl@searchPopulation');

Route::post('user/profile/verify','ClientCtrl@verifyProfile');
Route::get('user/profile/age/{dob}','ClientCtrl@calculateAge');

Route::get('user/population/add/{id}','ClientCtrl@addPopulation');
Route::post('user/population/save','ClientCtrl@savePopulation');

Route::get('user/population/head','ClientCtrl@addHeadProfile');
Route::post('user/population/head/save','ClientCtrl@saveHeadProfile');

Route::get('user/population/info/{id}','ClientCtrl@infoPopulation');
Route::post('user/population/update','ClientCtrl@updatePopulation');

Route::get('user/population/less','ClientCtrl@populationLess');
Route::post('user/population/less','ClientCtrl@searchPopulationLess');

Route::get('user/population/member/{id}','ClientCtrl@memberPopulation');
Route::get('user/population/service/{id}/{year}','ClientCtrl@servicePopulation');
Route::get('user/population/less/service/{profileID}','ClientCtrl@servicePopulationLess');

Route::get('user/profiles','ClientCtrl@getFamilyProfiles');
Route::get('user/profiles/all','ClientCtrl@getProfiles');
//end population
//services
Route::post('user/services/date','ClientCtrl@updateDate');


Route::post('user/services','ClientCtrl@searchServices');
Route::get('user/services','ClientCtrl@services');
Route::get('user/services/{id}','ClientCtrl@services');
Route::post('user/services/save','ClientCtrl@saveServices');
Route::get('user/services/updategender/{gender}/{id}','ClientCtrl@updategender');
//end services
//reports
Route::get('user/report','ClientCtrl@report');
Route::post('user/report','ClientCtrl@searchReport');
Route::post('user/report/delete','ClientCtrl@deleteReport');
Route::get('user/report/cases','ClientCtrl@casesReport');
Route::post('user/report/cases','ClientCtrl@searchCase');
Route::post('user/report/cases/delete','ClientCtrl@deleteCase');

Route::get('user/report/monthly','ClientCtrl@monthlyReport');
Route::post('user/report/monthly','ClientCtrl@monthlyReport');

//request monthly report
Route::get('user/report/monthly/count/{code}/{month}/{year}', 'MonthlyReportCtrl@countService');

//graph report
Route::get('/user/report/health','ClientCtrl@health');
Route::get('/user/report/health/data','ClientCtrl@healthData');

Route::get('user/report/status','ClientCtrl@statusReport');
//upload report
Route::get('user/upload/temp',function(){
    return view('temp');
});

//Dengvaxia
Route::get('user/dengvaxia','client\DengvaxiaCtrl@index');
Route::get('user/dengvaxia/add/{id}','client\DengvaxiaCtrl@add');
Route::post('user/dengvaxia/save','client\DengvaxiaCtrl@save');

Route::get('user/dengvaxia/validate/date_given/{start}/{end}','client\DengvaxiaCtrl@validateDoseDateGiven');
//end Dengvaxia


//Downloading of Data of 1.4
//Route::get('user/download/data','Client\ReportCtrl@download');
//Route::get('user/download/data/countprofile/{year}','Client\ReportCtrl@countProfile');
//Route::get('user/download/data/{offset}','Client\ReportCtrl@downloadProfile');

////Downloading of Services
//Route::get('user/download/services/{year}/{offset}','Client\ReportCtrl@getServices');
//
////Downloading of Cases
//Route::get('user/download/cases/{year}/{offset}','Client\ReportCtrl@getCases');
//
////Downloading of Options
//Route::get('user/download/options/{year}/{offset}','Client\ReportCtrl@getOptions');


////Uploading of Data
//Route::get('user/upload/data','Client\ReportCtrl@upload');
//Route::post('user/upload/data','Client\ReportCtrl@uploadData');
//
////UPloading old data
//Route::get('user/upload/old/data','Client\ReportCtrl@uploadOld');
//Route::post('user/upload/old/data','Client\ReportCtrl@uploadDataOld');
//
////Downloading of Data of 1.2
//Route::get('user/download/old/data','Client\OldDataCtrl@download');
//Route::get('user/download/old/data/countprofile/{year}','Client\OldDataCtrl@countProfile');
//Route::get('user/download/old/data/{offset}','Client\OldDataCtrl@downloadProfile');

//Downloading of Services
Route::get('user/download/old/services/{year}/{offset}','Client\OldDataCtrl@getServices');

//Downloading of Cases
Route::get('user/download/old/cases/{year}/{offset}','Client\OldDataCtrl@getCases');

//Downloading of status
Route::get('user/download/old/status/{year}/{offset}','Client\OldDataCtrl@getStatus');

//Downloading of Options
Route::get('user/download/old/options/{year}/{offset}','Client\OldDataCtrl@getOptions');

//Download login Info
Route::get('user/download','ClientCtrl@downloadLogin');
//end reports
//users
Route::get('user/add','ClientCtrl@addUser');
Route::post('user/add','ClientCtrl@searchUser');
Route::post('user/save','ClientCtrl@saveUser');
Route::post('user/update','ClientCtrl@updateUser');
Route::get('user/info/{id}','ClientCtrl@infoUser');
//end users

//feedback
Route::post('feedback/send','ParameterCtrl@sendFeedback');
//end client

//LOGOUT
Route::get('logout',function(){
    Auth::logout();
    \Illuminate\Support\Facades\Session::flush();
    return redirect('login');
});

Route::get('user/change/password','ParameterCtrl@password');
Route::post('user/change/password','ParameterCtrl@changePassword');

Route::get('change/password','UserCtrl@password');
Route::post('change/password','UserCtrl@changePassword');



Route::get('accounts',function(){
    return view('system.account');
});
Route::get('negros',function(){
    return view('system.bohol');
});

Route::get('api','ApiCtrl@api');
Route::post('api/syncprofile','ApiCtrl@syncProfile');
Route::post('api/syncservices','ApiCtrl@syncServices');

//RUSEL
Route::get('verify_dengvaxia/{id}/{unique_id}','DengvaxiaController@verify_dengvaxia');
Route::get('form_dengvaxia/{id}/{unique_id}/{tsekap_id}','DengvaxiaController@form_dengvaxia');
Route::get('form_dengvaxia_add/{unique_id}/{tsekap_id}','DengvaxiaController@form_dengvaxia_add');
Route::post('post_dengvaxia/{id}/{unique_id}/{tsekap_id}','DengvaxiaController@post_dengvaxia');
Route::post('api/insertDengvaxia','ApiCtrl@insertDengvaxia');

Route::get('fpdf','DengvaxiaController@fpdf');
Route::get('patient_api/{id}','ApiCtrl@patient_api');

Route::get('sessionProcessPrint/{id}', 'DengvaxiaController@sessionProcessPrint');
Route::get('topNdp', 'TopController@index');
Route::get('crossMatching/{provinceId}/{muncityId}', 'DengvaxiaController@crossMatching');
Route::get('crossMatchingResult/{provinceId}/{muncityId}', 'TopController@crossMatchingResult');
