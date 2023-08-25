<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home', [
            'restaurants' => Restaurant::get(),
        ]);
    }
}
