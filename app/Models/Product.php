<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'pname',
        'description',
        'quantity',
        'price',
        'cate_id',
        'userID',
        'photoURL'
    ];
    public $timestamps = true;
    public function category()
    {
        return $this->belongsTo(Category::class,'cate_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'userID','id');
    }
}
