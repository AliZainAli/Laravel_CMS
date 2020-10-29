<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use App\User;

use App\Http\Requests\Posts\CreatePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{

    public function __construct()
    {
        $this->middleware('VerifyCategoriesCount')->only(['create', 'store']);
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.index')->with('posts', Post::where('user_id', '=', Auth::user()->id)->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create')->with('categories', Category::all())->with('tags', Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        // upload image to storage
        $image = $request->image->store('posts');

        // create the post
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'image' => $image,
            'published_at' => $request->published_at,
            'user_id' => auth()->user()->id,
            'category_id' => $request->category
        ]);

        if ($request->tags) {
            $post->tags()->attach($request->tags);
        }

        // flash message
        session()->flash('success', 'Post ( '. $request->title .' )  Created Successfully');

        // redirect
        return redirect( route('posts.index') );
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
    public function edit(Post $post)
    {
        return view('posts.create')->with('post', $post)->with('categories', Category::all())->with('tags', Tag::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        // data to be updated ( more secure way ) 
        $data = $request->only('title', 'description', 'content', 'published_at');

        // check for new image
        if ($request->hasFile('image')) {
            // upload it to storage
            $image = $request->image->store('posts');
            // delete old one
            $post->deleteImage();
            $data['image'] =  $image ;
        }

        if ($post->tags) {
            $post->tags()->sync($request->tags);
        }

        // update 
        $post->update($data);

        // flash message
        session()->flash('success', 'Post Updated successfully');

        // return view
        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::withTrashed()->where('id', $id)->firstOrFail();
        if ($post->trashed()) {
            $post->deleteImage();
            $post->forceDelete();
            session()->flash('success', 'Post ( ' . $post->title . ' )  Deleted Successfully');
        }
        else {
            $post->delete();
            session()->flash('success', 'Post ( ' . $post->title . ' )  Trashed Successfully');
        }

        return redirect(route('posts.index'));
    }

    /**
     * display a list of all trashed posts
     *
     * @return \Illuminate\Http\Response
     */
    public function trashed()
    {
        $trashed = Post::onlyTrashed()->get();

        return view('posts.index')->withPosts($trashed);
        // alternative to with Posts
        // return view('posts.index')->with('posts', $trashed);
    }

    public function restore($id)
    {
        // find post by id
        $post = Post::withTrashed()->where('id', $id)->firstOrFail();

        $post->restore();

        session()->flash('success', 'Post ( '. $post->name .' ) restored successfully');

        return redirect()->back();
    }
}
