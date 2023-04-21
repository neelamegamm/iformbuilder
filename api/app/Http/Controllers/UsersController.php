<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repositories\UsersRepository;
use App\Traits\Utillity;
use Validator;
use Config;
class UsersController extends Controller
{
    use Utillity;

    public function __construct(UsersRepository $UsersRepository)
    {
        $this->UsersRepository = $UsersRepository;
    }

    
    /**
     * 
     * Get User Data
     * 
     * @method get
     * @return \Illuminate\Http\Response json data
     * 
     */
    public function getUsersListData()
    {
        return $this->UsersRepository->getUsersListData();
    }

  /**
     * 
     * Create User
     * 
     * @method post
     * @param  \Illuminate\Http\Request  $request data
     * @return \Illuminate\Http\Response json data
     * 
     */

    public function create()
    {
        $params = $this->getData($_REQUEST);
        $validator = Validator::make($params, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'designation' => 'required',
            'zip_code' => 'required',
            'date_of_birth' => 'required',
            'subscribe' => 'required',
            'comments' => 'required',
        ]);

        //check valudation
        if ($validator->fails()) {
            $return = $this->getReponse(FALSE, 4, $validator->messages());
            return response()->json($return);
        }
        return $this->UsersRepository->create($params);
    }
}
