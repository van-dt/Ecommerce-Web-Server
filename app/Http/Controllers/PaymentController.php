<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
        if(Auth::check())
        {
            $id = Auth::id();
        }
        else{
            return response()->json(['status'=>"Login to Continue"]);
        }
        $request->validate([
           
            'productID'=>'required',
            'quantity'=>'required'
        ]
    );
    $status =0;
    $select =0;
    $dataInsert = [
        'userID'=>$id,
        'productID'=>$request->productID,
        'quantity'=>$request->quantity,
        'status'=>$status,
        'select'=>$select
        
    ];
        
    $payments = DB::table('payments')->selectRaw('`userID`,`productID`,sum(quantity) as quantity')->where([['userID','=', $id],['productID','=',$request->productID]])->count();
    if($payments>0)
    { 
        $productID = $request->productID;
        DB::table('payments')->where([['userID','=', $id],['productID','=',$productID]])->update(['quantity'=>$request->quantity]);
    }
    else
    {
    $newPayment = Payment::create($dataInsert); return $newPayment;}
   // echo $dataInsert['photoURL'];
    //eturn $newPayment;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if(Auth::check())
        {
            $id = Auth::id();
        }
        else{
            return response()->json(['status'=>"Login to Continue"]);
        }
        $payments = DB::table('payments')->selectRaw('`userID`,`productID`,`quantity`,`select`')->where(['userID','=', $id],['status','=',0])->get();
        $userName = DB::table('users')->selectRaw('`name`')->where('id', $id)->get();

        foreach($payments as $payment)
        {
            $payment->userName = $userName;
            $payment->productName = DB::table('products')->selectRaw('`pname`')->where('id', $payment->productID)->get();

        }
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
    public function update(Request $request,$productID)
    {
        if(Auth::check())
        {
            $id = Auth::id();
        }
        else{
            return response()->json(['status'=>"Login to Continue"]);
        }
        $request->validate([
            'quantity'=>'required',
            'select'=>'required'
        ]
    );
    DB::table('payments')->where([['userID','=', $id],['productID','=',$productID]])->update([['quantity'=>$request->quantity],['select'=>$request->select]]);

   
   // return new PaymentResource($paymentUpdate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productID)
    {
        if(Auth::check())
        {
            $id = Auth::id();
        }
        else{
            return response()->json(['status'=>"Login to Continue"]);
        }
        
     DB::table('payments')->where([['userID','=', $id],['productID','=',$productID]])->delete();
     return $productID;
        
    }
    public function cartcount()
    {
        $countcart = Payment::where('userID',Auth::id())->where('status',0)->count();
        return response()->json(['count'=>$countcart]);
    }
    public function checkout()
    {
        if(Auth::check())
        {
            $id = Auth::id();
        }
        else{
            return response()->json(['status'=>"Login to Continue"]);
        }
        $checkout = Payment::where([['userID','=',$id],['select','=',1]])->get();
        $user = User::where('id',Auth::id())->first();

       
        foreach($checkout as $item)
        {
            $product= Product::where('id',$item->productID)->first();

            $item->pname = $product->pname;
            $item->userName = $user->name;
            $item->address = $user->address;
        }
        return $checkout;
    }
    public function Purchase(Request $request)
    {
        if(Auth::user()->address==NULL)
        {
            $user = User::where('id',Auth::id())->first();
            $user->address= $request->input('address');
            $user->update();
        }
      Payment::where([['userID','=',Auth::id()],['select','=',1]])->update(['status'=>1]);


    }
}
