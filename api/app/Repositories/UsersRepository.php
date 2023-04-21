<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Traits\Utillity;
use App\Services\UsersService;
use App\Models\Users;
use Carbon\Carbon;
class UsersRepository
{
    use Utillity;

    public function __construct(
        UsersService $UsersService)
    {
        $this->UsersService = $UsersService;
    }

    public function getUsersListData(){
        try {
                $userList = $this->UsersService->getUsersListData();
                // check access token status
                if($userList['status'] !=400){
                    $response = $this->getReponse(TRUE, 'Success', $userList);
                }else{
                    $response = $this->getReponse(FALSE, 4, 'Error in get record');
                }
        }    
        catch (\Exception $e) {
            $this->errorLogCreate(class_basename(get_class($this)), 'getUsersListData' , 'error', 'TRY_CATCH_ERROR', $e->getTraceAsString(),__LINE__);
            $response = $this->getReponse(FALSE, 3, $e->getMessage());
        }
        return $response;
    }

    public function create($param){
        try {
            //check parameter
            if($param){
                $userData = $this->UsersService->create($param);
                // check access token status
                if($userData['status'] !=400){
                    $response = $this->getReponse(TRUE, 'Success', $userData);
                }else{
                    $response = $this->getReponse(FALSE, 4, $userData);
                }
            }else{
                $response = $this->getReponse(FALSE, 4, 'Required error.');
            }
               
        }    
        catch (\Exception $e) {
            $this->errorLogCreate(class_basename(get_class($this)), 'create' , 'error', 'TRY_CATCH_ERROR', $e->getTraceAsString(),__LINE__);
            $response = $this->getReponse(FALSE, 3, $e->getMessage());
        }
        return $response;
    }
}