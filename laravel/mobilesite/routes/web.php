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

// Route::get('/', function () {
//     return view('welcome');
// });
/*
//前台首页
Route::get('/', function () {
    return view('front/index');
});
//新闻列表
Route::get('news', function () {
    return view('front/news/index');
});
//活动列表
Route::get('events', function () {
    return view('front/events/index');
});
*/
//后台登录页面
// Route::get('admin/login', function ()    {
//         return view('back/login/index');
// });

//后台登录页面
Route::any('admin/login', 'Back\LoginController@index')->middleware('loginsession');
//后台登录动作
//Route::post('admin/dologin', 'Back\LoginController@dologin')->middleware('loginsession');

/*
//后台首页
Route::get('admin/index', function ()    {
        return view('Back/admins/index');
})->middleware('loginsession');
*/