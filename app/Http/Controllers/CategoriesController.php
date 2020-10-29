<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;

use Illuminate\Http\Request;
use App\Http\Requests\CategoriesRequests\CreateCategoryRequest;
use App\Http\Requests\CategoriesRequests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Contracts\Service\Attribute\Required;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('categories.index')->with('categories', Category::all())->with('posts', Post::where('user_id', '=', Auth::user()->id)->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $this->validate($request, [
            'name'=>'required|unique:categories|min:3|max:18'
        ]);

        /*
        $data = request()->all();
        $category = new Category();
        $category->name = $data['name'];
        $category->save();
        */
        Category::create([
            'name'=> $request->name
        ]);

        session()->flash('success', 'Category ( '. $request->name . ' ) created successfully ');

        return redirect(route('categories.index'));
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
    public function edit(Category $category)
    {
        return view('categories.create')->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {

        $oldname = $category->name;
        
        $category->update([
            'name' => $request->name
        ]);

        session()->flash('success', 'Category ( '. $oldname .' ) updated to ( '. $category->name  .' ) successfully');

        return redirect(route('categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ( $category->posts->count() > 0 ) {
            session()->flash('error', 'Category ( ' . $category->name . ' ) can not be deleted because it has some posts');
            return redirect()->back();
        }
        
        $category->delete();

        session()->flash('success', 'Category ( '.$category->name.' ) deleted successfully');

        return redirect(route('categories.index'));
    }
}
