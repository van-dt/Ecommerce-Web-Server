<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    /* Fillable */
    protected $fillable = [
        'title', 'path', 'auth_by', 'size'
    ];

    /* @array $appends */
    public $appends = ['url', 'uploaded_time', 'size_in_kb'];

    public function getUrlAttribute()
    {
        $url = Storage::temporaryUrl($this->path);
        return $url;
    }
    public function getUploadedTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }
    public function getSizeInKbAttribute()
    {
        return round($this->size / 1024, 2);
    }
}
