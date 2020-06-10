<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;

use Swift_IoException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
// use App\Events\AppError;

class Handler extends ExceptionHandler {
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $exception
	 * @return void
	 */
	public function report(Exception $exception) {

		if ($exception instanceof Swift_IoException) {

			// event(new AppError($exception->getTraceAsString()));

		}

		parent::report($exception);

	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $exception
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $exception) {

		// \Log::log('info', $exception);
		// abort(404);
		// return redirect()->guest(route('login'));

		if ($exception instanceof MethodNotAllowedHttpException) {

			return redirect()->back()->withErrors(['Invalid Url ']);
		} elseif ($exception instanceof TokenMismatchException) {

			return redirect()->back()->withErrors(['Page have expired.']);

		} elseif ($exception instanceof Swift_IoException) {

			return redirect()->back()->withErrors(['Network Connection Error. Please Try again later. ']);
		}

		return parent::render($request, $exception);
	}

	/**
	 * Convert an authentication exception into an unauthenticated response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Auth\AuthenticationException  $exception
	 * @return \Illuminate\Http\Response
	 */
	protected function unauthenticated($request, AuthenticationException $exception) {
		//dd(Session::get('tenant'));

		if ($request      ->expectsJson()) {
			return response()->json(['error' => 'Unauthenticated.'], 401);
		}
		$guard = array_get($exception->guards(), 0);
		switch ($guard) {
			case 'admin':
				$login = 'admin.login';
				break;
			case 'operator':
				$login = 'operator.login';
				break;
			default:
				$login = 'login';
				break;
		}
		return redirect()->guest(route($login));
	}
}
