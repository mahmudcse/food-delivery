<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Enums\RoleName;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Notifications\RestaurantOwnerInvitation;
use App\Http\Requests\Admin\StoreRestaurantRequest;
use App\Http\Requests\Admin\UpdateRestaurantRequest;

class RestaurantController extends Controller
{
    public function index(): Response
    {
        $this->authorize('restaurant.viewAny');
        $restaurants = Restaurant::with(['city', 'owner'])->get();

        return Inertia::render('Admin/Restaurants/Index', [
            'restaurants' => $restaurants,
        ]);
    }

    public function create()
    {
        $this->authorize('restaurant.create');
        $cities = City::get(['id', 'name']);

        return Inertia::render('Admin/Restaurants/Create', [
            'cities' => $cities,
        ]);
    }

    public function store(StoreRestaurantRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['owner_name'],
                'email'    => $validated['email'],
                'password' => '',
            ]);

            $user->roles()->sync(Role::where('name', RoleName::VENDOR->value)->first());

            $user->restaurant()->create([
                'city_id' => $validated['city_id'],
                'name'    => $validated['restaurant_name'],
                'address' => $validated['address'],
            ]);

            // $user->notify(new RestaurantOwnerInvitation($validated['restaurant_name']));
        });

        return to_route('admin.restaurants.index');
    }

    public function edit(Restaurant $restaurant): Response
    {
        $this->authorize('restaurant.update');

        $restaurant->load(['city', 'owner']);

        return Inertia::render('Admin/Restaurants/Edit', [
            'restaurant' => $restaurant,
            'cities'     => City::get(['id', 'name']),
        ]);
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant): RedirectResponse
    {
        $validated = $request->validated();

        $restaurant->update([
            'city_id' => $validated['city'],
            'name'    => $validated['restaurant_name'],
            'address' => $validated['address'],
        ]);

        return to_route('admin.restaurants.index')
            ->withStatus('Restaurant updated successfully.');
    }
}
