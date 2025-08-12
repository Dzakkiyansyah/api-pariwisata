<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Mendefinisikan kolom yang boleh diisi
    protected $fillable = ['name', 'slug'];

    /**
     * Mendapatkan semua destinasi yang masuk dalam kategori ini.
     */
    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}
