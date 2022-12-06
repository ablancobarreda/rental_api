<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\MessageHelp;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    //section Get_User_By_Token_Rentalho
    protected function getUserByToken($token)
    {
        $response = Http::withToken($token)->acceptJson()->get("https://apitest.rentalho.com/api/oauth/token-info");

        return json_decode($response->getBody()->getContents(), true);
    }

    //section Get_Token_User
    public function getTokenUser(Request $request){

        $userDriver = $this->getUserByToken($request->token);

        if ($user = User::whereEmail($userDriver['data']['email'])->first()) {

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                'code' => 'ok',
                'message' => 'User logged in',
            ]);

        } else {
            $user = User::create([
                'name' => $userDriver['data']['name'],
                'last_name' => $userDriver['data']['name'],
                'email' => $userDriver['data']['email'],
                'phone' => $userDriver['data']['mobile'],
                //'avatar' => $userDriver->avatar,
                'password' => Hash::make($userDriver['data']['id']),
                'email_verified_at' => now(),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                'code' => 'ok',
                'message' => 'User registered',
            ]);

        }


        return response()->json(
            [
                'code' => 'ok',
                'message' => 'TOKEN',
                'token' => $request->token,
                'data' =>  $userDriver['data']['email']
            ]
        );

    }

    //section Get_Users
    public function getUsers(){

        $users = User::all();

        return response()->json(
            [
                'code' => 'ok',
                'message' => 'Users',
                'users' => $users
            ]
        );
    }

    //section Get_User
    public function getUserById(Request $request){

        $user = User::whereId($request->userId)->first();

        if($user){
            return response()->json(
                [
                    'code' => 'ok',
                    'message' => 'User',
                    'user' => $user
                ]
            );
        }

        return response()->json(
            [
                'code' => 'error',
                'message' => 'User not found'
            ]
        );
    }

    //section New_User
    public function newUser(NewUserRequest $request){

        try{
            DB::beginTransaction();
            $user = new User();

            $user->name = $request->userName;
            $user->last_name = $request->userLastName;
            $user->phone = $request->userPhone;
            $user->email = $request->userEmail;
            $user->password = Hash::make($request->userPassword);
            if ($request->hasFile('userAvatar')) {
                $user->avatar = self::uploadImage($request->userAvatar, $request->userName);
            }

            $user->save();

            DB::commit();

            return response()->json(
                [
                    'code' => 'ok',
                    'message' => 'User created successfully'
                ]
            );
        }
        catch(\Throwable $th){
            return response()->json(
                ['code' => 'error', 'message' => $th->getMessage()]
            );
        }
    }

    //section Update_User
    public function updateUser(UpdateUserRequest $request){
        try{
            DB::beginTransaction();

            $user = User::whereId($request->userId)->first();

            $user->name = $request->userName;
            $user->last_name = $request->userLastName;
            $user->phone = $request->userPhone;
            $user->email = $request->userEmail;

            if($request->userPassword){
                $user->password = Hash::make($request->userPassword);
            }
            if ($request->hasFile('userAvatar')) {
                $user->avatar = self::uploadImage($request->userAvatar, $request->userName);
            }

            $user->update();

            DB::commit();

            return response()->json(
                [
                    'code' => 'ok',
                    'message' => 'User updated successfully'
                ]
            );
        }
        catch(\Throwable $th){
            return response()->json(
                ['code' => 'error', 'message' => $th->getMessage()]
            );
        }
    }

    // section Delete_User
    public function deleteUser(Request $request){
        try {
            DB::beginTransaction();
            $result = User::whereId($request->userId)->delete();

            DB::commit();

            if($result){
                return response()->json(
                    [
                        'code' => 'ok',
                        'message' => 'User deleted successfully'
                    ]
                );
            }

            return response()->json(
                [
                    'code' => 'error',
                    'message' => 'User not found'
                ]
            );

        }
        catch(\Throwable $th){
            return response()->json(
                ['code' => 'error', 'message' => $th->getMessage()]
            );
        }
    }

    // section Send_Help_Message
    public function sendHelpMessage(Request $request){
        try {
            DB::beginTransaction();

            $settings = Setting::first();

            $date = now()->format('d-m-Y');

            Mail::to($settings->email)->send(new MessageHelp($request->message, $request->email, $request->name, $date));

            DB::commit();

            return response()->json(
                [
                    'code' => 'ok',
                    'message' => 'Help message sended successfully'
                ]
            );

        }
        catch(\Throwable $th){
            return response()->json(
                ['code' => 'error', 'message' => $th->getMessage()]
            );
        }
    }

    //section Upload_image
    public static function uploadImage($path, $name){
        $image = $path;

        $avatarName =  $name . substr(uniqid(rand(), true), 7, 7) . '.webp';

        $img = Image::make($image->getRealPath())->encode('webp', 50)->orientate();

        $img->resize(null, 300, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->stream();

        Storage::disk('public')->put('/userImages' . '/' . $avatarName, $img, 'public');

        $path = '/userImages/' . $avatarName;

        return $path;
    }


}
