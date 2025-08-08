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
}
