<?php
/*****************************************************/
# Company Name      :
# Author            :
# Created Date      : 17/01/2023
# Page/Class name   : CmsController
# Purpose           : CMS Management
/*****************************************************/

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Traits\GeneralMethods;
use App\Models\Cms;
use DataTables;

class CmsController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'Cms';
    public $management;
    public $modelName       = 'Cms';
    public $breadcrumb;
    public $routePrefix     = 'admin';
    public $pageRoute       = 'cms';
    public $listUrl         = 'cms.list';
    public $listRequestUrl  = 'cms.ajax-list-request';
    public $addUrl          = 'cms.add';
    public $editUrl         = 'cms.edit';
    public $statusUrl       = 'cms.change-status';
    public $deleteUrl       = 'cms.delete';
    public $viewFolderPath  = 'admin.cms';
    public $model           = 'Cms';

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

        $this->management  = trans('custom_admin.label_cms');
        $this->model        = new Cms();

        // Assign breadcrumb
        $this->assignBreadcrumb();
        
        // Variables assign for view page
        $this->assignShareVariables();
    }

    /*
        * Function name : list
        * Purpose       : This function is for the listing and searching
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : Returns to the list page
    */
    public function list(Request $request) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_cms_list'),
            'panelTitle'    => trans('custom_admin.label_cms_list'),
            'pageType'      => 'LISTPAGE'
        ];

        try {
            // Start :: Manage restriction
            $data['isAllow']    = false;
            $restrictions       = checkingAllowRouteToUser($this->pageRoute.'.');
            if ($restrictions['is_super_admin']) {
                $data['isAllow'] = true;
            }
            $data['allowedRoutes']  = $restrictions['allow_routes'];
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
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : Returns cms data
    */
    public function ajaxListRequest(Request $request) {
        $data['pageTitle'] = trans('custom_admin.label_cms_list');
        $data['panelTitle']= trans('custom_admin.label_cms_list');

        try {
            if ($request->ajax()) {
                $data = $this->model->get();

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
                        ->addColumn('page_name', function ($row) {
                            return excerpts($row->page_name, 6);
                        })
                        ->addColumn('updated_at', function ($row) {
                            return changeDateFormat($row->updated_at);
                        })
                        ->addColumn('status', function ($row) use ($isAllow, $allowedRoutes) {
                            // if ($isAllow || in_array($this->statusUrl, $allowedRoutes)) {
                            //     if ($row->status == '1') {
                            //         $status = ' <a href="javascript:void(0)" data-microtip-position="top" role="" aria-label="'.trans('custom_admin.label_active').'" data-id="'.customEncryptionDecryption($row->id).'" data-action-type="inactive" class="custom_font status"><span class="badge badge-pill badge-success">'.trans('custom_admin.label_active').'</span></a>';
                            //     } else {
                            //         $status = ' <a href="javascript:void(0)" data-microtip-position="top" role="" aria-label="'.trans('custom_admin.label_inactive').'" data-id="'.customEncryptionDecryption($row->id).'" data-action-type="active" class="custom_font status"><span class="badge badge-pill badge-danger">'.trans('custom_admin.label_inactive').'</span></a>';
                            //     }
                            // } else {
                            //     if ($row->status == '1') {
                            //         $status = ' <a data-microtip-position="top" role="" aria-label="'.trans('custom_admin.label_active').'" class="custom_font"><span class="badge badge-pill badge-success">'.trans('custom_admin.label_active').'</span></a>';
                            //     } else {
                            //         $status = ' <a data-microtip-position="top" role="" aria-label="'.trans('custom_admin.label_active').'" class="custom_font"><span class="badge badge-pill badge-danger">'.trans('custom_admin.label_inactive').'</span></a>';
                            //     }
                            // }
                            if ($row->status == '1') {
                                $status = ' <a data-microtip-position="top" role="" aria-label="'.trans('custom_admin.label_active').'" class="custom_font"><span class="badge badge-pill badge-success">'.trans('custom_admin.label_active').'</span></a>';
                            } else {
                                $status = ' <a data-microtip-position="top" role="" aria-label="'.trans('custom_admin.label_active').'" class="custom_font"><span class="badge badge-pill badge-danger">'.trans('custom_admin.label_inactive').'</span></a>';
                            }
                            return $status;
                        })
                        ->addColumn('action', function ($row) use ($isAllow, $allowedRoutes) {
                            $btn = '';
                            if ($isAllow || in_array($this->editUrl, $allowedRoutes)) {
                                $editLink = route($this->routePrefix.'.'.$this->editUrl, customEncryptionDecryption($row->id));

                                $btn .= '<a href="'.$editLink.'" data-microtip-position="top" role="tooltip" class="btn btn-info btn-circle btn-circle-sm" aria-label="'.trans('custom_admin.label_edit').'"><i class="fa fa-edit"></i></a>';
                            }
                            // if ($isAllow || in_array($this->deleteUrl, $allowedRoutes)) {
                            //     $deleteClass = 'delete';
                            //     if ($row->parent_id != null) {
                            //         $deleteClass = 'delete-parent';
                            //     }
                            //     $btn .= ' <a href="javascript: void(0);" data-microtip-position="top" role="tooltip" class="btn btn-danger btn-circle btn-circle-sm delete" aria-label="'.trans('custom_admin.label_delete').'" data-action-type="delete" data-id="'.customEncryptionDecryption($row->id).'"><i class="fa fa-trash"></i></a>';
                            // }
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
        * Purpose       : This function is to add cms page
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : Returns cms data
    */
    public function add(Request $request, $id = null) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_add_cms'),
            'panelTitle'    => trans('custom_admin.label_add_cms'),
            'pageType'      => 'CREATEPAGE'
        ];

        try {
            $data['parentPages'] = $this->model->whereNull(['parent_id', 'deleted_at'])->select('id', 'page_name')->get();
            
            if ($request->isMethod('POST')) {
                $validationCondition = array(
                    'page_name'     => 'required|unique:'.($this->model)->getTable().',page_name,NULL,id,deleted_at,NULL',
                    'title'         => 'required',
                    'banner_image'  => 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE'),
                    'featured_image'=> 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE'),
                    'other_image'   => 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE'),
                );
                $validationMessages = array(
                    'page_name.required'    => trans('custom_admin.error_page_name'),
                    'page_name.unique'      => trans('custom_admin.error_name_unique'),
                    'title.required'        => trans('custom_admin.error_title'),
                    'banner_image.mimes'    => trans('custom_admin.error_image_mimes'),
                    'featured_image.mimes'  => trans('custom_admin.error_image_mimes'),
                    'other_image.mimes'     => trans('custom_admin.error_image_mimes'),
                );
                $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($validator->fails()) {
                    $validationFailedMessages = validationMessageBeautifier($validator->messages()->getMessages());
                    $this->generateNotifyMessage('error', $validationFailedMessages, false);
                    return redirect()->back()->withInput();
                } else {
                    $saveData           = [];
                    $bannerImage        = $request->file('banner_image');
                    $featuredImage      = $request->file('featured_image');
                    $otherImage         = $request->file('other_image');
                    $uploadedBannerImage= $uploadedFeaturedImage = $uploadedOtherImage = '';

                    // Banner image upload
                    if ($bannerImage != '') {
                        $uploadedBannerImage        = singleImageUpload($this->modelName, $bannerImage, 'banner', $this->pageRoute, false);
                        $saveData['banner_image']   = $uploadedBannerImage;
                    }
                    // Featured image upload
                    if ($featuredImage != '') {
                        $uploadedFeaturedImage      = singleImageUpload($this->modelName, $featuredImage, 'featured_image', $this->pageRoute, false);
                        $saveData['featured_image'] = $uploadedFeaturedImage;
                    }
                    // Other image upload
                    if ($otherImage != '') {
                        $uploadedOtherImage         = singleImageUpload($this->modelName, $otherImage, 'other_image', $this->pageRoute, false);
                        $saveData['other_image']    = $uploadedOtherImage;
                    }
                    $saveData['slug']                       = generateUniqueSlug($this->model, trim($request->page_name,' '));
                    $saveData['page_name']                  = $request->page_name ?? null;
                    $saveData['title']                      = $request->title ?? null;
                    $saveData['short_title']                = $request->short_title ?? null;
                    $saveData['short_description']          = $request->short_description ?? null;
                    $saveData['description']                = $request->description ?? null;
                    $saveData['description2']               = $request->description2 ?? null;
                    $saveData['other_description']          = $request->other_description ?? null;
                    $saveData['banner_title']               = $request->banner_title ?? null;
                    $saveData['banner_short_title']         = $request->banner_short_title ?? null;
                    $saveData['banner_short_description']   = $request->banner_short_description ?? null;
                    $saveData['meta_title']                 = $request->meta_title ?? null;
                    $saveData['meta_keywords']              = $request->meta_keywords ?? null;
                    $saveData['meta_description']           = $request->meta_description ?? null;
                    $save = $this->model->create($saveData);

                    if ($save) {
                        $this->generateNotifyMessage('success', trans('custom_admin.success_data_updated_successfully'), false);
                        return redirect()->route($this->routePrefix.'.'.$this->listUrl);
                    } else {
                        // If files uploaded then delete those files
                        unlinkFiles($this->modelName, $uploadedBannerImage, $this->pageRoute, true);
                        unlinkFiles($this->modelName, $uploadedFeaturedImage, $this->pageRoute, true);
                        unlinkFiles($this->modelName, $uploadedOtherImage, $this->pageRoute, true);

                        $this->generateNotifyMessage('error', trans('custom_admin.error_took_place_while_updating'), false);
                        return redirect()->back()->withInput();
                    }
                }
            }
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
        * Purpose       : This function is to edit cms
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : Returns cms data
    */
    public function edit(Request $request, $id = null) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_edit_cms'),
            'panelTitle'    => trans('custom_admin.label_edit_cms'),
            'pageType'      => 'EDITPAGE'
        ];

        try
        {
            $data['id']         = $id;
            $data['cmsId']      = $id = customEncryptionDecryption($id, 'decrypt');
            $data['details']    = $details = $this->model->where(['id' => $id])->first();
            $data['parentPages']= $this->model->where('id', '<>', $id)->whereNull(['parent_id', 'deleted_at'])->select('id', 'page_name')->get();
            
            if ($request->isMethod('POST')) {
                if ($id == null) {
                    $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
                    return redirect()->route($this->pageRoute.'.'.$this->listUrl);
                }
                $validationCondition = array(
                    'page_name'     => 'required|unique:'.($this->model)->getTable().',page_name,'.$id.',id,deleted_at,NULL',
                    'title'         => 'required',
                    'banner_image'  => 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE'),
                    'featured_image'=> 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE'),
                    'other_image'   => 'mimes:'.config('global.IMAGE_FILE_TYPES').'|max:'.config('global.IMAGE_MAX_UPLOAD_SIZE'),
                );
                $validationMessages = array(
                    'page_name.required'    => trans('custom_admin.error_page_name'),
                    'page_name.unique'      => trans('custom_admin.error_name_unique'),
                    'title.required'        => trans('custom_admin.error_title'),
                    'banner_image.mimes'    => trans('custom_admin.error_image_mimes'),
                    'featured_image.mimes'  => trans('custom_admin.error_image_mimes'),
                    'other_image.mimes'     => trans('custom_admin.error_image_mimes'),
                );
                $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($validator->fails()) {
                    $validationFailedMessages = validationMessageBeautifier($validator->messages()->getMessages());
                    $this->generateNotifyMessage('error', $validationFailedMessages, false);
                    return redirect()->back()->withInput();
                } else {
                    $updateData                 = [];
                    $bannerImage                = $request->file('banner_image');
                    $featuredImage              = $request->file('featured_image');
                    $otherImage                 = $request->file('other_image');
                    $previousBannerImage        = $previousFeaturedImage = $previousOtherImage = null;
                    $unlinkBannerImageStatus    = $unlinkFeaturedImageStatus = $unlinkOtherImageStatus = false;
                    $uploadedBannerImage        = $uploadedFeaturedImage = $uploadedOtherImage = '';

                    // Banner image upload
                    if ($bannerImage != '') {
                        if ($details['banner_image'] != null) {
                            $previousBannerImage        = $details['banner_image'];
                            $unlinkBannerImageStatus    = true;
                        }
                        $uploadedBannerImage            = singleImageUpload($this->modelName, $bannerImage, 'banner', $this->pageRoute, false, $previousBannerImage, $unlinkBannerImageStatus);
                        $updateData['banner_image']     = $uploadedBannerImage;
                    }
                    // Featured image upload
                    if ($featuredImage != '') {
                        if ($details['featured_image'] != null) {
                            $previousFeaturedImage      = $details['featured_image'];
                            $unlinkFeaturedImageStatus  = true;
                        }
                        $uploadedFeaturedImage          = singleImageUpload($this->modelName, $featuredImage, 'featured_image', $this->pageRoute, false, $previousFeaturedImage, $unlinkFeaturedImageStatus);
                        $updateData['featured_image']   = $uploadedFeaturedImage;
                    }
                    // Other image upload
                    if ($otherImage != '') {
                        if ($details['other_image'] != null) {
                            $previousOtherImage         = $details['other_image'];
                            $unlinkOtherImageStatus     = true;
                        }
                        $uploadedOtherImage             = singleImageUpload($this->modelName, $otherImage, 'other_image', $this->pageRoute, false, $previousOtherImage, $unlinkOtherImageStatus);
                        $updateData['other_image']      = $uploadedOtherImage;
                    }
                    $updateData['slug']                 = generateUniqueSlug($this->model, trim($request->page_name,' '), $data['id']);
                    foreach ($this->websiteLanguages as $langKey => $langVal) {
                        $updateData['page_name']                = $request->page_name ?? null;
                        $updateData['title']                    = $request->title ?? null;
                        $updateData['short_title']              = $request->short_title ?? null;
                        $updateData['short_description']        = $request->short_description ?? null;
                        $updateData['description']              = $request->description ?? null;
                        $updateData['description2']             = $request->description2 ?? null;
                        $updateData['other_description']        = $request->other_description ?? null;
                        $updateData['banner_title']             = $request->banner_title ?? null;
                        $updateData['banner_short_title']       = $request->banner_short_title ?? null;
                        $updateData['banner_short_description'] = $request->banner_short_description ?? null;
                        $updateData['meta_title']               = $request->meta_title ?? null;
                        $updateData['meta_keywords']            = $request->meta_keywords ?? null;
                        $updateData['meta_description']         = $request->meta_description ?? null;
                    }
                    $update = $details->update($updateData);

                    if ($update) {
                        $this->generateNotifyMessage('success', trans('custom_admin.success_data_updated_successfully'), false);
                        return redirect()->route($this->routePrefix.'.'.$this->listUrl);
                    } else {
                        // If files uploaded then delete those files
                        unlinkFiles($this->modelName, $uploadedBannerImage, $this->pageRoute, true);
                        unlinkFiles($this->modelName, $uploadedFeaturedImage, $this->pageRoute, true);
                        unlinkFiles($this->modelName, $uploadedOtherImage, $this->pageRoute, true);

                        $this->generateNotifyMessage('error', trans('custom_admin.error_took_place_while_updating'), false);
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
        * Author        :
        * Created Date  :
        * Modified date :
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
                            $checkingRelatedRecordsExist = $this->model->where(['parent_id' => $id, 'status' => '1'])->whereNull('deleted_at')->count();
                            if (!$checkingRelatedRecordsExist) {
                                $details->status = '0';
                                $details->save();
                                
                                $title      = trans('custom_admin.message_success');
                                $message    = trans('custom_admin.success_status_updated_successfully');
                                $type       = 'success';
                            } else {
                                $title      = trans('custom_admin.message_warning');
                                $message    = trans('custom_admin.message_inactive_related_records_exist');
                                $type       = 'warning';
                            }        
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
        * Author        :
        * Created Date  :
        * Modified date :
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
                        $checkingRelatedRecordsExist = $this->model->where('parent_id', $id)->whereNull('deleted_at')->count();
                        if (!$checkingRelatedRecordsExist) {
                            $delete = $details->delete();
                            if ($delete) {
                                $title      = trans('custom_admin.message_success');
                                $message    = trans('custom_admin.success_data_deleted_successfully');
                                $type       = 'success';
                            } else {
                                $message    = trans('custom_admin.error_took_place_while_deleting');
                            }
                        } else {
                            $title      = trans('custom_admin.message_warning');
                            $message    = trans('custom_admin.message_delete_related_records_exist');
                            $type       = 'warning';
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
        * Function name : upload
        * Purpose       : This function is for upload from ckeditor
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : Return image preview to ckeditor
    */
    public function upload(Request $request) {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = 'cms_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
        
            $request->file('upload')->move(public_path('images/uploads/'.$this->pageRoute), $fileName);
            
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/uploads/'.$this->pageRoute.'/'.$fileName);
            $msg = trans('custom_admin.message_image_uploaded_successfully');
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
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
                        $response       = unlinkFiles($details->banner_image, $this->pageRoute, true);
                        $responseFeaturedImage = unlinkFiles($details->featured_image, $this->pageRoute, true);
                        if ($response && $responseFeaturedImage) {
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