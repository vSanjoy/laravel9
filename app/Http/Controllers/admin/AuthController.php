<?php
/*****************************************************/
# Company Name      :
# Author            :
# Created Date      : 12/01/2023
# Page/Class name   : AuthController
# Purpose           : Admin Login, Logout Management
/*****************************************************/

namespace App\Http\Controllers\admin;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\GeneralMethods;
use Auth;
use Redirect;
use Validator;
use View;
use Session;
use App\Models\User;

class AuthController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'Account';
    public $management;
    public $modelName       = 'User';
    public $breadcrumb;
    public $routePrefix     = 'admin';
    public $pageRoute       = 'account';
    public $listUrl         = '';
    public $addUrl          = '';
    public $editUrl         = '';
    public $viewFolderPath  = 'admin.auth';
    public $model           = 'User';
    public $as              = 'auth';

    /*
        * Function Name : __construct
        * Purpose       : It sets some public variables for being accessed throughout this
        *                   controller and its related view pages
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : Void
        * Return Value  : Mixed
    */
    public function __construct($data = null) {
        parent::__construct();

        $this->management   = 'Login';
        $this->model        = new User();

        // Variables assign for view page
        $this->assignShareVariables();        
    }

    /**
        * Function name : login
        * Purpose       : Login to dashboard
        * Author        : 
        * Created Date  : 12/01/2023
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : @return \Illuminate\Http\Response
    */
    public function login(Request $request) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_login'),
            'panelTitle'    => trans('custom_admin.label_login'),
            'pageType'      => ''
        ];

        if (Auth::guard('admin')->check()) {
        	return redirect()->route($this->routePrefix.'.account.dashboard');
        } else {
            try {
                if ($request->isMethod('PATCH')) {
                    $validationCondition = array(
                        'email'     => 'required|regex:'.config('global.EMAIL_REGEX'),
                        'password'  => 'required',
                    );
                    $validationMessages = array(
                        'email.required'    => trans('custom_admin.error_enter_email'),
                        'email.regex'       => trans('custom_admin.error_enter_valid_email'),
                        'password.required' => trans('custom_admin.error_enter_password'),
                    );
                    $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                    if ($validator->fails()) {
                        $validationFailedMessages = validationMessageBeautifier($validator->messages()->getMessages());                        
                        $this->generateNotifyMessage('error', $validationFailedMessages, false);

                        return to_route($this->routePrefix.'.'.$this->as.'.login')->withInput();
                    } else {
                        $rememberMe = (!empty($request->remember_me)) ? true : false;
                        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'type' => 'SA', 'status' => '1'], $rememberMe)) {
                            if (Auth::guard('admin')->user()->checkRolePermission == null) {
                                Auth::guard('admin')->logout();

                                $this->generateNotifyMessage('error', trans('custom_admin.error_permission_denied'), false);
                                return to_route($this->routePrefix.'.'.$this->as.'.login')->withInput();
                            } else {
                                $user  = \Auth::guard('admin')->user();
                                $user->lastlogintime = strtotime(date('Y-m-d H:i:s'));
                                $user->save();
                                
                                return to_route($this->routePrefix.'.account.dashboard');
                            }                            
                        } else if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'type' => 'A', 'status' => '1'])) {
                            $user  = \Auth::guard('admin')->user();
                            $user->lastlogintime = strtotime(date('Y-m-d H:i:s'));
                            $user->save();
                            
                            return to_route($this->routePrefix.'.account.dashboard');
                        } else {
                            $this->generateNotifyMessage('error', trans('custom_admin.error_invalid_credentials_inactive_user'), false);
                            return to_route($this->routePrefix.'.'.$this->as.'.login')->withInput();
                        }
                    }
                }
                $data['superAdminDetails'] = User::where(['id' => '1', 'type' => 'SA', 'sample_login_show' => 'Y'])->first();

                // If admin is not logged in, show the login form //
                return view($this->viewFolderPath.'.login', $data);
            } catch (Exception $e) {
                $this->generateNotifyMessage('error', trans('custom_admin.error_invalid_credentials'), false);
                return to_route($this->routePrefix.'.'.$this->as.'.login');
            } catch (\Throwable $e) {
                $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
                return to_route($this->routePrefix.'.'.$this->as.'.login');
            }
        }
    }

    /*
        * Function name : forgotPassword
        * Purpose       : Forgot password
        * Author        :
        * Created Date  : 12/01/2023
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : @return \Illuminate\Http\Response 
    */
    public function forgotPassword(Request $request) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_forgot_password'),
            'panelTitle'    => trans('custom_admin.label_forgot_password'),
            'pageType'      => ''
        ];

        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        } else {
            try {
                if ($request->isMethod('PATCH')) {
                    $validationCondition = array(
                        'email'     => 'required|regex:'.config('global.EMAIL_REGEX'),
                    );
                    $validationMessages = array(
                        'email.required'    => trans('custom_admin.error_enter_email'),
                        'email.regex'       => trans('custom_admin.error_enter_valid_email'),
                    );
                    $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                    if ($validator->fails()) {
                        $validationFailedMessages = validationMessageBeautifier($validator->messages()->getMessages());
                        $this->generateNotifyMessage('error', $validationFailedMessages, false);
                        return to_route($this->routePrefix.'.'.$this->as.'.forgot-password')->withInput();
                    } else {
                        $user = $this->model->where(['email' => $request->email, 'status' => '1'])->first();
                        if ($user != null) {
                            if ($user->type == 'SA' || $user->type == 'AG') {
                                $encryptedString = customEncryptionDecryption($user->id.'~'.$user->email);
                                $user->auth_token = $encryptedString;
                                if ($user->save()) {
                                    $siteSetting = getSiteSettings();                                    
                                    // Mail for reset password link
                                    \Mail::send('emails.admin.reset_password_link_to_admin',
                                    [
                                        'user'              => $user,
                                        'encryptedString'   => $encryptedString,
                                        'siteSetting'       => $siteSetting,
                                    ], function ($m) use ($user, $siteSetting) {
                                        $m->from($siteSetting->from_email, $siteSetting->website_title);
                                        $m->to($user->email, $user->full_name)->subject(trans('custom_admin.label_reset_password_link').' - '.$siteSetting->website_title);
                                    });
                                    $this->generateNotifyMessage('success', trans('custom_admin.message_reset_password_text'), false);
                                    return to_route($this->routePrefix.'.'.$this->as.'.forgot-password');
                                }
                            } else {
                                $this->generateNotifyMessage('error', trans('custom_admin.error_sufficient_permission'), false);
                                return to_route($this->routePrefix.'.'.$this->as.'.forgot-password')->withInput();
                            }
                        } else {
                            $this->generateNotifyMessage('error', trans('custom_admin.error_not_registered_with_us'), false);
                            return to_route($this->routePrefix.'.'.$this->as.'.forgot-password')->withInput();
                        }
                    }
                }
                return view($this->viewFolderPath.'.forgot_password', $data);
            } catch (Exception $e) {
                $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
                return to_route($this->routePrefix.'.'.$this->as.'.forgot-password')->withInput();
            } catch (\Throwable $e) {
                $this->generateNotifyMessage('error', $e->getMessage(), false);
                return to_route($this->routePrefix.'.'.$this->as.'.forgot-password')->withInput();
            }
        }
    }

    /*
        * Function name : unauthorizedAccess
        * Purpose       : Unauthorized access
        * Author        :
        * Created Date  : 13/01/2023
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : 
    */
    public function unauthorizedAccess(Request $request) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_unauthorized_access'),
            'panelTitle'    => trans('custom_admin.label_unauthorized_access'),
            'pageType'      => ''
        ];
        
        Auth::guard('admin')->logout();

        try {
            $data['title']          = trans('custom_admin.label_401');
            $data['description']    = trans('custom_admin.error_401');
            return view($this->viewFolderPath.'.401', $data);
        } catch (Exception $e) {
            $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
            return to_route($this->routePrefix.'.'.$this->as.'.login');
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return to_route($this->routePrefix.'.'.$this->as.'.login');
        }
    }

    /*
        * Function name : logout
        * Purpose       : Logout from dashboard
        * Author        :
        * Created Date  : 16/01/2023
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : 
    */
    public function logout() {
        try {
            if (Auth::guard('admin')->logout()) {
                return to_route($this->routePrefix.'.'.$this->as.'.login');
            } else {
                return to_route($this->routePrefix.'.account.dashboard');
            }
        } catch (Exception $e) {
            $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
            return to_route($this->routePrefix.'.account.dashboard');
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return to_route($this->routePrefix.'.account.dashboard');
        }
    }
    
}
