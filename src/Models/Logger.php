<?php

namespace Layman\LaravelLogger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logger extends Model
{
    use SoftDeletes;

    protected $table = 'logger';

    protected $guarded = [];

    protected $casts = [
        'old' => 'array',
        'new' => 'array',
    ];
}
