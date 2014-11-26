<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */
  
 

Route::get('/', 'EventsController@siteIndex');


Route::get('/events/', 'EventsController@siteIndex');
Route::get('/event/{slug}', 'EventsController@siteShow');
Route::get('/event/category/{id}', 'EventsController@siteIndex');
Route::any('seatmap/{id}', 'EventsController@seatmap');

//Coustomer app
Route::get('/appevents/', 'EventsController@appevents');
Route::get('/appevent/{id}', 'EventsController@appeventsdetail');


//Coustomer app User Details
Route::any('/appuser/{id}', 'UsersController@appuser');//Mobile User Details
Route::any('/applogin', 'UsersController@applogin');//Mobile User Details


Route::any('/mlogin', 'UsersController@mobileLogin');//Mobile App Login
Route::any('app/profile/modify', 'ProfileController@appEdit');//View profile edit for app
Route::get('app/account/wallet', 'UserAccountController@appIndex'); //EventController
Route::any('app/mytickets', 'UserTicketController@appTickets');   // users wishlist
Route::any('app/mytickets/{id}', 'UserTicketController@appShow');
Route::any('app/wishlist', 'WishlistController@appIndex'); 
Route::any('app/cart', 'CartController@appCart'); 
Route::any('app/checkout', 'PaymentController@appProcessUser');

Route::any('/mscan', 'UserTicketController@mobileScan');//Verify From Mobile

Route::any('mlogin/test', 'UsersController@mobilTest');//Verify From Mobile

Route::any('mreport', 'UserTicketController@mReport');//Verify From Mobile
Route::any('mscanreport', 'UserTicketController@mScanReport');//Verify From Mobile
Route::any('mscanhistory', 'UserTicketController@mScanHistory');//Verify From Mobile

//testing
//Session::forget('city_id');

if (!Session::has('city_id'))
                {
                        //$ip_address= $_SERVER['REMOTE_ADDR'];
                        //$geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip_address;
                        //$addrDetailsArr = unserialize(file_get_contents($geopluginURL)); 
                        if(isset($addrDetailsArr))
                        {
                                $city = isset($addrDetailsArr['geoplugin_city']) ? $addrDetailsArr['geoplugin_city'] : ''; 
                                $country = $addrDetailsArr['geoplugin_countryName'];
                                if(!$city){
                                   Session::put('city', 'Dubai');
                                   Session::put('city_id', 3);
                                }
                                else
                                {
                                        if($city == 'Abu Dhabi')
                                        {
                                                Session::put('city', 'Abu Dhabi');
                                                Session::put('city_id', 4);
                                        }
                                        elseif($city == 'Rio De Janeiro' || $city == 'Rio' || $city == 'RioDeJaneiro' || $country == 'Brazil')
                                        {
                                                Session::put('city', 'Rio De Janeiro');
                                                Session::put('city_id', 5);
                                        }
                                        else
                                        {
                                                Session::put('city', 'Dubai');
                                                Session::put('city_id', 3);
                                        }
                                }
                                if($country == 'Brazil' || $country == 'Brasil')
                                {
                                                Session::put('city', 'Rio De Janeiro');
                                                Session::put('city_id', 5);
                                }
                        }
                        else
                        {
                                   Session::put('city', 'Dubai');
                                   Session::put('city_id', 3);
                        }
                }
//User Sitefront
Route::get('logout', 'UsersController@logout');//Logout
Route::get('profile', 'ProfileController@index');//Forgot password 
Route::get('organizer/{id}', 'OrganizerController@organizer');//Forgot password



//No access after login 
Route::group(array('before' => 'auth.guest'), function() {
    Route::any('login', 'UsersController@index');//Login Form 
    Route::post('login/a/index', 'UsersController@ajaxLogin');//Login
	Route::any('facebook/login', 'UsersController@facebookLogin');//Login Form 
	Route::any('oauth2callback', 'UsersController@googleLogin');//Login Form 
    Route::any('signup', 'UsersController@signup');//Signup Form
    Route::any('signup/a/index', 'UsersController@ajaxSignup');//Signup Form
    Route::any('verify/{id}/{code}', 'UsersController@verify');//Verify Form
    Route::any('forgot', 'UsersController@forgot');//Forgot password 
    Route::any('resend', 'UsersController@resend');//Resend activation Form
});
Route::any('promocode/validate/{code}', 'PromoCodeController@validate');//Promocode
Route::any('promocode/remove/{code}', 'PromoCodeController@remove');//Promocode
Route::post('promo/event/open', 'PromoCodeController@specialPromo');//Shopping Cart

Route::get('cart', 'cartController@index');//Shopping Cart
Route::any('cart/add', 'cartController@add');//Shopping Cart
Route::any('removecart/{id}', 'cartController@removecart');//Shopping Cart
Route::post('cartupdate', 'cartController@cartUpdate');//Shopping Cart
Route::any('checkout', 'cartController@checkout');//Shopping Cart
Route::any('billing/summary', 'cartController@billingsummary');//Shopping Cart

Route::get('cart/barcode', 'cartController@barcode');//Shopping Cart
Route::get('cart/ticket', 'cartController@ticket');//Shopping Cart
Route::post('event/ticket/seat/update', 'cartController@updateTicketsTempTable');//Shopping Cart
Route::get('cart/clear', 'cartController@clearCart');//Shopping Cart


//events list search
Route::post('events','EventsController@siteIndex');
//static pages
Route::get('page/{slug}', 'PageController@siteShow');
// subcription site page
Route::post('subscription/add', 'SubscriptionController@add');
//change city from header
Route::any('city/change/{name}', 'CityController@changeCity');
// get event time
Route::any('event/geteventtime', 'EventsController@getEventTime');
Route::any('event/ticket/checkavailable', 'EventsController@checkAvaible');
// add to wishlist
Route::post('wishlist/add', 'WishlistController@add');   // add wishlist
Route::post('organizer/contact', 'OrganizerController@contactOrganiser');//Forgot password

Route::post('payment/info', 'PaymentController@processUser');//PaymentController
Route::post('payment/creditcard/info', 'PaymentController@creditcardinfo');//PaymentController
Route::post('payment/nbdcreditcard/info', 'PaymentController@nbdcreditcardinfo');//PaymentController
Route::any('processpayment', 'PaymentController@processpay');//PaymentController
Route::any('payment/success/{code}/{id}', 'PaymentController@paymentSuccess');//PaymentController
Route::any('payment/billinginfo', 'PaymentController@getBillingInfo');//PaymentController
Route::post('payment/a/billing', 'PaymentController@updateBillingInfo');//PaymentController
Route::get('payment/process/1/payfort/success', 'PaymentController@paymentProcess');//PaymentController
Route::get('payment/process/1/payfort/3dsecure/success', 'PaymentController@payfort3DSecureProcess');//PaymentController
Route::get('payment/process/0/payfort/failure', 'PaymentController@paymentProcess');//PaymentController
Route::get('payment/process/0/payfort/decline', 'PaymentController@decline');//PaymentController
Route::get('user/payment/process/1/payfort/success', 'UserAccountController@paymentProcess');//PaymentController
Route::get('user/payment/process/1/payfort/3dsecure/success', 'UserAccountController@payfort3DSecureProcess');//PaymentController


Route::get('payment/failure/{code}', 'PaymentController@paymentFailure');//PaymentController
Route::post('process/payment/card/validate/', 'PaymentController@ccvalidation');//PaymentController
Route::post('process/payment/card/installment/', 'PaymentController@installmentProcess');//PaymentController
Route::get('promotional/offer/enbd/{trans}/{code}', 'PaymentController@enbdSuccess');//PaymentController
Route::post('promotional/apply/{trans}/{code}', 'PaymentController@enbdApply');//PaymentController
Route::any('nbd/card/validate/', 'PromoCodeController@nbdcardValidation');//PaymentController

// paypal
Route::any('payment/process/1/paypal/notify/{code}', 'PaymentController@paypalProcess');//PaymentController
Route::any('payment/process/1/paypal/failure', 'PaymentController@paymentFailure');//PaymentController
Route::any('payment/process/1/paypal/success', 'PaymentController@paymentSuccess');//PaymentController

//cron
Route::get('payfort/process/cron/update', 'PaymentController@processPayfortOrder');   // merchant eventlist
Route::get('payfort/success', 'PaymentController@processPayfortSuccess');   // merchant eventlist

//Only ater login
Route::group(array('before' => 'auth.basic'), function() {
        Route::any('change', 'UsersController@change');//Change password
	Route::any('change/password', 'UserAccountController@change');//Change password
        Route::get('profile/modify', 'ProfileController@edit');//View profile edit
		
        Route::post('profile/modify', 'ProfileController@store');//Update profile data  
	Route::any('wishlist', 'WishlistController@siteIndex');   // users wishlist
	Route::any('wishlist/{id}', 'WishlistController@destroy');   // users wishlist
	
	Route::any('mytickets', 'UserTicketController@siteIndex');   // users wishlist
	Route::any('mytickets/{id}', 'UserTicketController@show');   // users wishlist
	Route::any('mytickets/pos/{id}', 'UserTicketController@printPos');   // users wishlist
	Route::any('mytickets/view/{id}', 'UserTicketController@listTickets');   // users wishlist
	Route::any('mytickets/print/{id}/{code}', 'UserTicketController@ticketShow');   // users wishlist
	Route::post('mytickets/exchange/{id}', 'UserTicketController@exchangeAmountAdd');   // users wishlist
	Route::get('account/wallet', 'UserAccountController@siteIndex'); //EventController
	Route::post('account/wallet', 'UserAccountController@store');//Update profile data
	
Route::get('tickets/event', 'UserTicketController@merchantIndex');   // merchant eventlist
Route::any('tickets/event/details/{slug}', 'UserTicketController@merchantEventDetails'); 
Route::get('tickets/event/statistics/{slug}', 'UserTicketController@merchantEventStatistics'); 	

Route::get('account/tickets/event', 'UserTicketController@accountIndex');   // merchant eventlist
Route::any('account/tickets/daily', 'UserTicketController@dailyReport');   // merchant eventlist

	
});


/******************************************Admin Router*****************************************/
Route::get('admin/',array('before' => 'auth.admin','uses' => 'DashboardController@index'));
Route::get('admin/login', 'UsersController@showLogin'); //Login Form
Route::post('admin/login','UsersController@adminLogin'); //Login Check
Route::get('admin/logout', 'UsersController@adminLogout');//Logout
Route::group(array('prefix' => 'admin/','before' => 'auth.admin'), function() {

    //Dashboard
    Route::resource('home', 'DashboardController');
    Route::resource('country', 'CountryController'); //CountryController
    Route::resource('language', 'LanguageController'); //LanguageController
    Route::resource('category', 'CategoryController'); //CategoryController
    Route::resource('currency', 'CurrencyController'); //CurrencyController
    Route::resource('city', 'CityController');   //CityController
    Route::resource('users', 'UsersController'); //UserController
    Route::any('userslist', 'UsersController@userslist'); //UsersController
    Route::any('user/userstatusupdate/{id}/{code}', 'UsersController@userstatusupdate');//Update Aprove & Disapprove    
	
    Route::resource('service', 'ServiceController'); //EventController
    Route::resource('usergroup', 'UserGroupController'); //EventController
    Route::resource('promocode', 'PromoCodeController'); //EventController
	Route::resource('promocodeuser', 'PromoCodeUserController'); //EventController
    Route::resource('page', 'PageController'); //EventController
    Route::resource('organizer', 'OrganizerController'); //EventController
    Route::any('organizer/approve/{id}/{code}', 'OrganizerController@approve');//Update Aprove & Disapprove             
    Route::resource('wishlist', 'WishlistController'); //EventController   
    Route::resource('paymentgateway', 'PaymentGatewayController'); //EventController   
    Route::resource('paymentgatewaysetting', 'PaymentGatewaySettingController'); //EventController    
    Route::resource('subscription', 'SubscriptionController'); //SubscriptionController  
    Route::resource('emailtemplate', 'EmailTemplateController'); //EventController  
    
    Route::resource('events', 'EventsController'); //EventController
    Route::resource('seatmap', 'SeatmapController'); //EventController
    
    Route::any('events/open/{id}/{code}', 'EventsController@open');//Update Aprove & Disapprove    
    Route::get('gallery', 'EventsController@gallery'); //EventController
    Route::resource('comment', 'EventCommentController'); //EventCommentController   
    Route::any('comment/approve/{id}/{code}', 'EventCommentController@approve');//Update Aprove & Disapprove
    Route::post('comment/destroyall', 'EventCommentController@destroyAll');//EVent comment delete all    
    
    
    Route::resource('bookedtickets', 'UserTicketController'); //EventController 
	Route::resource('bookedtickets/search', 'UserTicketController@searchBookedTickets'); //EventController 
    Route::resource('useraccount', 'UserAccountController'); //EventController
    Route::resource('transaction', 'TransactionController'); //EventController
    Route::get('tickets/list/{slug}', 'UserTicketController@adminTicketsView');
    Route::get('tickets/seat/delete/{ticket_id}', 'UserTicketController@deleteSeats');
	Route::get('tickets/seat/delete/temp/{ticket_id}', 'UserTicketController@deleteTempSeats');
	Route::any('tickets/seat/doblock/{event_id}/{event_date_id}', 'UserTicketController@doblock');
    Route::any('tickets/seat/block/', 'UserTicketController@blockSeats');
    Route::get('tickets/cancel/{id}', 'UserTicketController@cancelTicket');
	Route::any('ticket/print/{id}', 'UserTicketController@adminShow');   // users wishlist
	
	
	Route::any('events/eventdates/{id}', 'EventsController@eventDatesView');   // users wishlist
	Route::any('tickets/eventdates/{event_id}/{event_date_id}', 'UserTicketController@bookedEventDateTickets');   // users wishlist	
	Route::any('tickets/eventdates/temptickets', 'UserTicketController@tempBookedEventDateTickets');   // users wishlist	
	Route::any('tickets/print', 'UserTicketController@printTickets'); 
	
	Route::any('user/changepassword/{id}', 'UsersController@adminChange');//users password change form admin
	Route::get('tickets/event/statistics/{slug}', 'UserTicketController@adminMerchantEventStatistics');
	Route::any('tickets/eventdate/scan/{eventdate}', 'UserTicketController@scanEventDate');
    Route::any('tickets/eventdate/scannedtickets/{eventdate}', 'UserTicketController@scannedTicketEventDate'); 
});
Route::any('tickets/print', 'UserTicketController@printTicketsSite');
Route::any('ticket/printall/{email}/{ref}', 'UserTicketController@printAllTicket');   // print all ticket
Route::any('ticket/print/{email}/{ticket}', 'UserTicketController@printTicket');   // print ticket

Route::any('tickets/eventdate/scan/{eventdate}', 'UserTicketController@merchantScanEventDate');
Route::any('tickets/eventdate/scannedtickets/{eventdate}', 'UserTicketController@merchantScannedTicketEventDate');

/*Route::group(array('prefix' => 'organizer/','before' => 'auth.organizer'), function() {
    Route::resource('bookedtickets', 'UserTicketController'); //EventController 
	Route::get('tickets/list/{slug}', 'UserTicketController@adminTicketsView');
	Route::get('tickets/seat/block', 'UserTicketController@blockSeats');
});*/


