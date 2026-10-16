<?php

namespace App\Http\Controllers;

use App\Support\DemoContent;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(): View
    {
        return view('branches.index', [
            'branches' => DemoContent::branches(),
            'brand' => DemoContent::brand(),
        ]);
    }
}
