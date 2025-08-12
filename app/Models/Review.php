<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destination_id',
        'rating',
        'comment',
    ];

    /**
     * Mendapatkan data user yang membuat ulasan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan data destinasi yang diulas.
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
