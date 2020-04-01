<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostFormRequest;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use App\User;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(3);
    }

    public function store($channelId, Thread $thread, CreatePostFormRequest $form)
    {
        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ])->load('owner');
    }

    public function destroy(Reply $reply)
    {
        // if ($reply->user_id != auth()->id()) {
        //     return response([], 403);
        // }

        //Translated to Policy:

        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

    public function update(Reply $reply)
    {
        try {
            $this->authorize('update', $reply);

            request()->validate([
                'body' => ['required', new SpamFree],
            ]);

            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }
    }
}
