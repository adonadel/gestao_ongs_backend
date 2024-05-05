<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes;

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
    ];
}
