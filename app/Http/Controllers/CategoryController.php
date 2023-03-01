<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index()
    {
        //
        $categories = Category::paginate(15);
        
        return CategoryResource::collection($categories);
   

       
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
        $request->validate([
            'name'=>'required'
        ]
    );
    $dataInsert = [
        'name'=>$request->name
        
    ];
    $newCategories = Category::create($dataInsert);
   // echo $dataInsert['photoURL'];
    return $newCategories;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return new CategoryResource($category);
       // $product = Product::where('id', '=', $id)->get();
       // return ProductResources::collection($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return new CategoryResource($category);

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
        $request->validate([
            'name'=>'required'
        ]
    );

    $categoryUpdate = Category::find($id);
    $dataInsert = [
        'name'=>$request->name
        
    ];
    $categoryUpdate->update($dataInsert);
    return new CategoryResource($categoryUpdate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categoryDelete = Category::find($id);
        $categoryDelete->delete();
        return $id;
    }
}
