<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            // Validate the value...
            $posts=Post::with('tags:id,name','user:id,name')->orderBy('updated_at','desc')->get();


            return response( [
                'posts' => $posts,
            ]);
        } catch (Throwable $e) {
            report($e);

            return false;
        }
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
        try {
            $request->validate([
                'text' =>  ['required','min:1'],
            ]);
            $post= new Post();
            $post->text=$request->text;
            $post->user_id= Auth::user('api')->id;
            $post->save();
            if($request->tags)
            {
                foreach($request->tags as $tagName){
                    if($tagName)
                    {
                        $tag=Tag::where('name',$tagName)->first();
                        if(!$tag){
                            $tag=new Tag();
                            $tag->name=$tagName;
                            $tag->save();
                        }
                        $post->tags()->attach($tag->id);
                    }
                }
            }
            $post->save();
            return response( [
                'post' => $post
            ]);
        } catch (Throwable $e) {
            report($e);

            return false;
        }

    }
    public function getPostsByUser($tagName)
    {
        try {
            $tag=Tag::where('name',$tagName)->with('posts.user:id,name','posts.tags:id,name')->first();
            return response( [
                'tag' => $tag
            ]);
        } catch (Throwable $e) {
            report($e);

            return false;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $post=Post::findOrFail($id);
            return response( [
                'post' => $post
            ]);
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'text' =>  ['required','min:1'],
        ]);
        try {
            $post=Post::where('id',$id)->with('tags')->first();
            if(!$post)return response( [
                'message' => 'No Post Found !',
            ]);
            $post->text=$request->text;
            if($request->tags)
            {
                if($post->tags)
                {
                   $post->tags()->detach();
                }
                foreach($request->tags as $tagName){
                    if($tagName)
                    {
                        $tag=Tag::where('name',$tagName)->first();
                        if(!$tag){
                            $tag=new Tag();
                            $tag->name=$tagName;
                            $tag->save();
                        }
                        $post->tags()->attach($tag->id);
                    }
                }
            }
            $post->save();

            return response( [
             'post' => $post
            ]);
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
