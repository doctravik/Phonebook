<?php

namespace Tests\Feature;

use App\User;
use App\Phone;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeletePhoneNumberTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function unathenticated_user_cannot_delete_phone_number()
    {
        $phone = factory(Phone::class)->create();

        $response = $this->json('delete', "/api/phones/$phone->id");

        $response->assertStatus(401);
        $this->assertCount(1, Phone::all());
    }

    /** @test */
    public function authenticated_user_can_delete_phone_number_of_own_contact()
    {
        $phone = factory(Phone::class)->create();

        $response = $this->actingAs($phone->user)->json('delete', "/api/phones/$phone->id");

        $response->assertStatus(200);
        $this->assertCount(0, Phone::all());
    }

    /** @test */
    public function user_cannot_delete_phone_number_of_the_contact_of_the_another_user()
    {
        $anotherUser = factory(User::class)->create();
        $phone = factory(Phone::class)->create();

        $response = $this->actingAs($anotherUser)->json('delete', "/api/phones/$phone->id");

        $response->assertStatus(403);
        $this->assertCount(1, Phone::all());
    }
}
