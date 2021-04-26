<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);
//        dd($user);

        $token = $user->createToken('API Token')->accessToken;

        return response()->json([
            'msg'=>'Registered Successfully',
            'user' => UserResource::collection(User::query()->where('id',$user->id)->get()),
            'token' => $token
        ],200);

    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            return response()->json([
                'error_message' => 'Incorrect Details.Please try again',
            ],500);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;
        return response([
            'user' => UserResource::collection(User::query()->where('id',auth()->user()->id)->get()),
            'token' => $token
        ]);

    }
    function signout(){
        auth()->user()->token()->revoke();
        return response()->json([
            'msg'=>'Sign out successfully'
        ],200);
    }

    function updateProfile(Request $request){
        $request->validate([
            'name' => 'required',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->save();
        return response()->json([
            'msg'=>'Profile Updated Successfully',
            'data' => UserResource::collection(User::query()->where('id',$user->id)->get())
        ],200);
    }

    public function editPassword()
    {
        $data = \request()->validate([
            'password' => 'required|confirmed',
        ]);
        $user = User::find(\auth()->user()->id);
        if ($user->password == \request()->old){
            dd("1");
        }
        $requestData = \request()->all();
        if(\request()->password == \request()->password_confirmation){
            $requestData['password'] = bcrypt($requestData['password']);
        }
        else{
            unset($requestData['password']);
        }
        $user->update($requestData);
        return response()->json([
            'message' => 'User Updated Password Successfully',
            'data' => UserResource::collection(User::query()->where('id',$user->id)->get())
        ], 200);
    }
}
