<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $currentLang;
    private $setLang;
    public $websiteLanguages;
    
    public function __construct() {
        $this->websiteLanguages = config('global.WEBSITE_LANGUAGE');   
        \View::share(['websiteLanguages' => $this->websiteLanguages]);

        $segmentValue = \Request::segment(1);
        if ($segmentValue) {
            if (array_key_exists($segmentValue, $this->websiteLanguages)) {
                Session::put('websiteLang', '');
                Session::put('websiteLang', $segmentValue);
                \App::setLocale($segmentValue);
            } else {
                Session::put('websiteLang', '');
                Session::put('websiteLang', \App::getLocale());
                \App::setLocale(\App::getLocale());
            }
        }
    }

    /*
        * Function Name : generateNotifyMessage
        * Purpose       : This function is to generate Notify message
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : $type, $validationFailedMessages, $extraTitle = false
        * Return Value  : Mixed
    */
    public function generateNotifyMessage($type, $validationFailedMessages) {
        switch($type) {
            case 'success':
                notyf()
                    ->position('x', 'right')
                    ->position('y', 'top')
                    ->duration(3000)
                    ->ripple(true)
                    ->dismissible(true)
                    ->addSuccess($validationFailedMessages);
                break;
            case 'warning':
                notyf()
                    ->position('x', 'right')
                    ->position('y', 'top')
                    ->duration(3000)
                    ->ripple(true)
                    ->dismissible(true)
                    ->addWarning($validationFailedMessages);
                break;
            case 'error':
                notyf()
                    ->position('x', 'right')
                    ->position('y', 'top')
                    ->duration(3000)
                    ->ripple(true)
                    ->dismissible(true)
                    ->addError($validationFailedMessages);
                break;
            default:
                notyf()
                    ->position('x', 'right')
                    ->position('y', 'top')
                    ->duration(3000)
                    ->ripple(true)
                    ->dismissible(true)
                    ->addInfo($validationFailedMessages);
        }
    }

    /*
        * Function Name : getRandomPassword
        * Purpose       : This function is to generate random password
        * Author        :
        * Created Date  :
        * Modified date :
        * Input Params  : $type, $validationFailedMessages, $extraTitle = false
        * Return Value  : Mixed
    */
    public function getRandomPassword($stringLength = 8) {
        $capitalRandom = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ", 2)), 0, 2);
        $smallRandom   = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 3)), 0, 3);
        $specailRandom = substr(str_shuffle(str_repeat("!@#$%^&*", 1)), 0, 1);
        $numberRandom  = substr(str_shuffle(str_repeat("0123456789", 1)), 0, 2);
        
        $randonString = $capitalRandom.$smallRandom.$specailRandom.$numberRandom;

        return $randonString;
    }

}
