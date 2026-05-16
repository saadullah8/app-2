<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
//CartController
Route::get('export/orders','ReportController@index');
Route::post('export/orders','ReportController@filterOrder');

Route::post('promos/{promoCode}','PromoController@isAlreadyExists');
Route::get('promos',  'PromoController@index')->name('promos.index');
Route::post('promos',  'PromoController@store')->name('promos.store');
Route::get('promos/{id}/edit',  'PromoController@edit');
Route::get('promos/{id}/delete',  'PromoController@delete');
Route::get('promos/{id}/{status}',  'PromoController@activate')->name('promos.activate');
Route::get('promos/{id}/{status}/lock',  'PromoController@lock')->name('promos.lock');
Route::post('promoscode/update',  'PromoController@update')->name('promos.update');
Route::post('customer/promo-code/{promocode}',  'CheckoutController@customerPromocode');

/*============================Marketing Module goes here========================== */
Route::get('marketing', 'MarketingController@index');
Route::post('marketing', 'MarketingController@marketing');

Route::get('create/meal/{id}','CartController@MenuMeal');

Route::get('logout','UserController@logout');

Route::get('/admin','HomeController@index');
Route::get('/dashboard','HomeController@index');

Route::post('profile/update','UserController@profileUpdate');
Route::get('/','IndexController@index');

Route::get('contact','IndexController@contactUs');
Route::get('about','IndexController@aboutUs');

Route::get('thankyou','IndexController@thankyou');
Route::get('/home/{title?}', 'IndexController@index')->name('home');

Route::resource('staff','StaffController');
// latest
Route::get('php_artisan_migrate','CommController@index');
Route::resource('ingredient','IngredientController');
Route::resource('main-course','MainCourseController');
Route::resource('main-course-list','MainCourseListController');
Route::get('main-course-list/add/{id}','MainCourseListController@addList');
//Route::resource('type','TypeController');
Route::resource('category','CategoryController');
Route::resource('product','ProductController');
Route::get('product/category/{id}','ProductController@index');
Route::post('product/add','ProductController@addProduct');
Route::post('product/add/main_course','ProductController@addProductMainCourse');
Route::post('product/remove/main_course','ProductController@removeMainCourse');
Route::post('product/edit/main_course','ProductController@EditMainCourse');
Route::post('ingredient/status/','IngredientController@activeStatus');
Route::post('product/status/','ProductController@activeStatus');
Route::get('change/password/','UserController@changePassword');
Route::post('change/password/','UserController@updatePassword');

Route::post('add_cart','CartController@addCartSession');
Route::post('product/add/cart','CartController@fixedProduct');
Route::post('add/product/cart','CartController@CustomizedProductAdd');
Route::get('/cookie/get','IndexController@getCookie');

Route::get('checkout','CheckoutController@redirectURL');
Route::post('checkout','CheckoutController@index');


Route::get('orders/pending','OrderController@pendingOrders');
Route::get('orders/ready_to_pickup','OrderController@ReadyOrders');
Route::get('orders/cancel','OrderController@cancelOrders');


Route::get('edit-order/{id}','OrderController@editOrder');
Route::post('admin/order-edit','OrderController@editSaveOrder');
Route::delete('admin/order/deleted','OrderController@deleteItemOrder');

Route::get('orderDetail/{id}','OrderController@orderDetails');

Route::post('order/status','OrderController@OrderStatus');

Route::get('order/printer/{id}','OrderController@orderPrinter');

Route::get('user/dashboard','CheckoutController@UserDashBoard');
Route::get('order/detail/{id}','CheckoutController@OrderDetails');
Route::get('cards/marked/{id}','CardController@setCardDefault');
Route::post('cards/delete','CardController@delete');

Route::get('edit/meal/{id}','CartController@editMeal');
Route::get('remove/meal/{id}','CartController@RemovedItemCart');
Route::post('edit/fixed','CartController@editFixed');
Route::get('clear/cart','IndexController@EmptyCart');
Route::post('edit/customized','CartController@editCustomized');
Route::get('order/print/{id}','OrderController@orderMealPrint');
Route::post('contact/user','IndexController@contactForm');


//Route::get('getFilePrint','PrinterController@PrintPage');

Route::get('starPrint','PrinterController@startPrint');
Route::get('windowPrint','PrinterController@newPrinterFile');
Route::get('testPrinter','PrinterController@PrintFile');
Route::get('formPrint/{id}','PrinterController@formPrinter');
Route::get('formPendingPrint/{id}','PrinterController@formPendingPrint');
//end latest

Route::get('email','EmailController@emailSend');

Route::get('/storeClosed','IndexController@StoreClosed');
Route::resource('storeTiming','StoreOpeningController');



Route::post('sendSms','OrderController@sendSMS');
Route::get('testing','SMSController@sendMessg');

Auth::routes();

Route::get('cart','CheckoutController@cartPage');

Route::post('reorder','CheckoutController@reOrder');

//strip
Route::get('payment','CardController@index');
Route::post('pay','CardController@store');
