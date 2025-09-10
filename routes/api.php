<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReviewRequestController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\AdminUserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

##------------------------------------- Auth module
Route::controller(AuthController::class)->group(function(){
    Route::post('register','register');
    Route::post('login','login');
    Route::post('logout','logout')->middleware('auth:sanctum');
});

##------------------------------------- Category module
Route::controller(ProductCategoryController::class)->group(function(){
    Route::get('categories','index');
    Route::get('category/{id}','show');
    Route::post('addcategory','store');
    Route::post('updatecategory/{id}','update');
    Route::post('deletecategory/{id}','destroy');
});

##-------------------------------------- Product module
Route::controller(ProductController::class)->group(function(){
    Route::get('products','index');
    Route::get('product/{id}','show');
    Route::post('addproduct','store');
    Route::post('updateproduct/{id}','update');
    Route::post('deleteproduct/{id}','destroy');
    Route::get('products/featured','featured');
    Route::get('products/filter','filter');
    Route::put('products/{id}/feature','markAsFeatured');
});

##-------------------------------------- Admin review request module
Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::controller(ReviewRequestController::class)->group(function () {
        Route::get('review-requests','index');
        Route::put('admin/review-requests/{id}/approve', 'approve'); 
        Route::put('admin/review-requests/{id}/reject', 'reject'); 
    });
});

##-------------------------------------- Add review request module
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/review-requests', [ReviewRequestController::class, 'store']);
        Route::get('/notifications', [UserNotificationController::class, 'index']);
});

##-------------------------------------- Favorite module
Route::middleware(['auth:sanctum'])->group(function () {
Route::controller(FavoritesController::class)->group(function(){
    Route::get('favorites','index');
    Route::get('favorite/{id}','show');
    Route::post('addfavorite','store');
    Route::post('deletefavorite/{id}','destroy');
});
});

##-------------------------------------- Order module
Route::controller(OrderController::class)->group(function(){
    Route::get('orders','index');
    Route::get('order/{id}','show');
    Route::post('addorder','store');
    Route::post('updateorder/{id}','update');
    Route::post('deleteorder/{id}','destroy');
});

##-------------------------------------- Notification to admin module
Route::middleware(['auth:sanctum','isAdmin'])->group(function () {
Route::controller(NotificationController::class)->group(function(){
    Route::get('admin/notifications','index'); 
    Route::get('admin/notifications/unread','unread');
    Route::put('admin/notifications/{id}/read','markAsRead');
    Route::put('admin/notifications/read-all','markAllAsRead');  
});
});

##-------------------------------------- User module
Route::middleware('auth:sanctum')->get('user/current',[AdminUserController::class,'current']);
Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::controller(AdminUserController::class)->group(function () {
        Route::get('users', 'index');               
        Route::get('user/{id}', 'show');          
        Route::post('adduser', 'store');             
        Route::put('updateuser/{id}', 'update');        
        Route::post('deleteuser/{id}', 'destroy');     
    });
});

