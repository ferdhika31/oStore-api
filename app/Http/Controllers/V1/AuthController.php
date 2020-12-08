<?php

namespace App\Http\Controllers\V1;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Validator;

class AuthController extends BaseApiController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        try {
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

            $user = User::create($req);

            $user['token'] = $user->createToken('oStoreApp')->accessToken;

            return $this->returnData($user, "Register successfully.");
        } catch (\Throwable $t) {
            return $this->returnError($t);
        }
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function login(Request $request)
    {
        if (auth()->attempt($request->only(['email', 'password']))) {
            $token = auth()->user()->createToken('oStoreApp')->accessToken;
            return $this->returnData(['token' => $token], "Register successfully.");
        } else {
            return $this->returnStatus(false, "Login Failed.", 400);
        }
    }
}
