<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'medias';

    protected $fillable = [
        'display_name',
        'filename',
        'filename_id',
        'size',
        'extension',
        'description',
        'width',
        'height',
        'order',
    ];
}
