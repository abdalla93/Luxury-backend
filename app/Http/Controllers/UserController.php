<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User ;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // return $request;
        $validatedData = $request->validate([
            'user_name' =>  ['required', 'max:255'],
            'email' => ['required','unique:users', 'max:255','email'],
            'password' =>  ['required', 'min:6' ,'max:255'],
        ]);
        try {
            $user=new User();
            $user->user_name=$request->user_name;
            $user->email=$request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => true,
                'created' => true,
                'data' => [
                    'id' => $user->id
                ]
            ]);
        } catch (Throwable $e) {
            report($e);

            return false;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validatedData = $request->validate([
            'user_name' =>  ['required', 'max:255'],
            'email' => ['required', 'max:255','email'],
            'password' =>  ['required', 'max:255'],
            'role_id' =>  ['required', 'max:255'],
        ]);
        try {
            $user=User::findOrFail($id);
            $user->user_name=$request->user_name;
            $user->email=$request->email;
            $user->role_id=$request->role_id;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => true,
                'created' => true,
                'data' => [
                    'id' => $user->id
                ]
            ]);
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::findOrfail($id);
        $user->delete();
        return $this->successResponse();
    }
    public function getUserPosts()
    {
        try {
            $user_id= Auth::user('api')->id;
            $posts=Post::where('user_id',$user_id)->with('tags:id,name')->orderBy('updated_at','desc')->get();
            if($posts){
                return response( [
                    'posts' => $posts,
                ]);
            }
            return response( [
                'message' => 'No Posts for this user',
            ]);
        } catch (Throwable $e) {
            report($e);

            return false;
        }

    }
}
