<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getUserDetail($id)
    {
        Log::info($id);
        $user = User::where('id',$id)->first();
        return $user;
    }
}