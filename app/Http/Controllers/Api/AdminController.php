<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|unique:admins',
            'password' => 'required|confirmed',
            'phone' => 'required|unique:admins',
        ]);
        $admin = Admin::create([
            'name' => $data['name'],
            'password' => bcrypt($data['password']),
            'email' => $data['email'],
            'phone' => $data['phone'],
        ]);

        return response()->json([
            'message' => "Registered Successfully",
            'user' => AdminResource::collection(Admin::query()->where('id',$admin->id)->get()),

            'token' => $admin->createToken('Api Admin Token')->accessToken,
        ]);
    }
    public function login(Request $request)
    {

        $data = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $admin = Admin::where('email', $request->email)->first();
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'msg' => "Cardetalsd doesn't match",
            ],401);
        }

        return response()->json([
            'msg' => "login Successfully",
            'admin' => AdminResource::collection(Admin::query()->where('id',$admin->id)->get()),
            'token' => $admin->createToken('customer')->accessToken
        ]);

    }

    public function logout(){
//        dd("5");
        auth()->user()->token()->revoke();
        return response()->json([
            'msg'=>'Sign out successfully'
        ],200);
    }

    public function me(){

        return response()->json([
           'data' => AdminResource::collection(Admin::query()->where('id',auth()->user()->id)->get())
        ]);
    }

}
