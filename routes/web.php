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



Route::get('dashboard', 'DashboardCtrl@index');
Route::group(['prefix' => 'api'], function(){
	$c = "RetrieveCtrl";
	Route::get('/facebook/{page_name}',$c.'@facebook');
	Route::get('/twitter/{username}',$c.'@twitter');
	Route::get('/instagram/{username}',$c.'@instagram');
	Route::get('/youtube/{channel_name}',$c.'@youtube');
	Route::get('/alexa/{domain}',$c.'@alexa');
});

Route::group(['prefix'=>'socmed'],function(){
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

Route::group(['prefix' => 'alexa'], function () {
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

});


Route::group(['prefix'=>'group'],function(){
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

