<?php

namespace App\Http\Controllers\Vendor;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index(): Response
    {
        $this->authorize('category.viewAny');

        return Inertia::render('Vendor/Menu', [
            'categories' => Category::query()
                ->where('restaurant_id', auth()->user()->restaurant->id)
                ->with('products')
                ->get(),
        ]);
    }
}
