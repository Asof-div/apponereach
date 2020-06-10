<?php

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

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Credentials: true');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'Api', 'middleware' => 'cors'], function () {

		Route::group(['prefix' => 'call', 'namespace' => 'Call'], function () {
				Route::post('log', 'CallController@storeCallLog');
				// Route::get('logs', 'CallController@logs');
				Route::get('logs', 'CallController@getCallLogs')->middleware('auth:api');
			});

		Route::group(['prefix' => 'app', ], function () {

				Route::get('packages', 'PackageController@index');
				Route::get('account-categories', 'AccountCategoryController@index');
				Route::get('account-sources', 'AccountSourceController@index');
				Route::get('payment-terms', 'PaymentTermsController@index');
				Route::get('industries', 'IndustryController@index');
				Route::get('currencies', 'CurrencyController@index');
				Route::get('countries', 'CountryController@index');
				Route::get('states', 'StateController@index');
				Route::get('incidents', 'IncidentController@index');

			});

		Route::group(['prefix' => 'app', 'namespace' => 'Tenant'], function () {

				Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
						Route::post('login', 'AuthController@login');
						Route::get('get-domain/{domain}', 'AuthController@getDomain');
						Route::post('signup', 'AuthController@signup');

						Route::put('password/reset', 'AuthController@resetPassword');

						Route::group(['middleware' => 'auth:api'], function () {
								Route::put('password', 'AuthController@changePassword');
								Route::delete('logout', 'AuthController@logout');

								Route::post('check-in', 'AuthController@checkIn');

							});
					});

				Route::group(['middleware' => 'auth:api'], function () {

						Route::get('user', 'Auth\AuthController@user');

						Route::get('history', 'ProfileController@history');

						Route::put('update-profile', 'ProfileController@update');

						Route::put('change-password', 'ProfileController@changePassword');

						Route::post('picture', 'ProfileController@picture');

						Route::get('business/info', 'BusinessPageController@index');

						Route::post('business/logo', 'BusinessPageController@logo');

						Route::put('business/update', 'BusinessPageController@update');

						Route::get('trackers', 'TrackerController@index');
						Route::post('trackers/check-in', 'TrackerController@checkIn');

						Route::get('pilot/search', 'PilotNumberController@search');
						Route::get('pilot/reserve', 'PilotNumberController@addToCart');
						Route::get('pilot/reserved', 'PilotNumberController@getReserved');
						Route::get('pilot/remove', 'PilotNumberController@removeFromCart');

						Route::post('registration/save-order', 'BoardingController@saveOrder');

						Route::post('registration/confirm-order', 'BoardingController@confirmOrder');

						Route::get('registration/confirm-payment', 'BoardingController@handleGatewayCallback');

						Route::post('voucher/claim', 'VoucherController@claimVoucher');
						Route::get('voucher/info', 'VoucherController@getVoucherData');

						Route::group(['prefix' => 'crm', 'namespace' => 'CRM'], function () {

								Route::get('accounts', 'AccountController@index');
								Route::post('accounts', 'AccountController@store');
								Route::get('accounts/{id}', 'AccountController@show');
								Route::put('accounts/{id}', 'AccountController@update');
								Route::delete('accounts/{id}', 'AccountController@delete');

								Route::get('contacts', 'ContactController@index');
								Route::post('contacts', 'ContactController@store');
								Route::get('contacts/{id}', 'ContactController@show');
								Route::put('contacts/{id}', 'ContactController@update');
								Route::delete('contacts/{id}', 'ContactController@delete');

								Route::get('quotes', 'QuoteController@index');
								Route::post('quotes', 'QuoteController@store');
								Route::get('quotes/{id}', 'QuoteController@show');
								Route::put('quotes/{id}', 'QuoteController@update');
								Route::delete('quotes/{id}', 'QuoteController@delete');

							});

						Route::group(['prefix' => 'account', 'namespace' => 'Account'], function () {

								Route::get('users', 'UserController@index');
								Route::post('users', 'UserController@store');
								Route::get('users/{id}', 'UserController@show');
								Route::put('users/{id}', 'UserController@update');
								Route::delete('users/{id}', 'UserController@delete');

							});

						Route::group(['prefix' => 'billing', 'namespace' => 'Billing'], function () {

								Route::get('subscriptions', 'SubscriptionController@index');
								Route::post('subscriptions', 'SubscriptionController@store');
								Route::get('subscriptions/{id}', 'SubscriptionController@show');
								Route::get('subscription/current', 'SubscriptionController@currentSubscription');
								Route::get('subscription/current/processing', 'SubscriptionController@currentProcessingSubscription');

								Route::get('order/cart', 'OrderController@getCart');

								Route::post('order/add_to_cart', 'OrderController@addToCart');

								Route::post('order/remove_from_cart', 'OrderController@removeFromCart');

								Route::post('order/confirm-order', 'OrderController@confirmOrder');

								Route::get('order/verify_checkout', 'OrderController@verifyCheckout');

							});

						Route::group(['prefix' => 'media', 'namespace' => 'Media'], function () {

								Route::get('pilot-numbers', 'PilotNumberController@index');
								Route::put('pilot-numbers/default_route/{number}', 'PilotNumberController@defaultRoute');
								Route::put('pilot-numbers/enable-announcement/{number}', 'PilotNumberController@enableAnnouncement');
								Route::put('pilot-numbers/set-destination/{number}', 'PilotNumberController@setDestination');
								Route::get('pilot-numbers/{number}', 'PilotNumberController@show');

								Route::get('auto-attendants/{number}', 'AutoAttendantController@index');

								Route::get('auto-attendants/{number}/{id}', 'AutoAttendantController@show');

								Route::post('auto-attendants/{number}', 'AutoAttendantController@store');

								Route::put('auto-attendants/{number}/{id}', 'AutoAttendantController@update');

								Route::post('auto-attendants/reorder/{number}', 'AutoAttendantController@reorder');

								Route::delete('auto-attendants/delete/{id}', 'AutoAttendantController@delete');

								Route::get('calls', 'CallController@index');

								Route::get('dialplans', 'DialplanController@index');

								Route::get('extensions', 'ExtensionController@index');
								Route::post('extensions', 'ExtensionController@store');
								Route::get('extensions/{id}', 'ExtensionController@show');
								Route::put('extensions/{id}', 'ExtensionController@update');
								Route::delete('extensions/{number}', 'ExtensionController@delete');

								Route::get('numbers', 'NumberController@index');
								Route::post('numbers', 'NumberController@store');
								Route::put('numbers/{id}', 'NumberController@update');
								Route::put('numbers/status/{id}', 'NumberController@changeStatus');
								Route::put('numbers/remove/{id}', 'NumberController@remove');
								Route::delete('numbers/{id}', 'NumberController@delete');

								Route::get('groupcall', 'GroupRingController@index');
								Route::post('groupcall', 'GroupRingController@store');
								Route::get('groupcall/{id}', 'GroupRingController@show');
								Route::put('groupcall/{id}', 'GroupRingController@update');
								Route::delete('groupcall/{id}', 'GroupRingController@delete');

								Route::get('conference', 'ConferenceController@index');
								Route::post('conference', 'ConferenceController@store');
								Route::get('conference/{id}', 'ConferenceController@show');
								Route::put('conference/{id}', 'ConferenceController@update');
								Route::delete('conference/{id}', 'ConferenceController@delete');

								Route::get('ivr', 'VirtualReceptionistController@index');
								Route::post('ivr', 'VirtualReceptionistController@store');
								Route::get('ivr/{id}', 'VirtualReceptionistController@show');
								Route::put('ivr/{id}', 'VirtualReceptionistController@update');
								Route::delete('ivr/{id}', 'VirtualReceptionistController@delete');

								Route::get('sounds', 'SoundController@index');
								Route::post('sounds/upload', 'SoundController@upload');
								Route::post('sounds/call-to-record', 'SoundController@callToRecord');
								Route::delete('sounds/{id}', 'SoundController@delete');

								Route::get('images', 'GalleryController@index');
								Route::post('images', 'GalleryController@store');
								Route::delete('images/{id}', 'GalleryController@delete');

								Route::get('tts', 'TTSController@index');
								Route::post('tts', 'TTSController@store');
								Route::put('tts/{id}', 'TTSController@update');
								Route::delete('tts/{id}', 'TTSController@delete');

							});

						Route::group(['prefix' => 'conference', 'namespace' => 'Conference'], function () {

								Route::get('meetings', 'MeetingController@index');

								Route::post('meetings', 'MeetingController@store');

							});

					});

			});

		Route::fallback(function () {
				return response()->json(['message' => 'Not Found!'], 404);
			});
		Route::any('(:any)', function () {
				return response()->json(['message' => 'Not Found!'], 404);
			});
	});