<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {

    Route::post('login', 'UserController@login');
    Route::post('register', 'UserController@register');
    Route::post('get_data_lapangan', 'LapanganController@get_data_lapangan');
    Route::get('get_data_jenis_user', 'JenisController@get_data_jenis');
    Route::get('get_data_jenis_detail', 'JenisController@get_detil_jenis');
    Route::get('get_foto_slider', 'FotoController@get_foto');
    Route::get('get_komentar', 'KomentarController@get_komentar');
    Route::post('get_data_optik', 'OptikController@get_data_optik');
    Route::post('get_detail_optik', 'OptikController@get_detail_optik');
    Route::post("send_email", "EmailController@sendEmailToUser");
    Route::group(['middleware' => 'auth:api'], function() {
 
     //pemilik
        Route::post('add_optik', 'OptikController@add_optik');
        Route::post('edit_optik', 'OptikController@edit_optik');
        Route::post('hapus_optik', 'OptikController@hapus_optik');
        Route::post('hapus_foto', 'FotoController@hapus_foto');
       
       // Route::post('get_data_jenis', 'LapanganController@get_data_lapangan_pemilik');
        Route::post('add_jenis', 'JenisController@add_jenis');
        Route::post('edit_jenis', 'JenisController@edit_jenis');
        Route::post('edit_foto', 'FotoController@edit_foto');
        Route::post('add_foto', 'OptikController@hapus_foto');
       
 
     //user
       Route::post('add_komentar', 'KomentarController@add_komentar');
       Route::post('add_raiting', 'RaitingController@add_raiting');
      
       Route::post('get_data_jenis', 'JenisController@get_data_jenis');
 
       Route::post("edit_no_hp",'UserController@edit_nohp');
       Route::get('logout', 'UserController@logout');
       Route::post('edit_pass','UserController@update_password');
       Route::post('edit_foto_profil','UserController@edit_foto');
       Route::post('get_data_user','UserController@get_data_user');
 
 
       
    
 
    });
 
 });