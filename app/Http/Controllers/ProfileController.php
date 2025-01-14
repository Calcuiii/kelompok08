<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Profile';
        return view('profile', ['pageTitle' => $pageTitle]);
    }
}
