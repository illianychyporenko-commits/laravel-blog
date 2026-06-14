<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;
    use HasFactory;

    // Перевір, щоб тут не було друкарських помилок і все було всередині класу
    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'description',
    ];
}