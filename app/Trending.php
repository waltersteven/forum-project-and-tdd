<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get()
    {
        // Get the first five items.

        // 1st approach
        // $trending = collect(Redis::zrevrange('trending_threads', 0, 4))
        //     ->map(function ($thread) {
        //         return json_decode($thread);
        //     });

        // 2nd approach
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    public function push($thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    protected function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}
