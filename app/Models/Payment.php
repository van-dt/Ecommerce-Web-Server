<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'userID',
        'productID',
        'quantity',
        'status'
    ];
    public $timestamps = true;
    public function product()
    {
        return $this->belongsTo(Category::class,'productID','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'userID','id');
    }
}
