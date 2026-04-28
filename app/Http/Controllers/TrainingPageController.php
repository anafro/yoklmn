<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class TrainingPageController extends Controller
{
    public function __invoke(): \Inertia\Response
    {
        return Inertia::render("Mode/Training");
    }
}
