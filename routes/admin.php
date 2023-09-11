<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\VideoController;

Route::redirect('/admin', 'admin/login');
Route::group(['prefix' => 'admin'], function () {
Route::match(['get','post'],'login', [HomeController::class,'index'])->name('admin.login');

    Route::name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('dashboard', [HomeController::class,'dashboard'])->name('dashboard');
    Route::any('admin-profile', [HomeController::class,'adminProfile'])->name('profile');
    Route::post('admin-change-password', [HomeController::class,'adminChangePassword'])->name('changePassword');
    Route::post('logout', [HomeController::class,'logout'])->name('logout');

    // user module
    Route::get('users', [UserController::class,'userlist'])->name('users');
    Route::any('create-user', [UserController::class,'createUser'])->name('createUser');
    Route::any('edit-user/{user}', [UserController::class,'editUser'])->name('editUser');
    Route::delete('delete-user/{user}', [UserController::class,'deleteUser'])->name('userDelete');
    Route::post('change-user-status/{user}', [UserController::class,'changeUserStatus'])->name('changeUserStatus');
    
    // faq module
    Route::resource('faq', FaqController::class);  
    Route::post('faq/change-status/{faq}', [FaqController::class,'changeFaqStatus'])->name('faq.changeFaqStatus');

    // cms module
    Route::resource('cms', CmsController::class)->except(['update', 'show']);
    Route::post('cms/{slug}', [CmsController::class,'update']);  

    // category module
    Route::get('categories', [CategoryController::class,'index'])->name('category');  
    Route::match(['get','post'],'categories/create', [CategoryController::class,'create'])->name('createCategory');  
    Route::match(['get','post'],'categories/edit/{category}', [CategoryController::class,'update'])->name('updateCategory');  
    Route::post('categories/change-status/{category}', [CategoryController::class,'changeStatus'])->name('changeStatusCategory');  
    Route::delete('categories/delete/{category}', [CategoryController::class,'delete'])->name('deleteCategory');  
    
    
    //role  module
    Route::get('roles', [RolePermissionController::class,'index'])->name('role');  


     //video  module
     Route::get('videos', [VideoController::class,'index'])->name('video');  


    // setting module
    Route::any('setting', [SiteSettingController::class,'setting'])->name('setting'); 


});


});



