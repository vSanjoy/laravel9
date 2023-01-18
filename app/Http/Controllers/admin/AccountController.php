<?php
/*****************************************************/
# Company Name      :
# Author            :
# Created Date      : 16/01/2023
# Page/Class name   : AccountController
# Purpose           : Admin Account Management
/*****************************************************/

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use Hash;
use Redirect;
use DB;
use Carbon\Carbon;
use App\Traits\GeneralMethods;
use App\Models\WebsiteSetting;
use App\Models\User;
use App\Models\Cms;
use App\Models\Category;
use App\Models\EventCategory;
use App\Models\Event;
use App\Models\Contact;

class AccountController extends Controller
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
    public $viewFolderPath  = 'admin.account';
    public $model           = 'User';
    public $as              = 'auth';

    /*
        * Function Name : __construct
        * Purpose       : It sets some public variables for being accessed throughout this
        *                   controller and its related view pages
        * Author        :
        * Created Date  : 16/01/2023
        * Modified date :
        * Input Params  : Void
        * Return Value  : Mixed
    */
    public function __construct($data = null) {
        parent::__construct();

        $this->management           = 'Account';
        $this->model                = new User();
        $this->websiteSettingModel  = new WebsiteSetting();

        // Assign breadcrumb
        $this->assignBreadcrumb();

        // Variables assign for view page
        $this->assignShareVariables();
    }    

    /*
        * Function name : dashboard
        * Purpose       : After login admin will see dashboard page
        * Author        :
        * Created Date  : 16/01/2023
        * Modified date :
        * Input Params  : 
        * Return Value  : Returns to the dashboard page
    */
    public function dashboard() {
        $data = [
            'pageTitle'     => trans('custom_admin.label_dashboard'),
            'panelTitle'    => trans('custom_admin.label_dashboard'),
            'pageType'      => 'LISTPAGE'
        ];

        try {
            $adminDetail        = Auth::guard('admin')->user();
            $data['adminDetail']= $adminDetail;
            
            return view($this->viewFolderPath.'.dashboard', $data);
        } catch (Exception $e) {
            Auth::guard('admin')->logout();
            $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
            
            return to_route($this->routePrefix.'.'.$this->as.'.auth.login');
        } catch (\Throwable $e) {
            Auth::guard('admin')->logout();
            $this->generateNotifyMessage('error', $e->getMessage(), false);

            return to_route($this->routePrefix.'.'.$this->as.'.auth.login');
        }
    }

    /*
        * Function Name : profile
        * Purpose       : This function is for update profile
        * Author        :
        * Created Date  : 16/01/2023
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : 
    */
    public function profile(Request $request) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_profile'),
            'panelTitle'    => trans('custom_admin.label_profile'),
            'pageType'      => 'LISTPAGE'
        ];

        try {
            $adminDetail        = Auth::guard('admin')->user();
            $data['adminDetail']= $adminDetail;
            
            if ($request->isMethod('PATCH')) {
                $validationCondition = array(
                    'first_name'    => 'required',
                    'last_name'     => 'required',
                    'email'         => 'required|regex:'.config('global.EMAIL_REGEX').'|unique:'.($this->model)->getTable().',email,'.Auth::guard('admin')->user()->id.',id,deleted_at,NULL',
                    'phone_no'      => 'required',
                    'profile_pic'   => 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE'),
                );
                $validationMessages = array(
                    'first_name.required'   => trans('custom_admin.error_first_name'),
                    'last_name.required'    => trans('custom_admin.error_last_name'),
                    'email.required'        => trans('custom_admin.error_email'),
                    'email.regex'           => trans('custom_admin.error_valid_email'),
                    'email.unique'          => trans('custom_admin.error_email_unique'),
                    'phone_no.required'     => trans('custom_admin.error_enter_phone_no'),
                    'profile_pic.mimes'     => trans('custom_admin.error_image_mimes'),
                );
                $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($validator->fails()) {
                    $validationFailedMessages = validationMessageBeautifier($validator->messages()->getMessages());
                    $this->generateNotifyMessage('error', $validationFailedMessages, false);
                    return back()->withInput();
                } else {
                    $profilePic         = $request->file('profile_pic');
                    $uploadedImage      = $adminDetail->profile_pic ?? null;
                    $previousFileName   = $adminDetail->profile_pic ?? null;
                    $unlinkStatus       = false;
                    if ($profilePic != '') {
                        if ($adminDetail->profile_pic != null) {
                            $previousFileName   = $adminDetail->profile_pic;
                            $unlinkStatus       = true;
                        }
                        $uploadedImage  = singleImageUpload($this->modelName, $profilePic, 'account', $this->pageRoute, true, $previousFileName, $unlinkStatus);
                    }
                    $updateAdminData = array(
                        'first_name'    => $request->first_name,
                        'last_name'     => $request->last_name,
                        'full_name'     => $request->first_name." ".$request->last_name,
                        'email'         => $request->email,
                        'phone_no'      => $request->phone_no,
                        'profile_pic'   => $uploadedImage,
                    );
                    $saveAdminData = $this->model->where('id', $adminDetail->id)->update($updateAdminData);

                    if ($saveAdminData) {
                        $this->generateNotifyMessage('success', trans('custom_admin.success_data_updated_successfully'), false);
                        return back();
                    } else {
                        $this->generateNotifyMessage('error', trans('custom_admin.error_took_place_while_updating'), false);
                        return back()->withInput();
                    }
                }
            }
            return view($this->viewFolderPath.'.profile', $data);
        } catch (Exception $e) {
            $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
            return to_route($this->routePrefix.'.account.dashboard');
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return to_route($this->routePrefix.'.account.dashboard');
        }
    }

    /*
        * Function Name : changePassword
        * Purpose       : This function is for change password
        * Author        :
        * Created Date  : 17/01/2023
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : 
    */
    public function changePassword(Request $request) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_change_password'),
            'panelTitle'    => trans('custom_admin.label_change_password'),
            'pageType'      => 'LISTPAGE'
        ];

        try {
            if ($request->isMethod('PATCH')) {
                $validationCondition = array(
                    'current_password'  => 'required',
                    'password'          => 'required|regex:'.config('global.PASSWORD_REGEX'),
                    'confirm_password'  => 'required|regex:'.config('global.PASSWORD_REGEX').'|same:password',
                );
                $validationMessages = array(
                    'current_password.required' => trans('custom_admin.error_enter_current_password'),
                    'password.required'         => trans('custom_admin.error_enter_password'),
                    'password.regex'            => trans('custom_admin.error_enter_password_regex'),
                    'confirm_password.required' => trans('custom_admin.error_enter_confirm_password'),
                    'confirm_password.regex'    => trans('custom_admin.error_enter_password_regex'),
                    'confirm_password.same'     => trans('custom_admin.error_same_password'),
                );
                $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($validator->fails()) {
                    $validationFailedMessages = validationMessageBeautifier($validator->messages()->getMessages());
                    $this->generateNotifyMessage('error', $validationFailedMessages, false);
                    return back()->withInput();
                } else {
                    $adminDetail    = Auth::guard('admin')->user();
                    $hashedPassword = $adminDetail->password;

                    // check if current password matches with the saved password
                    if (Hash::check($request->current_password, $hashedPassword)) {
                        $adminDetail->password          = $request->password;
                        $adminDetail->sample_login_show = 'N';
                        $updatePassword                 = $adminDetail->save();
                        
                        if ($updatePassword) {
                            $this->generateNotifyMessage('success', trans('custom_admin.success_data_updated_successfully')." ".trans('custom_admin.success_for_security_reason_logged_out'), false);
                            Auth::guard('admin')->logout();
                            return to_route($this->routePrefix.'.auth.login');
                        } else {
                            $this->generateNotifyMessage('error', trans('custom_admin.error_took_place_while_updating'), false);
                            return back()->withInput();
                        }
                    } else {
                        $this->generateNotifyMessage('error', trans('custom_admin.error_current_password'), false);
                        return back()->withInput();
                    }
                }
            }
            return view($this->viewFolderPath.'.change_password', $data);
        } catch (Exception $e) {
            $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
            return to_route($this->routePrefix.'.account.dashboard');
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return to_route($this->routePrefix.'.account.dashboard');
        }
    }

    /*
        * Function name : settings
        * Purpose       : Settings for whole website / app
        * Author        :
        * Created Date  : 17/01/2023
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : Returns to the dashboard page
    */
    public function settings(Request $request) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_settings'),
            'panelTitle'    => trans('custom_admin.label_settings'),
            'pageType'      => 'LISTPAGE',
        ];

        try {
            $data['websiteSettings'] = $websiteSettings = $this->websiteSettingModel->first();

            if ($request->isMethod('PATCH')) {
                $validationCondition = array(
                    'from_email'    => 'required|regex:'.config('global.EMAIL_REGEX'),
                    'to_email'      => 'required|regex:'.config('global.EMAIL_REGEX'),
                    'website_title' => 'required',
                    'logo'          => 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE')
                );
                $validationMessages = array(
                    'from_email.required'       => trans('custom_admin.error_from_email'),
                    'from_email.regex'          => trans('custom_admin.error_valid_email'),
                    'to_email.required'         => trans('custom_admin.error_to_email'),
                    'to_email.regex'            => trans('custom_admin.error_valid_email'),
                    'website_title.required'    => trans('custom_admin.error_website_title'),
                    'logo.mimes'                => trans('custom_admin.error_image_mimes'),
                );
                $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($validator->fails()) {
                    $validationFailedMessages = validationMessageBeautifier($validator->messages()->getMessages());
                    $this->generateNotifyMessage('error', $validationFailedMessages, false);
                    return back()->withInput();
                } else {
                    if ($websiteSettings == null) {
                        $saveData       = [];
                        $logo           = $request->file('logo');
                        $uploadedLogo   = '';
                        // Logo upload
                        if ($logo != '') {
                            $uploadedLogo       = singleImageUpload('WebsiteSetting', $logo, 'logo', $this->pageRoute, false);
                            $saveData['logo']   = $uploadedLogo;
                        }
                        $saveData['from_email']                 = $request->from_email ?? null;
                        $saveData['to_email']                   = $request->to_email ?? null;
                        $saveData['phone_no']                   = $request->phone_no ?? null;
                        $saveData['fax']                        = $request->fax ?? null;
                        $saveData['facebook_link']              = $request->facebook_link ?? null;
                        $saveData['twitter_link']               = $request->twitter_link ?? null;
                        $saveData['instagram_link']             = $request->instagram_link ?? null;
                        $saveData['linkedin_link']              = $request->linkedin_link ?? null;
                        $saveData['pinterest_link']             = $request->pinterest_link ?? null;
                        $saveData['googleplus_link']            = $request->googleplus_link ?? null;
                        $saveData['youtube_link']               = $request->youtube_link ?? null;
                        $saveData['rss_link']                   = $request->rss_link ?? null;
                        $saveData['dribble_link']               = $request->dribble_link ?? null;
                        $saveData['tumblr_link']                = $request->tumblr_link ?? null;
                        $saveData['website_title']              = $request->website_title ?? null;
                        $saveData['default_meta_title']         = $request->default_meta_title ?? null;
                        $saveData['default_meta_keywords']      = $request->default_meta_title ?? null;
                        $saveData['default_meta_description']   = $request->default_meta_description ?? null;
                        $saveData['address']                    = $request->address ?? null;
                        $saveData['tag_line']                   = $request->tag_line ?? null;
                        
                        $save = $this->websiteSettingModel->create($saveData);

                        if ($save) {
                            $this->generateNotifyMessage('success', trans('custom_admin.success_data_added_successfully'), false);
                        } else {
                            $this->generateNotifyMessage('error', trans('custom_admin.error_took_place_while_updating'), false);
                        }
                    } else {
                        $updateData     = [];
                        $logo           = $request->file('logo');
                        $uploadedLogo   = '';
                        $previousLogo   = null;
                        $unlinkLogoStatus= false;
                        // Logo upload
                        if ($logo != '') {
                            if ($websiteSettings['logo'] != null) {
                                $previousLogo           = $websiteSettings['logo'];
                                $unlinkLogoStatus       = true;
                            }
                            $uploadedLogo               = singleImageUpload('WebsiteSetting', $logo, 'logo', $this->pageRoute, false, $previousLogo, $unlinkLogoStatus);
                            $updateData['logo']         = $uploadedLogo;
                        }
                        $updateData['from_email']               = $request->from_email ?? null;
                        $updateData['to_email']                 = $request->to_email ?? null;
                        $updateData['phone_no']                 = $request->phone_no ?? null;
                        $updateData['fax']                      = $request->fax ?? null;
                        $updateData['facebook_link']            = $request->facebook_link ?? null;
                        $updateData['twitter_link']             = $request->twitter_link ?? null;
                        $updateData['instagram_link']           = $request->instagram_link ?? null;
                        $updateData['linkedin_link']            = $request->linkedin_link ?? null;
                        $updateData['pinterest_link']           = $request->pinterest_link ?? null;
                        $updateData['googleplus_link']          = $request->googleplus_link ?? null;
                        $updateData['youtube_link']             = $request->youtube_link ?? null;
                        $updateData['rss_link']                 = $request->rss_link ?? null;
                        $updateData['dribble_link']             = $request->dribble_link ?? null;
                        $updateData['tumblr_link']              = $request->tumblr_link ?? null;
                        $updateData['website_title']            = $request->website_title ?? null;
                        $updateData['default_meta_title']       = $request->default_meta_title ?? null;
                        $updateData['default_meta_keywords']    = $request->default_meta_title ?? null;
                        $updateData['default_meta_description'] = $request->default_meta_description ?? null;
                        $updateData['address']                  = $request->address ?? null;
                        $updateData['tag_line']                 = $request->tag_line ?? null;

                        $update = $websiteSettings->update($updateData);

                        if ($update) {
                            $this->generateNotifyMessage('success', trans('custom_admin.success_data_updated_successfully'), false);
                        } else {
                            $this->generateNotifyMessage('error', trans('custom_admin.error_took_place_while_updating'), false);
                        }
                    }
                    return to_route($this->routePrefix.'.account.settings');
                }
            }
            return view($this->viewFolderPath.'.settings', $data);
        } catch (Exception $e) {
            $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
            return to_route($this->routePrefix.'.account.dashboard');
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return to_route($this->routePrefix.'.account.dashboard');
        }
    }
    
}