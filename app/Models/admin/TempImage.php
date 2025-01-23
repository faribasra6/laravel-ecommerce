<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class TempImage extends Model
{
    protected $table = 'temp_images';
    protected $fillable = [
        'file_name',
        'file_path',
        'file_extension',
        'is_active',
    ];
}
