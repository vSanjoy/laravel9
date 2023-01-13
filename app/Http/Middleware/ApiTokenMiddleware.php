<?php
/*****************************************************/
# Page/Class name   : ApiTokenMiddleware
# Purpose           : Restriction for users
/*****************************************************/
namespace App\Http\Middleware;
use App\Models\User;
use Closure;
use App;
use Hash;
use Response;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $functionName = getFunctionNameFromRequestUrl();
        $headers = $request->header();
        $data = [];

        // Token section
        if ( array_key_exists('x-access-token', $headers) ) {
            $headerToken  = $headers['x-access-token'][0];
            // Checking generated-token matched with request token (Before sign in)
            if (Hash::check(env('APP_KEY'), $headerToken)) {
                return $next($request);
            }
            // Checking stored database auth_token matched with request token (After sign in)
            else {
                $existToken = User::where(['auth_token' => $headerToken])->count();
                if ($existToken == 0) {
                    if ($functionName == 'sign_up' || $functionName == 'sign_in') {
                        return Response::json(generateResponseBodyForSignInSignUp('MIST-0003#'.$functionName, trans('custom_api.error_access_token_mismatched'), false, 300));
                    } else {
                        return Response::json(generateResponseBody('MIST-0003#'.$functionName, $data, trans('custom_api.error_access_token_mismatched'), false, 300));
                    }
                } else {
                    return $next($request);
                }
            }
        } else {
            if ($functionName == 'sign_up' || $functionName == 'sign_in') {
                return Response::json(generateResponseBody('MIST-0003#'.$functionName, $data, trans('custom_api.error_access_token_mismatched'), false, 300));
            } else {
                return Response::json(generateResponseBody('MIST-0001#'.$functionName, $data, trans('custom_api.error_access_token_not_provided'), false, 100));
            }
        }
    }
}
