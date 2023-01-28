<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::paginate(15);
        
        return ProductResource::collection($products);
   // $pr=  DB::select('select * from products where userID =?',[73]);

       
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
    public function store(Request $request,$id)
    {
        //
        $request->validate([
            'pname'=>'required',
             'description'=>'required',
             'quantity'=>'required|integer|min:1',
             'price'=>'required|integer|min:0',
             'photoURL'=>'required|image|max:2000'
        ],
        [
            'pname.reyuired'=>'Phải điền tên sản phẩm',
            'description.required'=>"Phải điền mô tả",
            'quantity.required'=>'Phải điền số lượng',
            'quantity.integer'=> 'Số lượng phải nguyên',
            'quantity.min'=>'số lượng phải lớn hơn 0',
            'price.required'=>'Phải có giá',
            'price.integer'=>'giá tiền phải là số nguyên',
            'price.min'=> 'giá tiền không thể là số âm',
            'photoURL.required'=>'Cần có ảnh mô tả',
            'photoURL.max'=>'Ảnh quá lớn',
            'photoURL.image'=>'Phải là ảnh'
        ]
    );

    $path = Storage::disk('s3')->put('images/originals', $request->photoURL, 'public');
    $dataInsert = [
        'pname'=>$request->pname,
        'description'=>$request->description,
        'quantity'=>$request->quantity,
        'price'=>$request->price,
        'photoURL'=>$path,
        'userID'=>$id
    ];
    $newProduct = Product::create($dataInsert);
   // echo $dataInsert['photoURL'];
    return $newProduct;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
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
        $request->validate([
            'pname'=>'required',
             'description'=>'required',
             'quantity'=>'required|integer|min:1',
             'price'=>'required|integer|min:0',
             'photoURL'=>'required|image|max:2000',
             'userID'=>'required|integer|min:0'
        ],
        [
            'pname.reyuired'=>'Phải điền tên sản phẩm',
            'description.required'=>"Phải điền mô tả",
            'quantity.required'=>'Phải điền số lượng',
            'quantity.integer'=> 'Số lượng phải nguyên',
            'quantity.min'=>'số lượng phải lớn hơn 0',
            'price.required'=>'Phải có giá',
            'price.integer'=>'giá tiền phải là số nguyên',
            'price.min'=> 'giá tiền không thể là số âm',
            'photoURL.required'=>'Cần có ảnh mô tả',
            'photoURL.max'=>'Ảnh quá lớn',
            'photoURL.image'=>'Phải là ảnh'
        ]
    );

    $productUpdate = Product::find($id);
    $path = Storage::disk('s3')->put('images/originals', $request->photoURL, 'public');
    $dataInsert = [
        'pname'=>$request->pname,
        'description'=>$request->description,
        'quantity'=>$request->quantity,
        'price'=>$request->price,
        'photoURL'=>$path,
        'userID'=>$request->userID
    ];
    $productUpdate->update($dataInsert);
    return new ProductResource($productUpdate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productDelete = Product::find($id);
        $productDelete->delete();
        return $id;
    }
}