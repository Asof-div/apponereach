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
Route::group(['namespace' => 'App', 'as' => 'pbx.'], function () {

		Route::get('/', ['as' => 'landing', 'uses' => 'LandingController@index']);

		Route::get('/pilot_number/search', ['as' => 'pilot_number.search', 'uses' => 'PilotNumberController@search']);

	});

Route::get('/domain_not_found', ['as' => 'domain_not_found', 'uses' => 'App\LandingController@domainNotFound']);

// Auth::routes();
Route::get('/mail', 'Auth\LoginController@mail');

// Route::get('/home', 'HomeController@index')->middleware('tenant')->name('home');

Route::get('register/{planr}', ['as' => 'register.customer', 'uses' => 'Auth\RegisterController@customerForm']);

/*
|
|	Here are route registration for Administrator e.g (Telvida)
|
 */

/*
|
|	Here are route registration for Operators e.g
|
 */

Route::group(['prefix' => 'operator', 'namespace' => 'App\Operator', 'as' => 'operator.'], function () {

		Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);

		Route::post('login', ['as' => 'login.do', 'uses' => 'Auth\LoginController@login']);

		Route::get('password/reset', ['as' => 'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);

		Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);

		Route::get('password/reset/{token}', ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm']);

		Route::post('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@reset']);

		Route::post('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

		Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

		Route::get('/', ['as' => 'index', 'uses' => 'WelcomeController@index']);

		Route::get('user', ['as' => 'user.index', 'uses' => 'UserController@index']);

		Route::get('user/create', ['as' => 'user.create', 'uses' => 'UserController@create']);

		Route::get('user/edit/{user}', ['as' => 'user.edit', 'uses' => 'UserController@edit']);

		Route::post('user/store', ['as' => 'user.store', 'uses' => 'UserController@store']);

		Route::post('user/update', ['as' => 'user.update', 'uses' => 'UserController@update']);

		Route::post('user/delete', ['as' => 'user.delete', 'uses' => 'UserController@delete']);

		Route::get('profile', ['as' => 'profile.index', 'uses' => 'ProfileController@index']);

		Route::post('profile/store', ['as' => 'profile.store', 'uses' => 'ProfileController@store']);

		Route::post('profile/picture', ['as' => 'profile.picture', 'uses' => 'ProfileController@picture']);

		Route::get('role', ['as' => 'role.index', 'uses' => 'RoleController@index']);

		Route::get('role/create', ['as' => 'role.create', 'uses' => 'RoleController@create']);

		Route::post('role/store', ['as' => 'role.store', 'uses' => 'RoleController@store']);

		Route::get('role/show/{operator_role}', ['as' => 'role.show', 'uses' => 'RoleController@show']);

		Route::get('role/edit/{operator_role}', ['as' => 'role.edit', 'uses' => 'RoleController@edit']);

		Route::put('role/update/{operator_role}', ['as' => 'role.update', 'uses' => 'RoleController@update']);

		Route::delete('role/delete/{operator_role}', ['as' => 'role.delete', 'uses' => 'RoleController@delete']);

		Route::put('role/account/assign/{operator_role}', ['as' => 'role.account.assign', 'uses' => 'RoleController@assignToAccount']);

		Route::put('role/account/revoke/{operator_role}', ['as' => 'role.account.revoke', 'uses' => 'RoleController@revokeFromAccount']);

		Route::put('role/permission/assign/{operator_role}', ['as' => 'role.permission.assign', 'uses' => 'RoleController@assignPermission']);

		Route::put('role/permission/revoke/{operator_role}', ['as' => 'role.permission.revoke', 'uses' => 'RoleController@revokePermission']);

		Route::get('customer/postpaid/activation/{id}', ['as' => 'customer.postpaid.activation', 'uses' => 'CustomerController@activation']);

		Route::post('customer/postpaid/activate', ['as' => 'customer.postpaid.activate', 'uses' => 'CustomerController@activate']);

		Route::get('order', ['as' => 'customer.order.index', 'uses' => 'CustomerOrderController@index']);

		// Orders
		Route::post('customer/{id}/billing/order/store', ['as' => 'customer.billing.order.store', 'uses' => 'CustomerOrderController@store']);

		Route::post('customer/{id}/billing/order/confirm', ['as' => 'customer.billing.order.confirm', 'uses' => 'CustomerOrderController@confirm']);

		Route::post('customer/{id}/billing/order/cancel', ['as' => 'customer.billing.order.cancel', 'uses' => 'CustomerOrderController@cancel']);

		Route::get('customer/{id}/billing/order/details/{order_id}', ['as' => 'customer.billing.order.details', 'uses' => 'CustomerOrderController@show']);

		Route::get('customer/{tenant_id}/order/create', ['as' => 'customer.order.create', 'uses' => 'CustomerOrderController@create']);

		Route::get('customer', ['as' => 'customer.index', 'uses' => 'CustomerController@index']);

		Route::get('customer/create', ['as' => 'customer.create', 'uses' => 'CustomerController@create']);

		Route::get('customer/edit/{tenant_id}', ['as' => 'customer.edit', 'uses' => 'CustomerController@edit']);

		Route::get('customer/show/{tenant_id}', ['as' => 'customer.show', 'uses' => 'CustomerController@show']);

		Route::get('customer/registration/{tenant_id}', ['as' => 'customer.registration', 'uses' => 'CustomerController@registration']);

		Route::post('customer/store', ['as' => 'customer.store', 'uses' => 'CustomerController@store']);

		Route::post('customer/number/store', ['as' => 'customer.number.store', 'uses' => 'CustomerController@addNumber']);

		Route::post('customer/update', ['as' => 'customer.update', 'uses' => 'CustomerController@update']);

		Route::post('customer/suspend/{tenant_id}', ['as' => 'customer.suspend', 'uses' => 'CustomerController@suspend']);

		Route::post('customer/renew/{tenant_id}', ['as' => 'customer.renew', 'uses' => 'CustomerController@renew']);

		Route::post('customer/revive/{tenant_id}', ['as' => 'customer.revive', 'uses' => 'CustomerController@revive']);

		Route::post('customer/delete/{tenant_id}', ['as' => 'customer.delete', 'uses' => 'CustomerController@delete']);

		Route::get('subscription', ['as' => 'subscription.index', 'uses' => 'SubscriptionController@index']);

		Route::get('subscription/create', ['as' => 'subscription.create', 'uses' => 'SubscriptionController@create']);

		Route::get('subscription/show/{subscription_id}', ['as' => 'subscription.show', 'uses' => 'SubscriptionController@show']);

		Route::get('transaction', ['as' => 'customer.transaction.index', 'uses' => 'CustomerTransactionController@index']);

		Route::get('transaction/show/{id}', ['as' => 'customer.transaction.show', 'uses' => 'CustomerTransactionController@show']);

		Route::post('transaction/status', ['as' => 'customer.transaction.status', 'uses' => 'CustomerTransactionController@status']);

		Route::get('pilot_number', ['as' => 'pilot_number.index', 'uses' => 'PilotNumberController@index']);

		Route::get('pilot_number/create', ['as' => 'pilot_number.create', 'uses' => 'PilotNumberController@create']);

		Route::get('pilot_number/edit/{pilot_number}', ['as' => 'pilot_number.edit', 'uses' => 'PilotNumberController@edit']);

		Route::post('pilot_number.store', ['as' => 'pilot_number.store', 'uses' => 'PilotNumberController@store']);

		Route::post('pilot_number.update', ['as' => 'pilot_number.update', 'uses' => 'PilotNumberController@update']);

		Route::post('pilot_number/delete', ['as' => 'pilot_number.delete', 'uses' => 'PilotNumberController@delete']);

		Route::get('pilot_number/template', ['as' => 'pilot_number.template', 'uses' => 'PilotNumberController@template']);

		Route::get('pilot_number/export', ['as' => 'pilot_number.export', 'uses' => 'PilotNumberController@export']);

		Route::post('pilot_number/import', ['as' => 'pilot_number.import', 'uses' => 'PilotNumberController@import']);

		// Purchase Pilot Number Controller

		Route::get('pilot_number/search', ['as' => 'pilot_number.search', 'uses' => 'PilotNumberController@search']);

		Route::get('pilot_number/reserve', ['as' => 'pilot_number.reserve', 'uses' => 'PilotNumberController@addToCart']);

		Route::get('pilot_number/remove', ['as' => 'pilot_number.remove', 'uses' => 'PilotNumberController@removeFromCart']);

		// Call Related routes

		Route::group(['prefix' => 'call', 'as' => 'call.'], function () {

				Route::get('rate', ['as' => 'rate.index', 'uses' => 'CallRateController@index']);

				Route::get('rate/create', ['as' => 'rate.create', 'uses' => 'CallRateController@create']);

				Route::get('rate/edit/{rate}', ['as' => 'rate.edit', 'uses' => 'CallRateController@edit']);

				Route::post('rate.store', ['as' => 'rate.store', 'uses' => 'CallRateController@store']);

				Route::post('rate.update', ['as' => 'rate.update', 'uses' => 'CallRateController@update']);

				Route::post('rate/delete', ['as' => 'rate.delete', 'uses' => 'CallRateController@delete']);

				Route::get('rate/template', ['as' => 'rate.template', 'uses' => 'CallRateController@template']);

				Route::get('rate/export', ['as' => 'rate.export', 'uses' => 'CallRateController@export']);

				Route::post('rate/import', ['as' => 'rate.import', 'uses' => 'CallRateController@import']);

				Route::get('history', ['as' => 'history.index', 'uses' => 'CallHistoryController@index']);

				Route::get('history/export', ['as' => 'history.export', 'uses' => 'CallHistoryController@export']);

			});

		// Incident Managemet

		Route::get('incident', ['as' => 'incident.index', 'uses' => 'IncidentController@index']);

		Route::get('incident/create', ['as' => 'incident.create', 'uses' => 'IncidentController@create']);

		Route::post('incident/store', ['as' => 'incident.store', 'uses' => 'IncidentController@store']);

		Route::get('incident/show/{incident_id}', ['as' => 'incident.show', 'uses' => 'IncidentController@show']);

		Route::put('incident/update/{incident_id}', ['as' => 'incident.update', 'uses' => 'IncidentController@update']);

		Route::delete('incident/delete/{incident_id}', ['as' => 'incident.delete', 'uses' => 'IncidentController@delete']);

		Route::put('incident/operator/assign/{incident_id}', ['as' => 'incident.operator.assign', 'uses' => 'IncidentController@assignToOperator']);

		Route::put('incident/operator/revoke/{incident_id}', ['as' => 'incident.operator.revoke', 'uses' => 'IncidentController@revokeFromOperator']);

		Route::put('incident/admin/assign/{incident_id}', ['as' => 'incident.admin.assign', 'uses' => 'IncidentController@assignToAdmin']);

		Route::put('incident/admin/revoke/{incident_id}', ['as' => 'incident.admin.revoke', 'uses' => 'IncidentController@revokeFromAdmin']);

		Route::get('ticket', ['as' => 'ticket.index', 'uses' => 'TicketController@index']);

		Route::get('ticket/unassigned', ['as' => 'ticket.unassigned', 'uses' => 'TicketController@unassigned']);

		Route::get('ticket/create', ['as' => 'ticket.create', 'uses' => 'TicketController@create']);

		Route::post('ticket/store', ['as' => 'ticket.store', 'uses' => 'TicketController@store']);

		Route::get('ticket/show/{ticket_id}', ['as' => 'ticket.show', 'uses' => 'TicketController@show']);

		Route::put('ticket/update/{ticket_id}', ['as' => 'ticket.update', 'uses' => 'TicketController@update']);

		Route::put('ticket/assign', ['as' => 'ticket.assign', 'uses' => 'TicketController@assign']);

		Route::put('ticket/status', ['as' => 'ticket.status', 'uses' => 'TicketController@status']);

		Route::post('ticket/resource/{ticket_id}', ['as' => 'ticket.resource', 'uses' => 'TicketController@resource']);

		Route::post('ticket/comment/{ticket_id}', ['as' => 'ticket.comment', 'uses' => 'TicketController@comment']);

		Route::put('ticket/escalate/{ticket_id}', ['as' => 'ticket.escalate', 'uses' => 'TicketController@escalate']);

		Route::delete('ticket/delete/{ticket_id}', ['as' => 'ticket.delete', 'uses' => 'TicketController@delete']);

	});

/*
|
|	Here are route registration for Tenants e.g ( CloudPBX Users )
|
 */
