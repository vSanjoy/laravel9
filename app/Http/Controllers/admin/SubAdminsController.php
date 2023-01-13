<?php
/*
    # Class name    : SubAdminsController
    # Purpose       : Sub admin Management
*/

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Traits\GeneralMethods;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use DataTables;

class SubAdminsController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'SubAdmins';
    public $management;
    public $modelName       = 'SubAdmin';
    public $breadcrumb;
    public $routePrefix     = 'admin';
    public $pageRoute       = 'subAdmin';
    public $listUrl         = 'subAdmin.list';
    public $listRequestUrl  = 'subAdmin.ajax-list-request';
    public $addUrl          = 'subAdmin.add';
    public $editUrl         = 'subAdmin.edit';
    public $statusUrl       = 'subAdmin.change-status';
    public $deleteUrl       = 'subAdmin.delete';
    public $sortUrl         = 'subAdmin.sort';
    public $slotUrl         = 'subAdmin.slot';
    public $viewFolderPath  = 'admin.subAdmin';
    public $model           = 'User';

    /*
        * Function Name : __construct
        * Purpose       : It sets some public variables for being accessed throughout this
        *                   controller and its related view pages
        * Input Params  : Void
        * Return Value  : Mixed
    */
    public function __construct() {
        parent::__construct();

        $this->management  = trans('custom_admin.label_menu_sub_admin');
        $this->model       = new User();

        // Assign breadcrumb
        $this->assignBreadcrumb();
        
        // Variables assign for view page
        $this->assignShareVariables();
    }

    /*
        * Function name : list
        * Purpose       : This function is for the listing and searching
        * Input Params  : Request $request
        * Return Value  : Returns to the list page
    */
    public function list(Request $request) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_sub_admin_list'),
            'panelTitle'    => trans('custom_admin.label_sub_admin_list'),
            'pageType'      => 'LISTPAGE'
        ];

        try {
            // Start :: Manage restriction
            $data['isAllow'] = false;
            $restrictions   = checkingAllowRouteToUser($this->pageRoute.'.');
            if ($restrictions['is_super_admin']) {
                $data['isAllow'] = true;
            }
            $data['allowedRoutes'] = $restrictions['allow_routes'];
            // End :: Manage restriction

            return view($this->viewFolderPath.'.list', $data);
        } catch (Exception $e) {
            $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
            return redirect()->route($this->routePrefix.'.dashboard');
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return redirect()->route($this->routePrefix.'.dashboard');
        }
    }

    /*
        * Function name : ajaxListRequest
        * Purpose       : This function is for the reutrn ajax data
        * Input Params  : Request $request
        * Return Value  : Returns sub admin data
    */
    public function ajaxListRequest(Request $request) {
        $data['pageTitle'] = trans('custom_admin.label_sub_admin_list');
        $data['panelTitle']= trans('custom_admin.label_sub_admin_list');

        try {
            if ($request->ajax()) {
                $data = $this->model->where('id','<>','1')->where(['type' => 'A'])->whereNull('deleted_at');

                // Start :: Manage restriction
                $isAllow = false;
                $restrictions   = checkingAllowRouteToUser($this->pageRoute.'.');
                if ($restrictions['is_super_admin']) {
                    $isAllow = true;
                }
                $allowedRoutes  = $restrictions['allow_routes'];
                // End :: Manage restriction

                return Datatables::of($data, $isAllow, $allowedRoutes)
                        ->addIndexColumn()
                        ->addColumn('image', function ($row) use ($isAllow, $allowedRoutes) {
                            $image = asset('images/'.config('global.NO_IMAGE'));
                            if ($row->profile_pic != null && file_exists(public_path('images/uploads/account/'.$row->profile_pic))) {
                                $image = asset('images/uploads/account/'.$row->profile_pic);
                                if (file_exists(public_path('images/uploads/account/thumbs/'.$row->profile_pic))) {
                                    $image = asset('images/uploads/account/thumbs/'.$row->profile_pic);
                                }
                            }
                            return $image;
                        })
                        ->addColumn('updated_at', function ($row) {
                            return changeDateFormat($row->updated_at);
                        })
                        ->addColumn('status', function ($row) use ($isAllow, $allowedRoutes) {
                            if ($isAllow || in_array($this->statusUrl, $allowedRoutes)) {
                                if ($row->status == '1') {
                                    $status = ' <a href="javascript:void(0)" data-microtip-position="top" role="" aria-label="'.trans('custom_admin.label_active').'" data-id="'.customEncryptionDecryption($row->id).'" data-action-type="inactive" class="custom_font status"><span class="badge badge-pill badge-success">'.trans('custom_admin.label_active').'</span></a>';
                                } else {
                                    $status = ' <a href="javascript:void(0)" data-microtip-position="top" role="" aria-label="'.trans('custom_admin.label_inactive').'" data-id="'.customEncryptionDecryption($row->id).'" data-action-type="active" class="custom_font status"><span class="badge badge-pill badge-danger">'.trans('custom_admin.label_inactive').'</span></a>';
                                }
                            } else {
                                if ($row->status == '1') {
                                    $status = ' <a data-microtip-position="top" role="" aria-label="'.trans('custom_admin.label_active').'" class="custom_font"><span class="badge badge-pill badge-success">'.trans('custom_admin.label_active').'</span></a>';
                                } else {
                                    $status = ' <a data-microtip-position="top" role="" aria-label="'.trans('custom_admin.label_active').'" class="custom_font"><span class="badge badge-pill badge-danger">'.trans('custom_admin.label_inactive').'</span></a>';
                                }
                            }
                            return $status;
                        })
                        ->addColumn('action', function ($row) use ($isAllow, $allowedRoutes) {
                            $btn = '';
                            if ($isAllow || in_array($this->editUrl, $allowedRoutes)) {
                                $editLink = route($this->routePrefix.'.'.$this->editUrl, customEncryptionDecryption($row->id));

                                $btn .= '<a href="'.$editLink.'" data-microtip-position="top" role="tooltip" class="btn btn-info btn-circle btn-circle-sm" aria-label="'.trans('custom_admin.label_edit').'"><i class="fa fa-edit"></i></a>';
                            }
                            if ($isAllow || in_array($this->deleteUrl, $allowedRoutes)) {
                                $btn .= ' <a href="javascript: void(0);" data-microtip-position="top" role="tooltip" class="btn btn-danger btn-circle btn-circle-sm delete" aria-label="'.trans('custom_admin.label_delete').'" data-action-type="delete" data-id="'.customEncryptionDecryption($row->id).'"><i class="fa fa-trash"></i></a>';
                            }                            
                            return $btn;
                        })
                        ->rawColumns(['status','action'])
                        ->make(true);
            }
            return view($this->viewFolderPath.'.list');
        } catch (Exception $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return '';
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return '';
        }
    }

    /*
        * Function name : add
        * Purpose       : This function is to add sub admin
        * Input Params  : Request $request
        * Return Value  : 
    */
    public function add(Request $request, $id = null) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_add_sub_admin'),
            'panelTitle'    => trans('custom_admin.label_add_sub_admin'),
            'pageType'      => 'CREATEPAGE'
        ];

        try {
            if ($request->isMethod('POST')) {
                $validationCondition = array(
                    'first_name'        => 'required',
                    'last_name'         => 'required',
                    'email'             => 'required|regex:'.config('global.EMAIL_REGEX').'|unique:'.($this->model)->getTable().',email,NULL,id,deleted_at,NULL',
                    'phone_no'          => 'required',
                    'password'          => 'required|regex:'.config('global.PASSWORD_REGEX'),
                    'confirm_password'  => 'required|regex:'.config('global.PASSWORD_REGEX').'|same:password',
                    'profile_pic'       => 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE'),
                );
                $validationMessages = array(
                    'first_name.required'       => trans('custom_admin.error_enter_first_name'),
                    'last_name.required'        => trans('custom_admin.error_enter_last_name'),
                    'email.required'            => trans('custom_admin.error_email'),
                    'email.regex'               => trans('custom_admin.error_valid_email'),
                    'email.unique'              => trans('custom_admin.error_email_unique'),
                    'phone_no.required'         => trans('custom_admin.error_enter_phone_no'),
                    'password.required'         => trans('custom_admin.error_enter_password'),
                    'password.regex'            => trans('custom_admin.error_enter_password_regex'),
                    'confirm_password.required' => trans('custom_admin.error_enter_confirm_password'),
                    'confirm_password.regex'    => trans('custom_admin.error_enter_password_regex'),
                    'confirm_password.same'     => trans('custom_admin.error_same_password'),
                    'profile_pic.mimes'         => trans('custom_admin.error_image_mimes'),
                );
                $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($validator->fails()) {
                    $validationFailedMessages = validationMessageBeautifier($validator->messages()->getMessages());
                    $this->generateNotifyMessage('error', $validationFailedMessages, false);
                    return redirect()->back()->withInput();
                } else {
                    $details            = $this->model;
                    $randomString       = $request->password;
                    $password           = $randomString;
                    $profilePic         = $request->file('profile_pic');
                    $croppedImage       = $request->cropped_image;
                    $uploadedImage      = '';                    
                    if ($profilePic != '' && $croppedImage) {
                        $uploadedImage  = singleImageUploadWithCropperTool($profilePic, $croppedImage, 'admin_user', 'account', true);
                    }
                    $details->first_name            = $request->first_name ?? null;
                    $details->last_name             = $request->last_name ?? null;
                    $details->full_name             = $request->first_name." ".$request->last_name;
                    $details->email                 = $request->email ?? null;
                    $details->phone_no              = $request->phone_no ?? null;
                    $details->profile_pic           = $uploadedImage;
                    $details->password              = $password;
                    $details->type                  = 'A';

                    if ($details->save()) {
                        // Start :: Inserting data to user_roles table
                        if ($request->role) {
                            foreach ($request->role as $valRole) {
                                $userRoleData           = new UserRole;
                                $userRoleData->user_id  = $details->id;
                                $userRoleData->role_id  = $valRole;
                                $userRoleData->save();
                            }
                        }
                        // End :: Inserting data to user_roles table

                        // Start :: Mail to Sub Admin & Super Admin
                        // $siteSetting = getSiteSettings();

                        // $userModel = $this->model->findOrFail($details->id);
                        // $roleArray = [];
                        // if (count($userModel->userRoles) > 0) {
                        //     foreach ($userModel->userRoles as $role) {
                        //         $roleArray[] = $role['name'];
                        //     }
                        // }        
                        // Email to created sub admin
                        // \Mail::send('emails.admin.registration_details_to_sub_admin',
                        // [
                        //     'user'          => $details,
                        //     'password'      => $password,
                        //     'siteSetting'   => $siteSetting,
                        //     'app_config'    => [
                        //         'appname'       => $siteSetting->website_title,
                        //         'appLink'       => getBaseUrl(),
                        //         'controllerName'=> 'users',
                        //         'currentLang'=> $currentLang,
                        //     ],
                        // ], function ($m) use ($details, $siteSetting) {
                        //     $m->to($details->email, $details->full_name)->subject('Sub Admin Registration - '.$siteSetting->website_title);
                        // });

                        // Mail to admin
                        // \Mail::send('emails.admin.sub_admin_registration_details_to_super_admin',
                        // [
                        //     'user'          => $details,
                        //     'password'      => $password,
                        //     'roleArray'     => $roleArray,
                        //     'siteSetting'   => $siteSetting,
                        //     'app_config'    => [
                        //         'appname'       => $siteSetting->website_title,
                        //         'appLink'       => getBaseUrl(),
                        //         'controllerName'=> 'users',
                        //     ],
                        // ], function ($m) use ($siteSetting) {
                        //     $m->to($siteSetting->to_email, $siteSetting->website_title)->subject('New Sub Admin Registration - '.$siteSetting->website_title);
                        // });
                        // End :: Mail to Sub Admin & Super Admin

                        $this->generateNotifyMessage('success', trans('custom_admin.success_data_updated_successfully'), false);
                        return redirect()->route($this->routePrefix.'.'.$this->listUrl);
                    } else {
                        // If files uploaded then delete those files
                        unlinkFiles($uploadedImage, $this->pageRoute, true);

                        $this->generateNotifyMessage('error', trans('custom_admin.error_took_place_while_adding'), false);
                        return redirect()->back()->withInput();
                    }
                }
            }

            $data['roleList'] = Role::where('id', '<>', '1')
                                    ->where('is_admin', '1')
                                    ->whereNull('deleted_at')
                                    ->select('id','name','slug','is_admin')
                                    ->get();
            
            return view($this->viewFolderPath.'.add', $data);
        } catch (Exception $e) {
            $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
            return redirect()->route($this->routePrefix.'.'.$this->listUrl);
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return redirect()->route($this->routePrefix.'.'.$this->listUrl);
        }
    }

    /*
        * Function name : edit
        * Purpose       : This function is to update form
        * Input Params  : Request $request
        * Return Value  : Returns sub admin data
    */
    public function edit(Request $request, $id = null) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_edit_sub_admin'),
            'panelTitle'    => trans('custom_admin.label_edit_sub_admin'),
            'pageType'      => 'EDITPAGE'
        ];

        try {
            $data['id']         = $id;
            $data['subAdminId'] = $id = customEncryptionDecryption($id, 'decrypt');
            $data['roleList']   = Role::where('id', '<>', '1')
                                        ->where('is_admin', '1')
                                        ->select('id','name','slug','is_admin')
                                        ->get();
            $data['details']    = $details = $this->model->where(['id' => $id])->first();
            $roleIds = [];
            if ($data['details']->userRoles) {
                foreach ($data['details']->userRoles as $role) {
                    $roleIds[] = $role['id'];
                }
            }
            $data['roleIds'] = $roleIds;
            
            if ($request->isMethod('POST')) {
                if ($id == null) {
                    $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
                    return redirect()->route($this->routePrefix.'.'.$this->listUrl);
                }
                $validationCondition = array(
                    'first_name'    => 'required',
                    'last_name'     => 'required',
                    'email'         => 'required|regex:'.config('global.EMAIL_REGEX'),
                    'phone_no'      => 'required',
                    'profile_pic'   => 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE'),
                );
                $validationMessages = array(
                    'first_name.required'   => trans('custom_admin.error_enter_first_name'),
                    'last_name.required'    => trans('custom_admin.error_enter_last_name'),
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
                    return redirect()->back()->withInput();
                } else {
                    $validationFlag = false;
                    // Unique Email validation for User type "Admin"
                    $userEmailExistCheck = $this->model->where('id', '<>', $id)
                                                ->where(['email' => $request->email, 'type' => 'A'])
                                                ->count();
                    if ($userEmailExistCheck > 0) {
                        $validationFlag = true;
                    }
                    
                    if (!$validationFlag) {
                        $profilePic         = $request->file('profile_pic');
                        $croppedImage       = $request->cropped_image;
                        $uploadedImage      = $details->profile_pic ?? '';
                        $previousFileName   = $details->profile_pic ?? null;
                        $unlinkStatus       = false;
                        if ($profilePic != '' && $croppedImage) {
                            if ($details->profile_pic != null) {
                                $previousFileName   = $details->profile_pic;
                                $unlinkStatus       = true;
                            }
                            $uploadedImage  = singleImageUploadWithCropperTool($profilePic, $croppedImage, 'admin_user', 'account', true, $previousFileName, $unlinkStatus);
                        }
                        $details->first_name    = $request->first_name ?? null;
                        $details->last_name     = $request->last_name ?? null;
                        $details->full_name     = $request->first_name." ".$request->last_name;
                        $details->email         = $request->email ?? null;
                        $details->phone_no      = $request->phone_no ?? null;
                        $details->profile_pic   = $uploadedImage;
                        $details->save();
                        
                        // Start :: Deleting & Inserting data to user_roles table
                        $deletingUserRoles = UserRole::where('user_id', $details->id)->delete();
                        if ($request->role) {
                            foreach ($request->role as $valRole) {
                                $userRoleData           = new UserRole;
                                $userRoleData->user_id  = $id;
                                $userRoleData->role_id  = $valRole;
                                $userRoleData->save();
                            }
                        }
                        // End :: Deleting & Inserting data to user_roles table

                        $this->generateNotifyMessage('success', trans('custom_admin.success_data_updated_successfully'), false);
                        return redirect()->route($this->routePrefix.'.'.$this->listUrl);
                    } else {
                        // If files uploaded then delete those files
                        unlinkFiles($uploadedImage, $this->pageRoute, true);

                        $this->generateNotifyMessage('error', trans('custom_admin.error_email_unique'), false);
                        return redirect()->back()->withInput();
                    }
                }
            }

            return view($this->viewFolderPath.'.edit', $data);
        } catch (Exception $e) {
            $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
            return redirect()->route($this->routePrefix.'.'.$this->listUrl);
        } catch (\Throwable $e) {
            $this->generateNotifyMessage('error', $e->getMessage(), false);
            return redirect()->route($this->routePrefix.'.'.$this->listUrl);
        }
    }

    /*
        * Function name : status
        * Purpose       : This function is to status
        * Input Params  : Request $request, $id = null
        * Return Value  : Returns json
    */
    public function status(Request $request, $id = null) {
        $title      = trans('custom_admin.message_error');
        $message    = trans('custom_admin.error_something_went_wrong');
        $type       = 'error';

        try {
            if ($request->ajax()) {
                $id = customEncryptionDecryption($id, 'decrypt');
                if ($id != null) {
                    $details = $this->model->where('id', $id)->first();
                    if ($details != null) {
                        if ($details->status == 1) {
                            $details->status = '0';
                            $details->save();
                            
                            $title      = trans('custom_admin.message_success');
                            $message    = trans('custom_admin.success_status_updated_successfully');
                            $type       = 'success';        
                        } else if ($details->status == 0) {
                            $details->status = '1';
                            $details->save();
        
                            $title      = trans('custom_admin.message_success');
                            $message    = trans('custom_admin.success_status_updated_successfully');
                            $type       = 'success';
                        }
                    } else {
                        $message = trans('custom_admin.error_invalid');
                    }
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
        * Function name : delete
        * Purpose       : This function is to delete record
        * Input Params  : Request $request, $id = null
        * Return Value  : Returns json
    */
    public function delete(Request $request, $id = null) {
        $title      = trans('custom_admin.message_error');
        $message    = trans('custom_admin.error_something_went_wrong');
        $type       = 'error';

        try {
            if ($request->ajax()) {
                $id = customEncryptionDecryption($id, 'decrypt');
                if ($id != null) {
                    $details = $this->model->where('id', $id)->first();
                    if ($details != null) {
                        $delete = $details->delete();
                        if ($delete) {
                            $title      = trans('custom_admin.message_success');
                            $message    = trans('custom_admin.success_data_deleted_successfully');
                            $type       = 'success';
                        } else {
                            $message    = trans('custom_admin.error_took_place_while_deleting');
                        }
                    } else {
                        $message = trans('custom_admin.error_invalid');
                    }
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
        * Function name : bulkActions
        * Purpose       : This function is to delete record, active/inactive
        * Input Params  : Request $request
        * Return Value  : Returns json
    */
    public function bulkActions(Request $request) {
        $title      = trans('custom_admin.message_error');
        $message    = trans('custom_admin.error_something_went_wrong');
        $type       = 'error';

        try {
            if ($request->ajax()) {
                $selectedIds    = $request->selectedIds;
                $actionType     = $request->actionType;
                if (count($selectedIds) > 0) {
                    if ($actionType ==  'active') {
                        $this->model->whereIn('id', $selectedIds)->update(['status' => '1']);
                        
                        $title      = trans('custom_admin.message_success');
                        $message    = trans('custom_admin.success_status_updated_successfully');
                        $type       = 'success';
                    } elseif ($actionType ==  'inactive') {
                        $this->model->whereIn('id', $selectedIds)->update(['status' => '0']);

                        $title      = trans('custom_admin.message_success');
                        $message    = trans('custom_admin.success_status_updated_successfully');
                        $type       = 'success';
                    } else {
                        $this->model->whereIn('id', $selectedIds)->delete();

                        $title      = trans('custom_admin.message_success');
                        $message    = trans('custom_admin.success_data_deleted_successfully');
                        $type       = 'success';
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
        * Function Name : deleteUploadedImage
        * Purpose       : This function is for delete uploaded image
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
                        $response = unlinkFiles($details->profile_pic, 'account', true);
                        if ($response) {
                            $details->$dbField = null;
                            if ($details->save()) {
                                $title      = trans('custom_admin.message_success');
                                $message    = trans('custom_admin.message_image_uploaded_successfully');
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
    
}