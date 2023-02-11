<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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
    public function store(Request $request)
    {

        Auth::check() ? Auth::user()->id : null;
        $id = Auth::id();
        $rule= array(
            'pname'=>'required',
             'description'=>'required',
             'quantity'=>'required|integer|min:1',
             'price'=>'required|integer|min:0',
             'photoURL'=>'required|image|max:2000',
             'cate_id'=>'required|integer',
    );
    $validator =  Validator::make($request->all(),$rule);
    if($validator->fails())
    {
            return $validator->errors();
    }


    $path = Storage::disk('s3')->put('images/originals', $request->photoURL, 'public');
    $dataInsert = [
        'pname'=>$request->pname,
        'description'=>$request->description,
        'quantity'=>$request->quantity,
        'price'=>$request->price,
        'photoURL'=>$path,
        // 'userID'=>$request->userID,
        'userID'=>$id,
        'cate_id'=>$request->cate_id
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
        $productUpdate = Product::find($id);
       // dd($request);
       Auth::check() ? Auth::user()->id : null;
       $userid = Auth::id();
        $rule= array(
            'pname'=>'required',
             'description'=>'required',
             'quantity'=>'required|integer|min:1',
             'price'=>'required|integer|min:0',
             'photoURL'=>'required|image|max:2000',
             'cate_id'=>'required|integer',
             'userID'=>'required|integer|min:0'

    );
    $validator =  Validator::make($request->all(),$rule);
    if($validator->fails())
    {
            return $validator->errors();
    }
    $path = Storage::disk('s3')->put('images/originals', $request->photoURL, 'public');
    $dataInsert = [
        'pname'=>$request->pname,
        'description'=>$request->description,
        'quantity'=>$request->quantity,
        'price'=>$request->price,
        'photoURL'=>$path,
        'userID'=> $userid,
        'cate_id'=>$request->cate_id
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