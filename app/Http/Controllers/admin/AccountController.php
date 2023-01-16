<?php
/*****************************************************/
# Company Name      :
# Author            :
# Created Date      :
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
        * Created Date  :
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
        * Created Date  :
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
        * Created Date  :
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
            return back();
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return back();
        }
    }

    /*
        * Function Name : deleteUploadedImage
        * Purpose       : This function is for delete uploaded image
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : 
    */
    public function deleteUploadedImage(Request $request, $id = null) {
        $title      = trans('custom_admin.message_error');
        $message    = trans('custom_admin.error_something_went_wrong');
        $type       = 'error';

        try {
            if ($request->ajax()) {
                $primaryId  = $request->primaryId ? customEncryptionDecryption($request->primaryId, 'decrypt') : null;
                $dbField    = $request->dbField ? $request->dbField : '';

                if ($primaryId != null && $dbField != '') {
                    $details = $this->model->where('id', $primaryId)->first();
                    if ($details != '') {
                        $response = unlinkFiles($details->profile_pic, $this->pageRoute, true);
                        if ($response) {
                            $details->$dbField = null;
                            if ($details->save()) {
                                $title      = trans('custom_admin.message_success');
                                $message    = trans('custom_admin.message_image_deleted_successfully');
                                $type       = 'success';
                            } else {
                                $message    = trans('custom_admin.error_took_place_while_deleting');
                            }
                        } else {
                            $message    = trans('custom_admin.error_took_place_while_deleting');
                        }
                    } else {
                        $message = trans('custom_admin.error_invalid');
                    }                    
                } else {
                    $message = trans('custom_admin.error_invalid');
                }
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        } catch (\Throwable $e) {
            $message = $e->getMessage();
        }
        return response()->json(['title' => $title, 'message' => $message, 'type' => $type]);
    }

    /*
        * Function Name : changePassword
        * Purpose       : This function is for change password
        * Author        :
        * Created Date  :
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
            if ($request->isMethod('POST')) {
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
                    return redirect()->back()->withInput();
                } else {
                    $adminDetail    = Auth::guard('admin')->user();
                    $user_id        = Auth::guard('admin')->user()->id;
                    $hashedPassword = $adminDetail->password;

                    // check if current password matches with the saved password
                    if (Hash::check($request->current_password, $hashedPassword)) {
                        $adminDetail->password          = $request->password;
                        $adminDetail->sample_login_show = 'N';
                        $updatePassword                 = $adminDetail->save();
                        
                        if ($updatePassword) {
                            $this->generateNotifyMessage('success', trans('custom_admin.success_data_updated_successfully')." ".trans('custom_admin.success_for_security_reason_logged_out'), false);
                            Auth::guard('admin')->logout();
                            return redirect()->route($this->routePrefix.'.login');
                        } else {
                            $this->generateNotifyMessage('error', trans('custom_admin.error_took_place_while_updating'), false);
                            return redirect()->back();
                        }
                    } else {
                        $this->generateNotifyMessage('error', trans('custom_admin.error_current_password'), false);
                        return redirect()->back();
                    }
                }
            }
            return view($this->viewFolderPath.'.change_password', $data);
        } catch (Exception $e) {
            $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
            return redirect()->route($this->routePrefix.'.dashboard');
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return redirect()->route($this->routePrefix.'.dashboard');
        }
    }

    /*
        * Function name : websiteSettings
        * Purpose       : Website settings for whole website
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : Returns to the dashboard page
    */
    public function websiteSettings(Request $request) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_website_settings'),
            'panelTitle'    => trans('custom_admin.label_website_settings'),
            'pageType'      => 'LISTPAGE',
        ];

        try {
            $data['websiteSettings'] = $websiteSettings = $this->websiteSettingModel->first();

            if ($request->isMethod('POST')) {
                $validationCondition = array(
                    'from_email'    => 'required|regex:'.config('global.EMAIL_REGEX'),
                    'to_email'      => 'required|regex:'.config('global.EMAIL_REGEX'),
                    'website_title' => 'required',
                    'logo'          => 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE'),
                    'footer_logo'   => 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE'),
                );
                $validationMessages = array(
                    'from_email.required'       => trans('custom_admin.error_from_email'),
                    'from_email.regex'          => trans('custom_admin.error_valid_email'),
                    'to_email.required'         => trans('custom_admin.error_to_email'),
                    'to_email.regex'            => trans('custom_admin.error_valid_email'),
                    'website_title.required'    => trans('custom_admin.error_website_title'),
                    'logo.mimes'                => trans('custom_admin.error_image_mimes'),
                    'footer_logo.mimes'         => trans('custom_admin.error_image_mimes'),
                );
                $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($validator->fails()) {
                    $validationFailedMessages = validationMessageBeautifier($validator->messages()->getMessages());
                    $this->generateNotifyMessage('error', $validationFailedMessages, false);
                    return redirect()->back()->withInput();
                } else {
                    if ($websiteSettings == null) {
                        $saveData       = [];
                        $logo           = $request->file('logo');
                        $footerLogo     = $request->file('footer_logo');
                        $uploadedLogo   = $uploadedFooterLogo = '';
                        // Logo upload
                        if ($logo != '') {
                            $uploadedLogo               = singleImageUpload('WebsiteSetting', $logo, 'logo', $this->pageRoute, false);
                            $saveData['logo']           = $uploadedLogo;
                        }
                        // Footer logo upload
                        if ($footerLogo != '') {
                            $uploadedFooterLogo         = singleImageUpload('WebsiteSetting', $footerLogo, 'footer_logo', $this->pageRoute, false);
                            $saveData['footer_logo']    = $uploadedFooterLogo;
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
                        $saveData['map']                        = $request->map ?? null;
                        $saveData['website_title']              = $request->website_title ?? null;
                        $saveData['default_meta_title']         = $request->default_meta_title ?? null;
                        $saveData['default_meta_keywords']      = $request->default_meta_title ?? null;
                        $saveData['default_meta_description']   = $request->default_meta_description ?? null;
                        $saveData['address']                    = $request->address ?? null;
                        $saveData['footer_address']             = $request->footer_address ?? null;
                        $saveData['copyright_text']             = $request->copyright_text ?? null;
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
                        $footerLogo     = $request->file('footer_logo');
                        $uploadedLogo   = $uploadedFooterLogo = '';
                        $previousLogo   = $previousFooterLogo = null;
                        $unlinkLogoStatus= $unlinkFooterLogoStatus = false;
                        // Logo upload
                        if ($logo != '') {
                            if ($websiteSettings['logo'] != null) {
                                $previousLogo           = $websiteSettings['logo'];
                                $unlinkLogoStatus       = true;
                            }
                            $uploadedLogo               = singleImageUpload('WebsiteSetting', $logo, 'logo', $this->pageRoute, false, $previousLogo, $unlinkLogoStatus);
                            $updateData['logo']         = $uploadedLogo;
                        }
                        // Footer logo upload
                        if ($footerLogo != '') {
                            if ($websiteSettings['footer_logo'] != null) {
                                $previousFooterLogo     = $websiteSettings['footer_logo'];
                                $unlinkFooterLogoStatus = true;
                            }
                            $uploadedFooterLogo         = singleImageUpload('WebsiteSetting', $footerLogo, 'footer_logo', $this->pageRoute, false, $previousFooterLogo, $unlinkFooterLogoStatus);
                            $updateData['footer_logo']  = $uploadedFooterLogo;
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
                        $updateData['map']                      = $request->map ?? null;
                        $updateData['website_title']            = $request->website_title ?? null;
                        $updateData['default_meta_title']       = $request->default_meta_title ?? null;
                        $updateData['default_meta_keywords']    = $request->default_meta_title ?? null;
                        $updateData['default_meta_description'] = $request->default_meta_description ?? null;
                        $updateData['address']                  = $request->address ?? null;
                        $updateData['footer_address']           = $request->footer_address ?? null;
                        $updateData['copyright_text']           = $request->copyright_text ?? null;
                        $updateData['tag_line']                 = $request->tag_line ?? null;

                        foreach ($this->websiteLanguages as $langKey => $langVal) {
                            
                        }
                        $update = $websiteSettings->update($updateData);

                        if ($update) {
                            $this->generateNotifyMessage('success', trans('custom_admin.success_data_updated_successfully'), false);
                        } else {
                            $this->generateNotifyMessage('error', trans('custom_admin.error_took_place_while_updating'), false);
                        }
                    }
                    return redirect()->back();
                }
            }
            return view($this->viewFolderPath.'.website_settings', $data);
        } catch (Exception $e) {
            $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
            return redirect()->route($this->routePrefix.'.dashboard');
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return redirect()->route($this->routePrefix.'.website-settings');
        }
    }

    /*
        * Function Name : generateSlug
        * Purpose       : This function is to generate unique slug
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : Mixed
    */
    public function generateSlug(Request $request) {
        $title      = trans('custom_admin.message_error');
        $message    = trans('custom_admin.error_something_went_wrong');
        $type       = 'error';
        $slug       = '';

        try {
            if ($request->ajax()) {
                $modelName  = $request->modelName ? $request->modelName : '';
                $title      = $request->title ? trim($request->title) : '';
                $id         = $request->id ? $request->id : null;

                if ($modelName != '' && $title != '') {
                    if ($modelName == 'Category') {
                        $modelName = new Category();
                    } else if ($modelName == 'Cms') {
                        $modelName = new Cms();
                    }  
                    $slug = generateUniqueSlug($modelName, $title, $id);    // This is defined in helper
                    $type = 'success';
                    $message    = trans('custom_admin.message_success');
                } else {
                    $message    = trans('custom_admin.error_something_went_wrong');
                }
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        } catch (\Throwable $e) {
            $message = $e->getMessage();
        }
        return response()->json(['title' => $title, 'message' => $message, 'type' => $type, 'slug' => $slug]);
    }
    
}