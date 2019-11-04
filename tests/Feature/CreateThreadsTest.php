<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test  */
    function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->withoutExceptionHandling();
        //Given we have a signed in user
        $this->signIn();

        //When we hit the endpoint to create a new Thread

        // $thread = factory('App\Thread')->raw(); //returns an array

        $thread = make('App\Thread'); //returns an instance

        $response = $this->post('/threads', $thread->toArray());

        //Then, when we visit the thread page, We should see the new Thread
        // $this->get($response->getTargetUrl())
        //     ->asertSee($thread->body);
        // ->asertSee($thread->body);
    }
    /** @test */
    function guest_may_not_create_threads()
    {
        $this->withoutExceptionHandling();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        // $thread = factory('App\Thread')->make();
        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    function guests_cannot_see_the_create_thread_page()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');
    }
}
