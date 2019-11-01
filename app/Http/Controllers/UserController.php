<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use Hash;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $data = new User();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = Hash::make($request->password);
            $data->api_token = str_random(50);
            $data->role_id = $request->role_id;
            $data->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal disimpan',
                'error' => $e
            ]);
        }
       
    }

    public function login(Request $request)
    {
        $datauser = User::where('email', $request->email)->first();
        if($datauser){
            if(Hash::check($request->password, $datauser->password)){
                $token = $datauser->generateToken();

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'data' => $datauser
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Email dan password tidak ditemukan',
                    'data' => null
                ]);
            }
        }
    }

    public function getUser (Request $request, $id){
        $user_data = Auth::user(); 

        if($request->api_token == $user_data['api_token']){
           $data = User::where('id', $id)->first();

           return new UserResource($data);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ]);
        }
    }
}
