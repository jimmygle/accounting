<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Category;

class CategoriesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Category $categories
     * @return \Illuminate\Http\Response
     */
    public function index(Category $categories)
    {
        $categories = $categories->orderBy('name')->with('transactions')->get();
        return view('categories.index')->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/categories');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories',
            'slug' => 'required|unique:categories',
        ]);

        $input = $request->except(['_token']);
        $category->fill($input)->save();

        return redirect('categories')->with('success', $category->name . ' category successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function show($slug, Category $category)
    {
        $category = $category->where('slug', '=', $slug)->firstOrFail();
        return view('categories.showOrUpdate')->with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        return redirect('categories/' . $slug);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Category $category
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category, $slug)
    {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required',
        ]);

        $input = $request->except(['_token', '_method']);
        $category = $category->where('slug', '=', $slug)->firstOrFail();
        $category->fill($input)->save();

        return redirect('categories/' . $category->slug)->with('success', 'Category successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, $slug)
    {
        $category = $category->where('slug', '=', $slug)->firstOrFail();
        $categoryName = $category->name;
        $category->delete();
        return redirect('categories')->with('success', 'Successfully deleted "' . $categoryName . '" category.');
    }
}
