<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
            // 'activities' => $this->getActivities($user)
        ]);
    }

    // protected function getActivities($user)
    // {
    //     return $user->activity()->with('subject')->take(50)->get()->groupBy(function ($activity) {
    //         return $activity->created_at->format('Y-m-d');
    //     });
    // }
}
