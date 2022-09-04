<?php

namespace App\Http\Controllers;

use App\Console\Commands\CheckLinks;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class LinkCheckController extends Controller
{
    public function check()
    {
        Artisan::call(CheckLinks::class);
    }
}