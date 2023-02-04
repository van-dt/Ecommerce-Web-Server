<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index()
    {
        //
        $payments = Payment::paginate(15);
        
        return PaymentResource::collection($payments);
   

       
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
            'userID'=>'required',
            'productID'=>'required',
            'quantity'=>'required'
        ]
    );
    $status =0;
    $dataInsert = [
        'userID'=>$request->userID,
        'productID'=>$request->productID,
        'quantity'=>$request->quantity,
        'status'=>$status
        
    ];
    $newPayment = Payment::create($dataInsert);
   // echo $dataInsert['photoURL'];
    return $newPayment;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payments = DB::table('payments')->selectRaw('`userID`,`productID`,sum(quantity) as quantity')->where('userID', $id)->groupBy('productID')->get();
        return new PaymentResource($payments);
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
        $payment = Payment::findOrFail($id);
        return new PaymentResource($payment);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'userID'=>'required',
            'productID'=>'required',
            'quantity'=>'required',
            'status'=>'required'
        ]
    );
    $userID= $request->userID;
    $productID = $request->productID;
    DB::table('payments')->where([['userID','=', $userID],['productID','=',$productID]])->update(['quantity'=>$request->quantity]);

   
   // return new PaymentResource($paymentUpdate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $rule = array(
            'userID'=>'required',
            'productID'=>'required',
    );
    $validator =  Validator::make($request->all(),$rule);
    if($validator->fails())
    {
            return $validator->errors();
    }
    $userID= $request->userID;
    $productID = $request->productID;
     DB::table('payments')->where([['userID','=', $userID],['productID','=',$productID]])->delete();
        
    }
}
