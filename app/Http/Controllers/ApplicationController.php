<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function ApplicationHomePage(Request $request): View{
        return view("app");
    }
}
