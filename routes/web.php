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

Route::get('login/auth', 'Auth\LoginController@redirectToProvider')->name('google_login');
Route::get('login/facebook', 'Auth\AuthController@redirectFacebook');
Route::get('login/facebook/callback','Auth\AuthController@handleProviderCallback');
Route::get('googlesignin', 'Auth\LoginController@handleProviderCallback');
Route::get('/',function(){
    return View('auth.login');
})->name('index')->middleware('guest');

Route::get('/logout',function(){
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::get('/toWa',function(){
    return redirect()->to('http://wa.kl-youniverse.com/googlesignin?state='.$_GET["state"].'&code='.$_GET["code"]);
});

Route::get('/logout/facebook',function(){
    session()->forget('fb_id');
    return redirect('/fbpage');
});

Route::group(['prefix' => 'dashboard'], function () {
    $c = "DashboardController";
    Route::get('/',$c.'@index')->name('dashboard');
    Route::post('/getPopularPost',$c.'@getPopularPost');
    Route::post('/getPopularCreator',$c.'@getPopularCreator');
    Route::post('getRank/{id}', $c.'@getRank');
    Route::post('getAlexaRank/{id}/{type}', $c.'@getAlexaRank');
    
});


Route::group(['prefix' => 'api'], function () {
    $c = "RetrieveController";
    Route::get('/twitter/{username}',$c.'@twitter');
    Route::get('/facebook/{page_name}',$c.'@facebook');
    Route::get('/youtube/{channel_name}',$c.'@youtube');
    Route::get('/instagram/{username}',$c.'@instagram');
    Route::get('/alexa/{domain}',$c.'@alexa');
    Route::post('/mail',$c.'@mailreport');
});

Route::group(['prefix' => 'alexa','middleware'=>'auth'], function () {
    $c = "AlexaMasterController";
    Route::get('/',$c.'@index')->name('alexa');
    Route::post('count_alexa', $c.'@count_alexa');
    Route::post('get_alexa', $c.'@get_alexa');
    Route::post('action',$c.'@action');
    Route::post('detail_alexa',$c.'@detail_alexa');
    Route::post('retrieveAlexaById', 'RetrieveController@retrieveAlexaById');
    Route::delete('delete/{id}',$c.'@delete');
    Route::get('report/{id}','AlexaReportController@index');
    Route::post('report/getFilterReport/{id}/{action}','AlexaReportController@getFilterReport');
    Route::post('report/getFilterReportAll/{action}','AlexaReportController@getFilterReportAll');

});

Route::group(['prefix' => 'fbpage','middleware'=>'auth'], function () {
    $c = "FansPageController";
    Route::get('/',$c.'@index')->name('fbpage');
    Route::post('count_fanspage', $c.'@count_fanspage');
    Route::post('get_fanspage', $c.'@get_fanspage');
    Route::post('updateStatus',$c.'@updateStatus');

});

Route::group(['prefix' => 'detail_fbpage','middleware'=>'auth'], function () {
    $c = "FansPageController";
    Route::get('/{id}',$c.'@detail')->name('detail_fbpage');
    Route::post('count_post', $c.'@count_post');
    Route::post('get_post', $c.'@get_post');
    Route::post('reloadall', $c.'@reloadallpost');
});

Route::group(['prefix' => 'post','middleware'=>'auth'], function () {
    $c = "PostController";
    Route::get('/',$c.'@index')->name('post');
    Route::post('count_post', $c.'@count_post');
    Route::post('get_post', $c.'@get_post');
    Route::post('get_fanspage', $c.'@get_fanspage');
    Route::post('get_creator', $c.'@get_creator');
});

Route::group(['middleware'=>'auth','prefix'=>'socmed'],function(){
    $c = "SocmedMasterController";
    Route::get('/',$c.'@index')->name('socmed');
    Route::get('report/{id}','ReportController@index');
    Route::post('report/getFilterReport/{id}/{action}','ReportController@getFilterReport');
    Route::post('count_sosmed', $c.'@count_sosmed');
    Route::post('get_sosmed', $c.'@get_sosmed');
    Route::post('action',$c.'@action');
    Route::post('retrieveById', 'RetrieveController@retrieveById');
    Route::post('detail_socmed',$c.'@detail_socmed');
    Route::delete('delete/{id}',$c.'@delete');
});

Route::group(['middleware'=>'auth','prefix'=>'group'],function(){
    $c = "GroupMasterController";
    Route::get('/',$c.'@index')->name('group');
    Route::post('count_group', $c.'@count_group');
    Route::post('get_group', $c.'@get_group');
    Route::post('updateOrder',$c.'@updateOrder');
    Route::post('get_groupBySocmed', $c.'@get_groupBySocmed');
    Route::post('get_groupByAlexa', $c.'@get_groupByAlexa');
    Route::post('detail_group',$c.'@detail_group');
    Route::post('action',$c.'@action');
    Route::delete('delete/{id}',$c.'@delete');
});

Route::group(['middleware'=>'auth','prefix'=>'user_allow'],function(){
    $c = "AllowedUserController";
    Route::get('/',$c.'@index')->name('allowed_user');
    Route::post('count_users', $c.'@count_users');
    Route::post('get_users', $c.'@get_users');
    Route::post('detail_users',$c.'@detail_users');
    Route::post('action',$c.'@action');
    Route::delete('delete/{id}',$c.'@delete');
});


Route::group(['middleware'=>'auth','prefix'=>'cron_email'],function(){
    $c = "CronEmailController";
    Route::get('/',$c.'@index')->name('cron_email');
    Route::post('count_cron', $c.'@count_cron');
    Route::post('get_cron', $c.'@get_cron');
    Route::post('detail_cron',$c.'@detail_cron');
    Route::post('action',$c.'@action');
    Route::delete('delete/{id}',$c.'@delete');
    Route::put('update', $c.'@update');
});

