<?php 

namespace app\Traits;

use Illuminate\Support\Facades\Request;
use Config;
use Log;

trait Utillity {

    public function getData($data) 
    {
        $post = Request::json()->all();
        if (!isset($post) || empty($post))
            $post = Request::all();
        return $post; 
    }

    public function getReponse($status, $code, $result = array('*')) 
    {

        $response['status']  = $status;
        $response['code']    = $code;
        $response['message'] = Config::get('errorcode')[$code];
        $response['body']  = $result;    

        return $response;
    }

    public function errorLogCreate($controllerName,$functionName,$errorType,$messagecode,$postVal,$lineNumber=null){        
        
        // $msg =  array('Function' => $functionName, 'Line No'=>$lineNumber, 'Message' => Config::get('errorcode')[$messagecode], 'Data' => $postVal);
        
        // $className = "Class:".$controllerName;

        // switch ($errorType) {
        //     case 'error':
        //         Log::error($className, $msg);
        //         break;
        //     case 'critical':
        //         Log::critical($className, $msg);
        //         break;
        //     case 'emergency':
        //         Log::emergency($className, $msg);
        //         break;
        //     case 'success':
        //         Log::success($className, $msg);
        //         break;
        //     case 'warning':
        //         Log::warning($className, $msg);
        //         break;   
        //     case 'notice':
        //         Log::notice($className, $msg);
        //         break;     
        //     case 'debug':
        //         Log::debug($className, $msg);
        //         break;
        //     case 'info':  
        //         Log::info($className, $msg);
        //         break;
        //     default:
        //         Log::info($className, $msg);
        //         break;
        // }    

        return TRUE;          
    }

    public function strTimestampToDate($timestamp,$format){
        
        $convertedDate = date($format, strtotime($timestamp)); 
        return $convertedDate;
    }

}