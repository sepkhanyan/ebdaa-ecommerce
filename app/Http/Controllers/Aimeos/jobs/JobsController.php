<?php

namespace App\Http\Controllers\Aimeos\jobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class JobsController extends Controller
{
    public function IndexRebuild(Request $request)
    {
        try {
            // run aimeos job
            Artisan::call('aimeos:jobs', ['jobs' => 'index/rebuild']);
        } catch (\Throwable $e) {
            return redirect()->back();
        }

        return redirect()->back();
    }
}
