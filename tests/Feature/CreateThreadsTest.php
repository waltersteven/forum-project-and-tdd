<?php

namespace Tests\Feature;

use App\Activity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_may_not_create_threads()
    {
        //When visiting the page, guest may be redirected
        $this->get('/threads/create')
            ->assertRedirect(route('login'));

        //When hitting the endpoint, guest may be redirected
        $this->post(route('threads'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory('App\User')->states('unconfirmed')->create();

        $this->signIn($user);

        $thread = make('App\Thread');

        $this->post(route('threads'), $thread->toArray())
            ->assertRedirect('/email/verify');
    }

    /** @test  */
    public function a_user_can_create_new_forum_threads()
    {
        $this->withoutExceptionHandling();
        //Given we have a signed in user
        $this->signIn();

        //When we hit the endpoint to create a new Thread

        // $thread = factory('App\Thread')->raw(); //returns an array

        $thread = make('App\Thread'); //returns an array

        $response = $this->post(route('threads'), $thread->toArray());

        //Then, when we visit the thread page, We should see the new Thread
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $thread = create('App\Thread');

        $response = $this->delete($thread->path());

        $response->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        // dd($response->toArray());

        $response->assertStatus(204);
        // $this->assertDatabaseMissing('threads', $thread->toArray());
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        // $this->assertDatabaseMissing('activities', [
        //     'subject_id' => $thread->id,
        //     'subject_type' => get_class($thread)
        // ]);

        // $this->assertDatabaseMissing('activities', [
        //     'subject_id' => $reply->id,
        //     'subject_type' => get_class($reply)
        // ]);

        //or

        $this->assertEquals(0, Activity::count());
    }

    protected function publishThread($overrides = [])
    {
        $this->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post(route('threads'), $thread->toArray());
    }
}
