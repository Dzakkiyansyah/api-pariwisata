<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_path',
        'user_id',
    ];

    /**
     * Mendapatkan data user (admin) yang menulis berita ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
