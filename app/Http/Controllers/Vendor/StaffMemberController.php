<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Enums\RoleName;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Notifications\RestaurantStaffInvitation;
use App\Http\Requests\Vendor\StoreStaffMemberRequest;

class StaffMemberController extends Controller
{
    public function index(): Response
    {
        $this->authorize('user.viewAny');

        return Inertia::render('Vendor/Staff/Show', [
            'staff' => auth()->user()->restaurant->staff,
        ]);
    }

    public function store(StoreStaffMemberRequest $request): RedirectResponse
    {
        $restaurant = $request->user()->restaurant;
        $attributes = $request->validated();
        $member = DB::transaction(function () use ($attributes, $restaurant) {
            $user = $restaurant->staff()->create([
                'name'     => $attributes['name'],
                'email'    => $attributes['email'],
                'password' => '',
            ]);
            $user->roles()->sync(Role::where('name', RoleName::STAFF->value)->first());
            return $user;
        });
        // $member->notify(new RestaurantStaffInvitation($restaurant->name));
        return back();
    }

    public function destroy($userId){
        $user = User::find($userId);

        $user->update([
            'restaurant_id' => null
        ]);

        $user->delete();

        return back();
    }
}
