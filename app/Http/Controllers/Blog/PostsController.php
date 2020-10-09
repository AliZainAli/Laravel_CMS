<?php

namespace App\Http\Controllers\Blog;

use App\Category;
use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Http\Request;

use function GuzzleHttp\Promise\queue;

class PostsController extends Controller
{
    public function show(Post $post)
    {
        return view('blog.show')->with('post', $post);
    }


    public function category(Category $category)
    {
        return view('blog.category')
        ->with('category', $category)
        ->with('posts',$category->posts()->searched()->simplepaginate(2))
        ->with('categories', Category::all())
        ->with('tags', Tag::all());
    }


    public function tag(Tag $tag)
    {
        return view('blog.tag')
        ->with('tag', $tag)
        ->with('posts', $tag->posts()->searched()->simplepaginate(2))
        ->with('tags', Tag::all())
        ->with('categories', Category::all());
    }
}
