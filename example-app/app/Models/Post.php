<?php

namespace App\Models;

use IlluminateHttpRequest;
use AppModelsPost;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
      ];
}
