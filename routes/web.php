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
   
});

