<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class LoginPageController extends Controller
{
    public function __invoke(): \Inertia\Response
    {
        return Inertia::render('Auth');
    }
}
