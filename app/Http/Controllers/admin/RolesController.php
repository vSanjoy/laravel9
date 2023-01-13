<?php
/*****************************************************/
# Company Name      :
# Author            :
# Created Date      :
# Page/Class name   : RolesController
# Purpose           : Role Management
/*****************************************************/

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Traits\GeneralMethods;
use App\Models\Role;
use App\Models\RolePage;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserRole;
use DataTables;

class RolesController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'Roles';
    public $management;
    public $modelName       = 'Role';
    public $breadcrumb;
    public $routePrefix     = 'admin';
    public $pageRoute       = 'role';
    public $listUrl         = 'role.list';
    public $listRequestUrl  = 'role.ajax-list-request';
    public $addUrl          = 'role.add';
    public $editUrl         = 'role.edit';
    public $statusUrl       = 'role.change-status';
    public $deleteUrl       = 'role.delete';
    public $sortUrl         = '';
    public $viewFolderPath  = 'admin.role';
    public $model           = 'Role';

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

        $this->management   = trans('custom_admin.label_menu_role');
        $this->model        = new Role();

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
            'pageTitle'     => trans('custom_admin.label_role_list'),
            'panelTitle'    => trans('custom_admin.label_role_list'),
            'pageType'      => 'LISTPAGE'
        ];

        try {
            // Start :: Manage restriction
            $data['isAllow'] = false;
            $restrictions   = checkingAllowRouteToUser($this->pageRoute.'.');
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
        * Return Value  : Returns role data
    */
    public function ajaxListRequest(Request $request) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_role_list'),
            'panelTitle'    => trans('custom_admin.label_role_list'),
            'pageType'      => 'LISTPAGE'
        ];

        try {
            if ($request->ajax()) {
                $data   = $this->model->where('id', '!=', '1')->get();

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
                        ->addColumn('name', function ($row) {
                            return excerpts($row->name, 10);
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
        * Purpose       : This function is to add role
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : 
    */
    public function add(Request $request, $id = null) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_add_role'),
            'panelTitle'    => trans('custom_admin.label_add_role'),
            'pageType'      => 'CREATEPAGE'
        ];

        try {
            if ($request->isMethod('POST')) {
                $validationCondition = array(
                    'name'  => 'required|unique:'.($this->model)->getTable().',name,NULL,id,deleted_at,NULL',
                );
                $validationMessages = array(
                    'name.required' => trans('custom_admin.error_role'),
                    'name.unique'   => trans('custom_admin.error_role_unique'),
                );
                $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($validator->fails()) {
                    $validationFailedMessages = validationMessageBeautifier($validator->messages()->getMessages());
                    $this->generateNotifyMessage('error', $validationFailedMessages, false);
                    return redirect()->back()->withInput();
                } else {
                    $details            = $this->model;
                    $details->name      = $request->name ?? null;
                    $details->slug      = generateUniqueSlug($this->model, trim($request->name,' '));
                    $details->is_admin  = '1';
                    
                    if ($details->save()) {
                        // Inserting role_page_id into role_permission table
                        if (isset($request->role_page_ids) && count($request->role_page_ids)) {
                            foreach ($request->role_page_ids as $keyRolePageId => $rolePageId) {
                                $rolePermission[$keyRolePageId]['role_id'] = $details->id;
                                $rolePermission[$keyRolePageId]['page_id'] = $rolePageId;
                            }
                            RolePermission::insert($rolePermission);
                        }
                        $this->generateNotifyMessage('success', trans('custom_admin.success_data_updated_successfully'), false);
                        return redirect()->route($this->routePrefix.'.'.$this->listUrl);
                    } else {
                        $this->generateNotifyMessage('error', trans('custom_admin.error_took_place_while_adding'), false);
                        return redirect()->back()->withInput();
                    }
                }
            }

            $routeCollection        = self::getRoutes();
            $data['routeCollection']= $routeCollection;

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
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : Request $request
        * Return Value  : Returns role data
    */
    public function edit(Request $request, $id = null) {
        $data = [
            'pageTitle'     => trans('custom_admin.label_edit_role'),
            'panelTitle'    => trans('custom_admin.label_edit_role'),
            'pageType'      => 'EDITPAGE'
        ];

        try {
            $data['id']         = $id;
            $data['roleId']     = $id = customEncryptionDecryption($id, 'decrypt');
            $data['details']    = $details = Role::where(['id' => $id])->with(['permissions'])->first();
            $routeCollection    = self::getRoutes();
            
            if ($request->isMethod('POST')) {
                if ($id == null) {
                    $this->generateNotifyMessage('error', trans('custom_admin.error_something_went_wrong'), false);
                    return redirect()->route($this->routePrefix.'.'.$this->listUrl);
                }
                $validationCondition = array(
                    'name' => 'required|unique:' .($this->model)->getTable().',name,'.$data['roleId'].',id,deleted_at,NULL',
                );
                $validationMessages = array(
                    'name.required' => trans('custom_admin.error_role'),
                    'name.unique'   => trans('custom_admin.error_role_unique'),
                );
                $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($validator->fails()) {
                    $validationFailedMessages = validationMessageBeautifier($validator->messages()->getMessages());
                    $this->generateNotifyMessage('error', $validationFailedMessages, false);
                    return redirect()->back()->withInput();
                } else {
                    $details->name  = $request->name ?? null;
                    $details->slug  = generateUniqueSlug($this->model, trim($request->name,' '), $data['roleId']);
                    
                    if ($details->save()) {
                        // Deleting and Inserting role_page_id into role_permission table
                        $deleteRolePermissions = RolePermission::where('role_id',$details->id)->delete();
                        if (isset($request->role_page_ids) && count($request->role_page_ids)) {
                            foreach ($request->role_page_ids as $keyRolePageId => $rolePageId) {
                                $rolePermission[$keyRolePageId]['role_id'] = $details->id;
                                $rolePermission[$keyRolePageId]['page_id'] = $rolePageId;
                            }
                            RolePermission::insert($rolePermission);
                        }
                        $this->generateNotifyMessage('success', trans('custom_admin.success_data_updated_successfully'), false);
                        return redirect()->route($this->routePrefix.'.'.$this->listUrl);
                    } else {                        
                        $this->generateNotifyMessage('error', trans('custom_admin.error_took_place_while_updating'), false);
                        return redirect()->back()->withInput();
                    }
                }
            }

            $existingPermission = [];
            if (count($details->permissions) > 0) {
                foreach ($details->permissions as $permission) {
                    $existingPermission[] = $permission['page_id'];
                }
            }
            $data['routeCollection']    = $routeCollection;
            $data['existingPermission'] = $existingPermission;

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
                            $isRelatedWithAnotherTable = UserRole::where('role_id', $id)->count();
                            if ($isRelatedWithAnotherTable > 0) {
                                $title      = trans('custom_admin.message_warning');
                                $message    = trans('custom_admin.message_inactive_related_records_exist');
                                $type       = 'warning';
                            } else {
                                $details->status = '0';
                                $details->save();
                                
                                $title      = trans('custom_admin.message_success');
                                $message    = trans('custom_admin.success_status_updated_successfully');
                                $type       = 'success';
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
                    if ($details) {
                        $isRelatedWithAnotherTable = UserRole::where('role_id', $id)->count();
                        if ($isRelatedWithAnotherTable > 0) {
                            $message = trans('custom_admin.error_role_user');
                        } else {
                            $delete = $details->delete();
                            if ($delete) {
                                RolePermission::where('role_id',$id)->delete();
                                $title      = trans('custom_admin.message_success');
                                $message    = trans('custom_admin.success_data_deleted_successfully');
                                $type       = 'success';
                            } else {
                                $message    = trans('custom_admin.error_took_place_while_deleting');
                            }
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
        * Author        :
        * Created Date  :
        * Modified date :
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
                $blockCount     = 0;

                if (count($selectedIds) > 0) {
                    if ($actionType ==  'active') {
                        $this->model->whereIn('id', $selectedIds)->update(['status' => '1']);
                        
                        $title      = trans('custom_admin.message_success');
                        $message    = trans('custom_admin.success_status_updated_successfully');
                        $type       = 'success';
                    } else {
                        foreach ($selectedIds as $key => $id) {
                            $isRelatedWithAnotherTable = UserRole::where('role_id', $id)->count();
                            if ($isRelatedWithAnotherTable) {
                                $blockCount++;
                            } else {
                                if ($actionType ==  'inactive') {
                                    $this->model->where('id', $id)->update(['status' => '0']);
                                    $message    = trans('custom_admin.success_status_updated_successfully');
                                } else if ($actionType ==  'delete') {
                                    $this->model->where('id', $id)->delete();
                                    $message    = trans('custom_admin.success_data_deleted_successfully');
                                }
                            }
                        }
                        
                        if ($blockCount) {
                            $title      = trans('custom_admin.message_warning');
                            if ($actionType ==  'inactive') {
                                $message    = trans('custom_admin.message_inactive_related_records_exist');
                            } else {
                                $message    = trans('custom_admin.message_delete_related_records_exist');
                            }
                            $type       = 'warning';
                        } else {
                            $title      = trans('custom_admin.message_success');
                            $type       = 'success';
                        }
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
        * Function name : getRoutes
        * Purpose       : This function is to get all routes
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : 
        * Return Value  : Returns array
    */
    public function getRoutes() {
        $routeCollection = \Route::getRoutes();

        // echo "<table style='width:100%'>";
        //     echo "<tr>";
        //         echo "<td width='10%'><h4>Serial</h4></td>";
        //         echo "<td width='10%'><h4>HTTP Method</h4></td>";
        //         echo "<td width='10%'><h4>Route</h4></td>";
        //         echo "<td width='10%'><h4>Name</h4></td>";
        //         echo "<td width='70%'><h4>Corresponding Action</h4></td>";
        //     echo "</tr>";
        //     $k = 1;
        //     foreach ($routeCollection as $route) {
        //         $namespace = $route->uri();
        //         if (!in_array("POST", $route->methods)  && strstr($namespace,'adminpanel/') != '' && strstr($route->getName(),'admin') != '') {
        //             echo "<tr>";
        //                 echo "<td>" . $k . "</td>";
        //                 echo "<td>" . $route->methods[0] . "</td>";
        //                 echo "<td>" . $route->uri() . "</td>";
        //                 echo "<td>" . $route->getName() . "</td>";
        //                 echo "<td>" . $route->getActionName() . "</td>";
        //             echo "</tr>";
        //             $k++;
        //         }                
        //     }
        // echo "</table>";
        // die('here');

        $list = [];
        $excludedSections = ['forgot','profile','update','reset','role','subAdmin'];
        
        foreach($routeCollection as $route) {
            $namespace = $route->uri();
            
            if (!in_array("POST", $route->methods)  && strstr($namespace,'adminpanel/') != '' && strstr($route->getName(),'admin') != '') {
                $group = str_replace("admin.", "", $route->getName());
                $group = strstr($group, ".", true);
                if ($group) {
                    if (!in_array($group, $excludedSections)) {
                        $pagePath       = explode('admin.',$route->getName());
                        $getPagePath    = $pagePath[1];
                        
                        //Checking route exist in role_pages table or not, if not then insert and get the id
                        $rolePageDetails = RolePage::where('routeName', '=', $getPagePath)->first();
                        if ($rolePageDetails == null) {
                            $rolePageDetails = new RolePage();
                            $rolePageDetails->routeName = $getPagePath;
                            $rolePageDetails->save();
                        }

                        if (!array_key_exists($group, $list)) {
                            $list[$group] = [];
                        }
                        array_push($list[$group], [
                            "method" => $route->methods[0],
                            "uri" => $route->uri(),
                            "path" => $getPagePath,
                            "role_page_id" => $rolePageDetails->id,
                            "group_name" => ($group) ? $group : '',
                            "middleware"=>$route->middleware()
                        ]);
                    }
                }
            }
        }
        return $list;
    }

}