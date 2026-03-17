<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class MenuPageController extends Controller
{
    public function __invoke(): \Inertia\Response
    {
        return Inertia::render('Menu');
    }
}
