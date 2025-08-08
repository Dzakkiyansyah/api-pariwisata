<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'status',
        'official_document_path',
        'notes',
    ];

    /**
     * Untuk mendapatkan relasi ke modek User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
