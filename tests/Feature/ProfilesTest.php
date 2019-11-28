<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_has_a_profile()
    {
        $this->withoutExceptionHandling();

        $user = create('App\User');

        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }

    /** @test */
    public function profiles_displays_all_threads_created_by_the_associated_user()
    {
        $this->withoutExceptionHandling();

        $user = create('App\User');

        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->get("/profiles/{$user->name}")
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
