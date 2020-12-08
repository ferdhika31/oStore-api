<?php

namespace App\Http\Controllers\V1;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Validator;

class UserController extends BaseApiController
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = User::search($request)->get();

        return $this->returnData($data, "Data retrieved.");
    }

    /**
     * @param $id
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function show($id)
    {
        $data = User::find($id);

        if(empty($data)){
            return $this->returnStatus(false, "User not found.", 404);
        }

        return $this->returnData($data, "Data retrieved.");
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return $this->returnData(['errors' =>  $validator->errors()], "Validation errors.", 422);
        }

        $req = $request->only(['name', 'email']);
        $req['password'] = bcrypt($request->password);

        // create user
        $user = User::create($req);

        return $this->returnData($user, "User created.");
    }

}