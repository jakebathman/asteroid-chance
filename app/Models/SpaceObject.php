<?php

namespace App\Models;

use App\Models\SpaceObjectDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SpaceObject extends Model
{
    protected $guarded = [];

    public function detail(): HasOne
    {
        return $this->hasOne(SpaceObjectDetail::class);
    }
}
