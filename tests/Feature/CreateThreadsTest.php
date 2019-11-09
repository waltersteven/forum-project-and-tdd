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

        $thread = create('App\Thread'); //returns an instance

        $this->post('/threads', $thread->toArray());

        //Then, when we visit the thread page, We should see the new Thread
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
    /** @test */
    function guest_may_not_create_threads()
    {
        //When visiting the page, guest may be redirected
        $this->get('/threads/create')
            ->assertRedirect('/login');

        //When hitting the endpoint, guest may be redirected
        $this->post('/threads')
            ->assertRedirect('/login');
    }
}
