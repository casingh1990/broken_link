<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    public const STATUS_PENDING = 0;
    public const STATUS_WORKING = 1;
    public const STATUS_BROKEN = 2;
}
