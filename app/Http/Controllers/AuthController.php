<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use Authenticatable;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $token = $user->createToken('my-app-token');

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    public function register(Request $request)
    {
        $data = $request->validate([
            'username' =>  ['required', 'max:255'],
            'email' => ['required','unique:users', 'max:255','email'],
            'password' =>  ['required', 'min:6' ,'max:255'],
        ]);

        $user=new User();
        $user->name=$request->username;
        $user->email=$request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $token = $user->createToken('my-app-token');
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

}
