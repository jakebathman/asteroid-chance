<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

class SpaceObjectDetail extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'impacts' => AsArrayObject::class,
        ];
    }
}
