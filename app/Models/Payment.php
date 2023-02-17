<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table='payments';

    protected $fillable = [
        'userID',
        'productID',
        'quantity',
        'status',
        'select',
    ];
    // public $timestamps = true;

    public function product()
    {
        return $this->belongsTo(Product::class,'productID','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'userID','id');
    }
}
