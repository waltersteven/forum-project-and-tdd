<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        // 1st approach
        // foreach ($event->reply->mentionedUsers() as $name) {
        //     $user = User::whereName($name)->first();

        //     if ($user) {
        //         $user->notify(new YouWereMentioned($event->reply));
        //     }
        // }

        // 2nd approach
        collect($event->reply->mentionedUsers())
            ->map(function ($name) {
                return User::where('name', $name)->first();
            })
            ->filter() // removes null values
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });
    }
}
