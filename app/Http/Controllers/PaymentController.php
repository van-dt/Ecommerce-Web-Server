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
use Illuminate\Support\Facades\Log;

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
        Log::info(Auth::id());
        if(Auth::check())
        {
            $id = Auth::id();
        }
        else{
            return response()->json(['error'=> true,'message'=>"Login to Continue"]);
        }
        $request->validate([

            'productID'=>'required',
            'quantity'=>'required'
        ]
    );
    $status =1;
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
        $payments = DB::table('payments')->selectRaw('`id`,`userID`,`productID`,`quantity`,`select`,`status`')->where('userID','=', $id)->where('status','=',1)->get();
        $userName = DB::table('users')->selectRaw('`name`')->where('id', $id)->first();

        foreach($payments as $payment)
        {
            $payment->userName = $userName;
            $payment->product = DB::table('products')->selectRaw('`id`,`pname`,`description`,`price`,`quantity`,`photoURL`')->where('id', $payment->productID)->first();
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
    public function update(Request $request,$id)
    {
        if(Auth::check())
        {
            $userId = Auth::id();
        }
        else{
            return response()->json(['status'=>"Login to Continue"]);
        }
        $request->validate([
            'quantity'=>'required',
        ]
    );
    DB::table('payments')->where('id','=',$id)->update(['quantity'=>$request->quantity]);


        return response()->json(['success'=> true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::check())
        {
            $userid = Auth::id();
        }
        else{
            return response()->json(['status'=>"Login to Continue"]);
        }

     DB::table('payments')->where('id','=', $id)->delete();
     return response()->json(['success'=> true]);

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
            return response()->json(['error'=>true,'message'=>"Login to Continue"]);
        }
        $checkout = Payment::where('userID','=',$id)->get();
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
        if(Auth::check())
        {
            $id = Auth::id();
        }
        else{
            return response()->json(['error'=>true,'message'=>"Login to Continue"]);
        }
        if(Auth::user()->address!=$request->address)
        {
            $user = User::where('id','=',Auth::id())->first();
            Log::info($user);
            $user->address= $request->address;
            $user->update();
        }
      Payment::where('userID','=',Auth::id())->update(['status'=>2]);
      return response()->json(['success'=> true]);

    }
}
