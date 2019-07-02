<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PrimaryPivot extends Pivot
{
    protected $casts = ['id' => 'string'];
}
