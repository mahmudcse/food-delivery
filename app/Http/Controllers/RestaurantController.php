<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function show(Restaurant $restaurant): Response
    {
        return Inertia::render('Restaurant', [
            'restaurant' => $restaurant->load('categories.products'),
        ]);
    }
}
