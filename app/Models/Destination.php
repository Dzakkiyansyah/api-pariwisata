<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'ticket_price',
        'status',
        'user_id',
        'category_id',
    ];

    /**
     * Mendapatkan data user (pengelola) yang memiliki destinasi ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Mendapatkan semua foto dari destinasi ini.
     */
    public function photos()
    {
        return $this->hasMany(DestinationPhoto::class);
    }
}
