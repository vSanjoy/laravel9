<?php
/*
    # Purpose   : For global
*/

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Cms;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Models\Order;

/*
    * Function name : getAppName
    * Purpose       : This function is to return app name
    * Input Params  : 
    * Return Value  : 
*/
function getAppName() {
    return 'Made In The Six';
}

/*
    * Function name : getAppName
    * Purpose       : This function is to return app name
    * Input Params  : 
    * Return Value  : 
*/
function getBaseUrl() {
    $baseUrl = url('/');
    return $baseUrl;
}

/*
    * Function name : getSiteSettings
    * Purpose       : This function is to return website settings
    * Input Params  : Void
    * Return Value  : Array
*/
function getSiteSettings() {
    $siteSettings       = WebsiteSetting::where('id', 1)->first();
    $siteSettingData    = $siteSettings;
    return $siteSettingData;
}

/*
    * Function name : validationMessageBeautifier
    * Purpose       : This function is to beautify validation messages
    * Input Params  : $errorMessageObject
    * Return Value  : String
*/
function validationMessageBeautifier($errorMessageObject) {
    $validationFailedMessages = '';
    foreach ($errorMessageObject as $fieldName => $messages) {
        foreach($messages as $message) {
            $validationFailedMessages .= $message.'<br>';
        }
    }
    return $validationFailedMessages;
}

/*
    * Function name : getUserRoleSpecificRoutes
    * Purpose       : This function is for user specific routes
    * Input Params  : Void
    * Return Value  : Array
*/
function getUserRoleSpecificRoutes() {
    $existingRoutes = [];
    $userExistingRoles = \Auth::guard('admin')->user()->userRoles;
    if ($userExistingRoles) {
        foreach ($userExistingRoles as $role) {
            if ($role->rolePermissionToRolePage) {
                foreach ($role->rolePermissionToRolePage as $permission) {
                    $existingRoutes[] = $permission['routeName'];
                }
            }
        }
    }
    return $existingRoutes;
}

/*
    * Function name : checkingAllowRouteToUser
    * Purpose       : This function is for allowed routes
    * Input Params  : $routeToCheck without admin. alias
    * Return Value  : Array
*/
function checkingAllowRouteToUser($routePartToCheck = null) {
    $existingRoutes['is_super_admin']   = false;
    $existingRoutes['allow_routes']     = [];

    if (\Auth::guard('admin')->user()->id == 1 || \Auth::guard('admin')->user()->type == 'SA') {
        $existingRoutes['is_super_admin'] = true;
    } else {
        $userExistingRoles = \Auth::guard('admin')->user()->userRoles;
        if ($userExistingRoles) {
            foreach ($userExistingRoles as $role) {
                if ($role->rolePermissionToRolePage) {
                    foreach ($role->rolePermissionToRolePage as $permission) {
                        if (strpos($permission['routeName'], $routePartToCheck) !== false) {
                            $existingRoutes['allow_routes'][] = $permission['routeName'];
                        }
                    }
                }
            }
        }
    }
    return $existingRoutes;
}

/*
    * Function name : customEncryptionDecryption
    * Purpose       : This function is for encrypt/decrypt data
    * Input Params  : $string, $action = encrypt/decrypt
    * Return Value  : String
*/
function customEncryptionDecryption($string, $action = 'encrypt') {
    $secretKey = 'c7tpe291z';
    $secretVal = 'GfY7r512';
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secretKey);
    $iv = substr(hash('sha256', $secretVal), 0, 16);

    if ($action == 'encrypt') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

/*
    * Function Name : generateUniqueSlug
    * Purpose       : This function is to generate unique slug
    * Author        :
    * Created Date  :
    * Modified date :
    * Input Params  : $type, $validationFailedMessages, $extraTitle = false
    * Return Value  : Mixed
*/
function generateUniqueSlug($model, $title, $id = null) {
    $slug = Str::slug($title);
    $currentSlug = '';

    if ($id) {
        $id = customEncryptionDecryption($id, 'decrypt');
        $currentSlug = $model->where('id', '=', $id)->value('slug');
    }

    if ($currentSlug && $currentSlug === $slug) {
        return $slug;
    } else {
        $slugList = $model::where('slug', 'LIKE', $slug . '%')->whereNull('deleted_at')->pluck('slug');
        if ($slugList->count() > 0) {
            $slugList = $slugList->toArray();
            if (!in_array($slug, $slugList)) {
                return $slug;
            }
            $newSlug = '';
            for ($i = 1; $i <= count($slugList); $i++) {
                $newSlug = $slug . '-' . $i;
                if (!in_array($newSlug, $slugList)) {
                    return $newSlug;
                }
            }
            return $newSlug;
        } else {
            return $slug;
        }
    }
}

/*
    * Function Name : generateSortNumber
    * Purpose       : This function is to generate sort number
    * Author        :
    * Created Date  :
    * Modified date :
    * Input Params  : $model, $id = null
    * Return Value  : Sort number
*/
function generateSortNumber($model = null, $id = null) {
    if ($id != null) {
        $gettingLastSortedCount = $model::select('sort')->where('id','<>',$id)->whereNull('deleted_at')->orderBy('sort','desc')->first();
    } else {
        $gettingLastSortedCount = $model::select('sort')->whereNull('deleted_at')->orderBy('sort','desc')->first();
    }        
    $newSort = isset($gettingLastSortedCount->sort) ? ($gettingLastSortedCount->sort + 1) : 0;

    return $newSort;
}

/*
    * Function Name : excerpts
    * Purpose       : This function is to show certain words
    * Author        :
    * Created Date  :
    * Modified date :
    * Input Params  : $text, $limit = 5, $type = null
    * Return Value  : Certain words
*/
function excerpts($text, $limit = 5, $type = null) {
    if (str_word_count($text, 0) > $limit) {
        $words  = str_word_count($text, 2);
        $pos    = array_keys($words);
        $text   = substr($text, 0, $pos[$limit]);
        $text   = trim($text);
        if ($type == null) {
            $text .= '...';
        }
    }
    return $text;
}

/*
    * Function Name : truncateString
    * Purpose       : This function is to show certain words
    * Author        :
    * Created Date  :
    * Modified date :
    * Input Params  : $str, $chars, $to_space, $replacement="..."
    * Return Value  : Certain words
*/
function truncateString($str, $chars, $to_space, $replacement="...") {
    if($chars > strlen($str)) return $str;
 
    $str = substr($str, 0, $chars);
    $space_pos = strrpos($str, " ");
    if($to_space && $space_pos >= 0) 
        $str = substr($str, 0, strrpos($str, " "));
 
    return($str . $replacement);
 }

/*
    * Function name : singleImageUpload
    * Purpose       : This function is for image upload
    * Input Params  : $modelName, $originalImage, $imageName, $uploadedFolder, $thumbImage = false,
    *                   $previousFileName = null, $unlinkStatus = false
    * Return Value  : Uploaded file name
*/
function singleImageUpload($modelName, $originalImage, $imageName, $uploadedFolder, $thumbImage = false, $previousFileName = null, $unlinkStatus = false) {
    $originalFileName   = $originalImage->getClientOriginalName();
    $extension          = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $fileName           = $imageName.'_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
    $imageResize        = Image::make($originalImage->getRealPath());

    // Checking if folder already existed and if not create a new folder
    $directoryPath      = public_path('images/uploads/'.$uploadedFolder);
    $thumbDirectoryPath = public_path('images/uploads/'.$uploadedFolder.'/thumbs');
    $listThumbDirectoryPath = public_path('images/uploads/'.$uploadedFolder.'/list-thumbs');
    if (!File::isDirectory($directoryPath)) {
        File::makeDirectory($directoryPath);    // make the directory because it doesn't exists
    }
    $imageResize->save($directoryPath.'/'.$fileName);

    if ($thumbImage) {
        if (!File::isDirectory($thumbDirectoryPath)) {
            File::makeDirectory($thumbDirectoryPath);    // make the Thumbs directory because it doesn't exists
        }

        $thumbImageWidth    = config('global.THUMB_IMAGE_WIDTH');   // Getting data from global file (global.php)
        $thumbImageHeight   = config('global.THUMB_IMAGE_HEIGHT');  // Getting data from global file (global.php)

        $imageResize->resize($thumbImageWidth[$modelName], $thumbImageHeight[$modelName], function ($constraint) {
            $constraint->aspectRatio();
        });
        $imageResize->save($thumbDirectoryPath.'/'.$fileName);
    }
    
    if ($unlinkStatus && $previousFileName != null) {
        if (file_exists($directoryPath.'/'.$previousFileName)) {
            $largeImagePath = $directoryPath.'/'.$previousFileName;
            @unlink($largeImagePath);
            if ($thumbImage) {
                $thumbImagePath = $thumbDirectoryPath.'/'.$previousFileName;
                @unlink($thumbImagePath);
            }
        }
    }
    return $fileName;
}

/*
    * Function name : singleImageUploadWithCropperTool
    * Purpose       : This function is for image upload with cropper tool
    * Input Params  : $originalImage, $croppedImage = base64 format,  $imageName,
    *                   $uploadedFolder, $thumbImage = false, $previousFileName = null, $unlinkStatus = false
    * Return Value  : Uploaded file name
*/
function singleImageUploadWithCropperTool($originalImage, $croppedImage, $imageName, $uploadedFolder, $thumbImage = false, $previousFileName = null, $unlinkStatus = false) {
    $originalFileName   = $originalImage->getClientOriginalName();
    $extension          = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $fileName           = $imageName.'_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
    $imageResize        = Image::make($originalImage->getRealPath());

    // Checking if folder already existed and if not create a new folder
    $directoryPath      = public_path('images/uploads/'.$uploadedFolder);
    $thumbDirectoryPath = public_path('images/uploads/'.$uploadedFolder.'/thumbs');
    if (!File::isDirectory($directoryPath)) {
        File::makeDirectory($directoryPath);    // make the directory because it doesn't exists
    }
    $imageResize->save($directoryPath.'/'.$fileName);

    if ($thumbImage) {
        if (!File::isDirectory($thumbDirectoryPath)) {
            File::makeDirectory($thumbDirectoryPath);    // make the Thumbs directory because it doesn't exists
        }

        // Cropped file upload
        $base64DecodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));
        $uploadedPath       = $thumbDirectoryPath.'/'.$fileName;
        file_put_contents($uploadedPath, $base64DecodedImage);
    }
    
    if ($unlinkStatus && $previousFileName != null) {
        if (file_exists($directoryPath.'/'.$previousFileName)) {
            $largeImagePath = $directoryPath.'/'.$previousFileName;
            @unlink($largeImagePath);
            if ($thumbImage) {
                $thumbImagePath = $thumbDirectoryPath.'/'.$previousFileName;
                @unlink($thumbImagePath);
            }
        }
    }
    return $fileName;
}

/*
    * Function name : gallerySingleImageUpload
    * Purpose       : This function is for image upload
    * Input Params  : $modelName, $originalImage, $imageName, $uploadedFolder, $albumId, $thumbImage = false,
    *                   $previousFileName = null, $unlinkStatus = false
    * Return Value  : Uploaded file name
*/
function gallerySingleImageUpload($modelName, $originalImage, $imageName, $uploadedFolder, $thumbImage = false) {
    $originalFileName   = $originalImage->getClientOriginalName();
    $extension          = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $fileName           = $imageName.'_'.rand(1000,9999).'_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
    $imageResize        = Image::make($originalImage->getRealPath());

    // Checking if folder already existed and if not create a new folder
    $directoryPath      = public_path('images/uploads/gallery');
    $subDirectoryPath   = public_path('images/uploads/gallery/'.$uploadedFolder);
    $thumbDirectoryPath = public_path('images/uploads/gallery/'.$uploadedFolder.'/thumbs');
    if (!File::isDirectory($directoryPath)) {
        File::makeDirectory($directoryPath);        // make the main directory (gallery) because it doesn't exists
        File::makeDirectory($subDirectoryPath);    // make the directory because it doesn't exists
    }
    $imageResize->save($subDirectoryPath.'/'.$fileName);

    if ($thumbImage) {
        if (!File::isDirectory($thumbDirectoryPath)) {
            File::makeDirectory($thumbDirectoryPath);    // make the Thumbs directory because it doesn't exists
        }

        $thumbImageWidth    = config('global.THUMB_IMAGE_WIDTH');   // Getting data from global file (global.php)
        $thumbImageHeight   = config('global.THUMB_IMAGE_HEIGHT');  // Getting data from global file (global.php)

        $imageResize->resize($thumbImageWidth[$modelName], $thumbImageHeight[$modelName], function ($constraint) {
            $constraint->aspectRatio();
        });
        $imageResize->save($thumbDirectoryPath.'/'.$fileName);
    }
    return $fileName;
}

/*
    * Function name : fileUpload
    * Purpose       : This function is for upload files
    * Input Params  : $originalFile, $fileName, $uploadedFolder
    * Return Value  : Uploaded file name
*/
function fileUpload($originalFile, $fileName, $uploadedFolder) {
    $originalFileName   = $originalFile->getClientOriginalName();
    $extension          = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $fileName           = $fileName.'_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
    $originalFile->move(public_path('images/uploads/'.$uploadedFolder), $fileName);

    return $fileName;
}

/*
    * Function name : unlinkFiles
    * Purpose       : This function is for unlinking files
    * Input Params  : $fileName, $uploadedFolder, $thumbFile = false
    * Return Value  : True
*/
function unlinkFiles($fileName, $uploadedFolder, $thumbFile = false) {
    if ($fileName != '') {
        $directoryPath      = public_path('images/uploads/'.$uploadedFolder);
        $thumbDirectoryPath = public_path('images/uploads/'.$uploadedFolder.'/thumbs');
        
        if (file_exists($directoryPath.'/'.$fileName)) {
            $largeFilePath = $directoryPath.'/'.$fileName;
            @unlink($largeFilePath);
            if ($thumbFile) {
                $thumbFilePath = $thumbDirectoryPath.'/'.$fileName;
                @unlink($thumbFilePath);
            }
        }
    }    
    return true;
}

/*
    * Function name : getCurrentDateTime
    * Purpose       : This function is to get current date time
    * Input Params  : 
    * Return Value  : Date and Time
*/
function getCurrentDateTime() {
    return Carbon::now()->format('Y-m-d h:i A');
}

/*
    * Function name : getCurrentFullDateTime
    * Purpose       : This function is to get current date time
    * Author        : 
    * Created Date  : 19-05-2021
    * Modified Date : 
    * Input Params  : 
    * Return Value  : Date and Time
*/
function getCurrentFullDateTime() {
    return Carbon::now()->format('Y-m-d H:i:s');
}

/*
    * Function name : getCurrentDateTimeWithoutAmPm
    * Purpose       : This function is to get current date time without am/pm
    * Input Params  : 
    * Return Value  : Date and Time
*/
function getCurrentDateTimeWithoutAmPm() {
    return Carbon::now()->format('Y-m-d H:i');
}

/*
    * Function name : changeDateFormat
    * Purpose       : This function is for formatting date
    * Input Params  : $fieldName, $dateFormat = false
    * Return Value  : Formatted date
*/
function changeDateFormat($fieldName, $dateFormat = false) {
    if ($dateFormat) {
        return Carbon::createFromFormat('Y-m-d H:i:s', $fieldName)->format($dateFormat);
    } else {
        return Carbon::createFromFormat('Y-m-d H:i:s', $fieldName)->format('Y-m-d H:i');
    }    
}

/*
    * Function name : changeDateFormatFromUnixTimestamp
    * Purpose       : This function is for formatting date
    * Input Params  : $dateValue, $dateFormat = false
    * Return Value  : True
*/
function changeDateFormatFromUnixTimestamp($dateValue, $dateFormat = false) {
    if ($dateFormat) {
        return Carbon::createFromTimestamp($dateValue)->format($dateFormat);
    } else {
        return Carbon::createFromTimestamp($dateValue)->format('Y-m-d H:i');
    }    
}

/*
    * Function name : dayParts
    * Purpose       : This function is to get morning/afternoon/evening
    * Input Params  : 
    * Return Value  : Data
*/
function dayParts() {
    if (date("H") < 12) {
        return "Good Morning";
    } elseif (date("H") > 11 && date("H") < 18) {
        return "Good Afternoon";
    } elseif (date("H") > 17) {
        return "Good Evening";
    }
}

/*
    * Function name : getMetaDetails
    * Purpose       : This function is to get meta details
    * Input Params  : $table = null, $where = ''
    * Return Value  : Meta details
*/
function getMetaDetails($table = null, $where = '') {
    if ($table == 'cms') {        
        $metaDetails = Cms::where('id', $where)->first();
        $metaData['title']              = $metaDetails['meta_title'] != '' ? $metaDetails['page_name'].' | '.$metaDetails['meta_title'] : $metaDetails['page_name'];
        $metaData['metaKeywords']       = $metaDetails['meta_keywords'] ?? null;
        $metaData['metaDescription']    = $metaDetails['meta_description'] ?? null;
    } else {
        $metaDetails = WebsiteSetting::select('website_title','default_meta_title', 'default_meta_keywords', 'default_meta_description')->first();
        $metaData['title']              = $metaDetails['default_meta_title'] ?? $metaDetails['website_title'];
        $metaData['metaKeywords']       = $metaDetails['default_meta_keywords'] ?? null;
        $metaData['metaDescription']    = $metaDetails['default_meta_description'] ?? null;
    }
    return $metaData;
}

/*
    * Function name : getSiteSettingsWithSelectFields
    * Purpose       : This function is to return website settings with
                        selected fields
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : Void
    * Return Value  : Array
*/
function getSiteSettingsWithSelectFields($selectedFields) {
    $dataToReturn = [];
    $siteSettingData = WebsiteSetting::select($selectedFields)->first();
    foreach ($selectedFields as $keyLabel => $valLabel) {
        $dataToReturn[$valLabel]  = $siteSettingData->$valLabel;
    }
    return $dataToReturn;
}

/*
    * Function name : generateSlots
    * Purpose       : This function is to return slots
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : Void
    * Return Value  : Array
*/
function generateSlots($requestedBookingDate = null, $serviceProviderId = 0) {
    $todaysDate = Carbon::now()->format('Y-m-d');
    if ($requestedBookingDate == null) {
        $currentDate = Carbon::now()->format('Y-m-d');
        $today       = date('l');
    } else {
        $currentDate = $requestedBookingDate;
        $today       = date('l', strtotime($requestedBookingDate));
    }
    
    $sameDateStatus = false;
    if ($todaysDate == $currentDate) {
        $sameDateStatus = true;
    }
    $slots = [];

    $shopOpenCloseTimeAccordingToDay = Slot::where('title->'.\App::getLocale(),'like','%'.$today.'%')->where('service_provider_id', $serviceProviderId)->first();
    
    // If not closed
    if ($shopOpenCloseTimeAccordingToDay->is_closed == 'N') {
        $currentTimeStamp   = strtotime(date('H:i'));
        $deliveryStartTime  = $currentTimeStamp;
        
        // Shop open and close time
        $shopOpenTime       = date('H:i', strtotime($shopOpenCloseTimeAccordingToDay->start_time));
        $shopCloseTime      = strtotime($shopOpenCloseTimeAccordingToDay->end_time);

        // Shop open Hour & minute
        $shopOpenTimeInHour     = date('H', strtotime($shopOpenCloseTimeAccordingToDay->start_time));
        $shopOpenTimeInMinute   = date('i', strtotime($shopOpenCloseTimeAccordingToDay->start_time));
        
        $remainder = $shopOpenTimeInMinute % 15;
        
        $remainingFromMinute = $shopOpenTimeInMinute - $remainder;
        
        if ($remainingFromMinute / 15 == 0) {
            $slotStartTime = strtotime($shopOpenTimeInHour.':15');
        }
        else if ($remainingFromMinute / 15 == 1) {
            $slotStartTime = strtotime($shopOpenTimeInHour.':30');
        }
        else if ($remainingFromMinute / 15 == 2) {
            $slotStartTime = strtotime($shopOpenTimeInHour.':45');
        }
        else if ($remainingFromMinute / 15 == 3) {
            $slotStartTime = strtotime(($shopOpenTimeInHour+1).':00');
        }

        // slot break up
        if ($slotStartTime < $shopCloseTime) {
            // start
            $firstSlotStartTime = $shopOpenCloseTimeAccordingToDay->start_time;
            if ($slotStartTime > strtotime($firstSlotStartTime)) {
                $slotStartTime = strtotime($firstSlotStartTime);
            }
            // end

            for ($slotStartTime; $slotStartTime <= $shopCloseTime;) {
                if ($sameDateStatus) {
                    if ($slotStartTime > $deliveryStartTime) {
                        $slots[] = date('H:i', $slotStartTime);
                    }
                } else {
                    $slots[] = date('H:i', $slotStartTime);
                }
                $slotStartTime = strtotime("+15 minutes", $slotStartTime);
            }
        }
        // start
        if (count($slots)) {
            if ($sameDateStatus) {
                if (strtotime($slots[count($slots)-1]) != $shopCloseTime && $deliveryStartTime <= $shopCloseTime) {
                    $slots[] = date('H:i',$shopCloseTime);
                }
            } else {
                if (strtotime($slots[count($slots)-1]) != $shopCloseTime) {
                    $slots[] = date('H:i',$shopCloseTime);
                }
            }
        }
        // end

        // If slot 2 exist START
        if ($shopOpenCloseTimeAccordingToDay->start_time2 != null && $shopOpenCloseTimeAccordingToDay->end_time2 != null) {
            // Shop open and close time
            $shopOpenTime2      = date('H:i', strtotime($shopOpenCloseTimeAccordingToDay->start_time2));
            $shopCloseTime2     = strtotime($shopOpenCloseTimeAccordingToDay->end_time2);

            // Shop open Hour & minute
            $shopOpenTimeInHour2     = date('H', strtotime($shopOpenCloseTimeAccordingToDay->start_time2));
            $shopOpenTimeInMinute2   = date('i', strtotime($shopOpenCloseTimeAccordingToDay->start_time2));
            
            $remainder2 = $shopOpenTimeInMinute2 % 15;
            
            $remainingFromMinute2 = $shopOpenTimeInMinute2 - $remainder2;            

            if ($remainingFromMinute2 / 15 == 0) {
                $slotStartTime2 = strtotime($shopOpenTimeInHour2.':15');
            }
            else if ($remainingFromMinute2 / 15 == 1) {
                $slotStartTime2 = strtotime($shopOpenTimeInHour2.':30');
            }
            else if ($remainingFromMinute2 / 15 == 2) {
                $slotStartTime2 = strtotime($shopOpenTimeInHour2.':45');
            }
            else if ($remainingFromMinute2 / 15 == 3) {
                $slotStartTime2 = strtotime(($shopOpenTimeInHour2+1).':00');
            }

            // slot break up
            if ($slotStartTime2 < $shopCloseTime2) {
                // start
                $secondSlotStartTime = $shopOpenCloseTimeAccordingToDay->start_time2;
                if ($slotStartTime2 > strtotime($secondSlotStartTime)) {
                    $slotStartTime2 = strtotime($secondSlotStartTime);
                }
                // end

                for ($slotStartTime2; $slotStartTime2 <= $shopCloseTime2;) {
                    if ($sameDateStatus) {
                        if ($slotStartTime2 > $deliveryStartTime) {
                            $slots[] = date('H:i', $slotStartTime2);
                        }
                    } else {
                        $slots[] = date('H:i', $slotStartTime2);
                    }
                    $slotStartTime2 = strtotime("+15 minutes", $slotStartTime2);
                }
            }

            // start
            if (count($slots)) {
                if ($sameDateStatus) {
                    if (strtotime($slots[count($slots)-1]) != $shopCloseTime2 && $deliveryStartTime <= $shopCloseTime2) {
                        $slots[] = date('H:i',$shopCloseTime2);
                    }
                } else {
                    if (strtotime($slots[count($slots)-1]) != $shopCloseTime2) {
                        $slots[] = date('H:i',$shopCloseTime2);
                    }
                }
            }
            // end
        }
        // If slot 2 exist END
    }
    
    return $slots;
}

/*
    * Function name : formatToTwoDecimalPlaces
    * Purpose       : This function is to return price 2 decimal places
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : 
    * Return Value  : 
*/
function formatToTwoDecimalPlaces($data) {
    return number_format((float)$data, 2, '.', '');
}

/*
    * Function name : priceRoundOff
    * Purpose       : This function is to return price rounding off
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : 
    * Return Value  : 
*/
function priceRoundOff(float $price) {
    $price = number_format((float)$price, 2, '.', '');
    
    $priceArr = explode('.', $price);
    $beforeDecimal = $priceArr[0];
    $afterDecimal = $priceArr[1];
    $lastDigit = substr($afterDecimal, -1);
    $lastDigit1 = substr($afterDecimal, -1);
    $firstDigit = substr($afterDecimal, 0, 1);
    
    if ($lastDigit >= 3 && $lastDigit <=7) {
        $lastDigit = 5;
        $price = $beforeDecimal.".".$firstDigit.$lastDigit;
    } else {
        $price = number_format((float)$price, 1, '.', '');
        $price = number_format((float)$price, 2, '.', '');
    }
    return $price;
}

/*
    * Function name : generateUniqueId
    * Purpose       : This function is to return unique id
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : 
    * Return Value  : 
*/
function generateUniqueId() {
    $timeNow        = date("his");
    $randNumber     = strtoupper(substr(sha1(time()), 0, 4));
    return $unique  = 'CR' . $timeNow . $randNumber;
}

/*
    * Function name : hoursAndMins
    * Purpose       : This function is to return total hours
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : 
    * Return Value  : 
*/
function hoursAndMins($time, $format = '%01d.%01d') {
    if (strpos($time, '/') !== false) {
        $explodedTime = explode('/', $time);
        $firstSession = $secondSession = 0;
        if ($explodedTime[0] <= 60 && $explodedTime[1] <= 60) {
            return $explodedTime[0].'/'.$explodedTime[1].' '.trans('custom.label_minutes');
        }
        else if ($explodedTime[0] > 60 && $explodedTime[1] <= 60) {
            $hours = floor($explodedTime[0] / 60);
            $minutes = ($explodedTime[0] % 60);
            $firstSession = sprintf($format, $hours, $minutes).' '.trans('custom.label_hours');
            $secondSession = $explodedTime[1].' '.trans('custom.label_minutes');

            return $firstSession.'/'.$secondSession;
        }
        else if ($explodedTime[0] <= 60 && $explodedTime[1] > 60) {
            $firstSession = $explodedTime[0].' '.trans('custom.label_minutes');

            $hours = floor($explodedTime[1] / 60);
            $minutes = ($explodedTime[1] % 60);
            $secondSession = sprintf($format, $hours, $minutes).' '.trans('custom.label_hours');
            
            return $firstSession.'/'.$secondSession;
        }
        else if ($explodedTime[0] > 60 && $explodedTime[1] > 60) {
            $hours = floor($explodedTime[0] / 60);
            $minutes = ($explodedTime[0] % 60);
            $firstSession = sprintf($format, $hours, $minutes);
            
            $hours1 = floor($explodedTime[1] / 60);
            $minutes1 = ($explodedTime[1] % 60);
            $secondSession = sprintf($format, $hours1, $minutes1);

            return $firstSession.'/'.$secondSession.' '.trans('custom.label_hours');
        }
    } else {
        if ($time == 120) {
        }
        if ($time > 60) {
            if ($time < 1) {
                return;
            }
            $hours = floor($time / 60);
            $minutes = ($time % 60);
            return sprintf($format, $hours, $minutes).' '.trans('custom.label_hours');
        } else {
            return $time.' '.trans('custom.label_minutes');
        }
    }   
}

/*
    * Function name : getCartItemDetails
    * Purpose       : This function return cart array
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : 
    * Return Value  : 
*/
function getCartItemDetails() {
    $cartSessionId = '';
    if (Session::get('cartSessionId') != '') {
        $cartSessionId = Session::get('cartSessionId');
    }

    $cartUserId = $totalCartCount = $totalCartAmount = $cartId = $uniqueId = 0;
    $getCartData = $cartArray = [];
    if (Auth::user()) {
        $cartUserId = Auth::user()->id;
        $cartConditions = ['user_id' => $cartUserId, 'type' => 'C'];
    } else {
        $cartConditions = ['session_id' => $cartSessionId];
    }
    // Getting cart details
    $getCartData = Cart::where($cartConditions)->first();
    if ($getCartData != null) {
        $cartId         = $getCartData->id;
        $uniqueId       = $getCartData->unique_id;
        // Main Cart array
        if (isset($getCartData->cartDetails) && $getCartData->cartDetails->count() > 0) {
            $i = 0;
            foreach ($getCartData->cartDetails as $cartDetails) {
                $productImage = asset('images/'.config('global.NO_IMAGE'));
                if ($cartDetails->productDetails->defaultImage != null) {
                    if (file_exists(public_path('/images/uploads/gallery/product/'.$cartDetails->productDetails->defaultImage->image))) {
                        $productImage = asset('/images/uploads/gallery/product/'.$cartDetails->productDetails->defaultImage->image);
                    }
                }
                $productDetails = json_decode($cartDetails->product_details, true);
                $cartArray[$i]['id']                = $cartDetails->id;
                $cartArray[$i]['cart_id']           = $cartDetails->cart_id;
                $cartArray[$i]['product_id']        = $cartDetails->product_id;
                $cartArray[$i]['quantity']          = $cartDetails->quantity;
                $cartArray[$i]['unit_price']        = $cartDetails->unit_price;
                $cartArray[$i]['unit_total_price']  = $cartDetails->unit_total_price;
                $cartArray[$i]['total_price']       = $cartDetails->total_price;
                $cartArray[$i]['product_title']     = $productDetails['title'];
                $cartArray[$i]['product_image']     = $productImage;
                
                // Total price
                $totalCartAmount    += $cartDetails->total_price;
                $totalCartCount     += $cartDetails->quantity;
                $i++;
            }
        }
    }
    $cartDetailArray = array(
                        'cartId'            => $cartId,
                        'uniqueId'          => $uniqueId,
                        'itemDetails'       => $cartArray,
                        'totalItems'        => $totalCartCount,
                        'totalCartAmount'   => (float)formatToTwoDecimalPlaces($totalCartAmount),
                        'payableAmount'     => (float)formatToTwoDecimalPlaces($totalCartAmount)
                    );
    // dd($cartDetailArray);
    return $cartDetailArray;
}

/*
    * Function name : getOrderDetails
    * Purpose       : This function return order array
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : 
    * Return Value  : 
*/
function getOrderDetails($orderId = null, $userId = null) {
    $totalOrderCount = $totalOrderAmount = $paidAmount = $discountAmount = $uniqueId = 0;
    $getOrderData = $orderArray = $mainOrder = [];
    $couponCode = null;

    if ($orderId != null && $userId != null) {
        $getOrderData   = Order::where(['id' => $orderId, 'user_id' => $userId])->first();
        $mainOrder      = $getOrderData->toArray();
        if ($getOrderData != null) {
            $orderId        = $getOrderData->id;
            $uniqueId       = $getOrderData->unique_id;
            $paidAmount     = $getOrderData->final_amount;
            $discountAmount = $getOrderData->discount_amount;
            $couponCode     = $getOrderData->coupon_code;
            // Main Order array
            if (isset($getOrderData->orderDetails) && $getOrderData->orderDetails->count() > 0) {
                $i = 0;
                foreach ($getOrderData->orderDetails as $orderDetails) {
                    $productImage = asset('images/'.config('global.NO_IMAGE'));
                    if ($orderDetails->productDetails->defaultImage != null) {
                        if (file_exists(public_path('/images/uploads/gallery/product/'.$orderDetails->productDetails->defaultImage->image))) {
                            $productImage = asset('/images/uploads/gallery/product/'.$orderDetails->productDetails->defaultImage->image);
                        }
                    }
                    $productDetails = json_decode($orderDetails->product_details, true);
                    $orderArray[$i]['id']                = $orderDetails->id;
                    $orderArray[$i]['order_id']          = $orderDetails->order_id;
                    $orderArray[$i]['product_id']        = $orderDetails->product_id;
                    $orderArray[$i]['quantity']          = $orderDetails->quantity;
                    $orderArray[$i]['unit_price']        = $orderDetails->unit_price;
                    $orderArray[$i]['unit_total_price']  = $orderDetails->unit_total_price;
                    $orderArray[$i]['total_price']       = $orderDetails->total_price;
                    $orderArray[$i]['product_title']     = $productDetails['title'];
                    $orderArray[$i]['product_image']     = $productImage;
                    
                    // Total price
                    $totalOrderAmount    += $orderDetails->total_price;
                    $totalOrderCount     += $orderDetails->quantity;
                    $i++;
                }
            }
        }
    }

    $orderDetailArray = array(
        'orderId'           => $orderId,
        'uniqueId'          => $uniqueId,
        'mainOrder'         => $mainOrder,
        'itemDetails'       => $orderArray,
        'totalItems'        => $totalOrderCount,
        'totalOrderAmount'  => (float)formatToTwoDecimalPlaces($totalOrderAmount),
        'discountAmount'    => (float)formatToTwoDecimalPlaces($discountAmount),
        'paidAmount'        => (float)formatToTwoDecimalPlaces($paidAmount),
    );

    return $orderDetailArray;
}



/******************************************************** API SECTION ********************************************************/

/*
    * Function name : generateResponseBody
    * Purpose       : This function is to generate response body
    * Author        : 
    * Created Date  : 
    * Modified Date : 
    * Input Params  : $code, $data, $success = true, $errorCode = null
    * Return Value  : Structured response body
*/
function generateResponseBody($code, $data, $message, $apiStatus = true, $statusCode = null) {
    $response       = [];
    $result         = [];
    $finalCode      = $code;
    $functionName   = null;

    // response return type
    if (gettype($data) === 'string') {
        $result['dataset'] = [];
        $result['status']['message'] = $message;
    } else if (gettype($data) === 'array' && array_key_exists('errors', $data)) {
        $result['dataset'] = [];
        $result['status']['message'] = implode(",",$data['errors']);
    } else {
        $result['dataset'] = $data;
        $result['status']['message'] = $message;
    }

    // response status
    $result['status']['action_status']  = $apiStatus;
    $result['status']['code']           = $statusCode;

    // explode to get code and function name
    if (strpos($code, '#') !== false) {
        $explodedCode   = explode('#',$code);
        $finalCode      = $explodedCode[0];
        $functionName   = $explodedCode[1];
        
        $result['publish']['api_code']      = $finalCode;
        $result['publish']['api_function']  = $functionName;
    }

    $response['response'] = $result;
   
    return $response;
}

/*
    * Function name : generateResponseBodyForSignInSignUp
    * Purpose       : This function is to generate response body for sign up / sign in
    * Author        : 
    * Created Date  : 
    * Modified Date : 
    * Input Params  : $code, $data, $message, $apiStatus = true, $statusCode = null, $encryptedUserData = ''
    * Return Value  : Structured response body
*/
function generateResponseBodyForSignInSignUp($code, $data, $message, $apiStatus = true, $statusCode = null, $encryptedUserData = '') {
    $response       = [];
    $result         = [];
    $finalCode      = $code;
    $functionName   = null;

    // response return type
    if (gettype($data) === 'string') {
        $result['dataset'] = [];
        $result['status']['message'] = $message;
    } else if (gettype($data) === 'array' && array_key_exists('errors', $data)) {
        $result['dataset'] = [];
        $result['status']['message'] = implode(",",$data['errors']);
    } else {
        $result['dataset'] = $data;
        $result['status']['message'] = $message;
    }

    // Encrypted user data
    if ($encryptedUserData != '') {
        $result['encryptedUserData'] = $encryptedUserData;
    }

    // response status
    $result['status']['action_status']  = $apiStatus;
    $result['status']['code']           = $statusCode;

    // explode to get code and function name
    if (strpos($code, '#') !== false) {
        $explodedCode   = explode('#',$code);
        $finalCode      = $explodedCode[0];
        $functionName   = $explodedCode[1];
        
        $result['publish']['api_code']      = $finalCode;
        $result['publish']['api_function']  = $functionName;
    }

    $response['response'] = $result;
   
    return $response;
}

/*
    * Function name : getFunctionNameFromRequestUrl
    * Purpose       : This function is to return structured function name
    * Author        : 
    * Created Date  : 
    * Modified Date : 
    * Input Params  : 
    * Return Value  : Structured function name
*/
function getFunctionNameFromRequestUrl() {
    $requestUrl             = Request::getRequestUri();
    $requestUrlAfterVersion = substr($requestUrl, strpos($requestUrl, 'v1/') + 3);
    $urlSegments            = explode("/",$requestUrlAfterVersion);
    $functionName           = $urlSegments[0];

    return str_replace("-","_",$functionName);
}

/*
    * Function name : categoryList
    * Purpose       : This function is category list
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : 
    * Return Value  : 
*/
function categoryList() {
    $categoryList   = Category::select('id','title')->where(['status' => '1'])->whereNull('deleted_at')->get();
    return $categoryList;
}

/*
    * Function name : vendorList
    * Purpose       : This function is vendor / user list
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : 
    * Return Value  : 
*/
function vendorList() {
    $vendorList   = User::select('id','full_name', 'email')->where('id','<>','1')->where(['type' => 'V'])->whereNull('deleted_at')->orderBy('id', 'desc')->get();
    return $vendorList;
}

/*
    * Function name : locationList
    * Purpose       : This function is location list
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : 
    * Return Value  : 
*/
function locationList() {
    $locationList   = Location::select('id','location')->where(['status' => '1'])->whereNull('deleted_at')->orderBy('sort', 'asc')->get();
    return $locationList;
}

/*
    * Function name : toSetAndGetLocale
    * Purpose       : This function is get and set language
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : $request
    * Return Value  : 
*/
function toSetAndGetLocale($request) {
    $requestLanguage = $request->header('x-lang');
    if (!$requestLanguage) {
        $requestLanguage =  \App::getLocale();
    }
    \App::setLocale($requestLanguage);
    return $requestLanguage;
}

/*
    * Function name : getUserFromHeader
    * Purpose       : This function is get user from header
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : $request
    * Return Value  : 
*/
function getUserFromHeader($request) {   
    $headers            = $request->header();
    $authenticatedToken = $headers['x-access-token'][0];
    $userData           = User::where('auth_token', $authenticatedToken)->first();
    return $userData;
}

/*
    * Function name : dateDiffInDaysNights
    * Purpose       : This function is get user from header
    * Author        :
    * Created Date  :
    * Modified Date : 
    * Input Params  : $request
    * Return Value  : 
*/
function dateDiffInDaysNights($startDateTime, $endDateTime) {
    $startDateTime  = new DateTime($startDateTime);
    $endDateTime    = new DateTime($endDateTime);
    $sinceStart     = $startDateTime->diff($endDateTime);
    $numberOfNights = $startDateTime->setTime(0,0)
                                    ->diff($endDateTime)
                                    ->format("%a");
    // echo $sinceStart->y.' years<br>';
    // echo $sinceStart->m.' months<br>';
    // echo $sinceStart->s.' seconds<br>';
    $numberOfDays   = $sinceStart->d;
    $numberOfHours  = $sinceStart->h;
    $numberOfMinutes= $sinceStart->i;
    $tripDuration   = '';
    if ($numberOfDays != 0 && $numberOfNights != 0) {
        if ($numberOfDays > 1) {
            $tripDuration .= $numberOfDays.' '.trans('custom_api.label_days');
        } else {
            $tripDuration .= $numberOfDays.' '.trans('custom_api.label_day');
        }
        if ($numberOfNights > 1) {
            $tripDuration .= ' '.$numberOfNights.' '.trans('custom_api.label_nights');
        } else {
            $tripDuration .= ' '.$numberOfNights.' '.trans('custom_api.label_night');
        }
    } else if ($numberOfDays != 0 && $numberOfNights == 0) {
        if ($numberOfDays > 1) {
            $tripDuration .= $numberOfDays.' '.trans('custom_api.label_days');
        } else {
            $tripDuration .= $numberOfDays.' '.trans('custom_api.label_day');
        }
    } else if ($numberOfDays == 0 && $numberOfNights != 0) {
        if ($numberOfNights > 1) {
            $tripDuration .= $numberOfNights.' '.trans('custom_api.label_nights');
        } else {
            $tripDuration .= $numberOfNights.' '.trans('custom_api.label_night');
        }
    } else {
        if ($numberOfHours > 1) {
            $tripDuration .= $numberOfHours.' '.trans('custom_api.label_hours');
        } else {
            $tripDuration .= $numberOfHours.' '.trans('custom_api.label_hour');
        }
    }
    return $tripDuration;
}

/*
    * Function Name : generateVerificationCode
    * Purpose       : This function is to generate verification code
    * Author        :
    * Created Date  :
    * Modified date :
    * Input Params  : 
    * Return Value  : 4 digit verification code
*/
function generateVerificationCode() {
    $stringOfNumbers            = '123456789';
    $shuffledStringOfNumbers    = str_shuffle($stringOfNumbers);
    $verificationCode           = substr($shuffledStringOfNumbers, 1, 4);
    return $verificationCode;
}