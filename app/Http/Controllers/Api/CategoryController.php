<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = auth()->user()->categories()->with('tasks')->paginate(100);

        return  CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedDate = $request->validate([
            'title' => 'required'
        ]);

        return auth()->user()->categories()->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        if(auth()->id() != $category->user_id){
            return response()->json(['message' => 'You don\'t own this resource'], 401);
        }

        $category->load('tasks');
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {

        if(auth()->id() != $category->user_id){

            return response()->json(['message' => 'You don\'t own this resource'], 401);

        }

        $validatedData = $request->validate([
            'title' => 'required',
        ]);

        if($category->update($request->all())){
            return response()->json(['message' => 'updated']);
        }else{
            return response()->json(['message' => 'error'], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if(auth()->id() != $category->user_id){

            return response()->json(['message' => 'You don\'t own this resource'], 401);

        }

        if($category->delete()){
            return response()->json(['message' => 'Resources deleted']);
        }

        return response()->json(['message' => 'Error, try again later'], 500);
    }

    public function restore($categoryId){

        $category = Category::withTrashed()->findOrFail($categoryId);

        if(auth()->id() != $category->user_id){

            return response()->json(['message' => 'You don\'t own this resource'], 401);

        }

        if($category->restore()){
            return ['message' => 'Restored resource successfully'];
        }

        return response()->json(['message' => 'You have an error'], 500);

    }

    public function forceDelete($categoryId){

        $category = Category::withTrashed()->findOrFail($categoryId);

        if(auth()->id() != $category->user_id){

            return response()->json(['message' => 'You don\'t own this resource'], 401);

        }

        if($category->forceDelete()){
            return response()->json(['message' => 'Resources deleted']);
        }

        return response()->json(['message' => 'Error, try again later'], 500);

    }
}
