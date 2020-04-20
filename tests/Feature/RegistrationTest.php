<?php

namespace Test\Feature;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Event::fake();

        $this->post(route('register'), [
            'name' => 'Joe',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        Event::assertDispatched(Registered::class);
    }
}
