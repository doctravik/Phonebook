<?php

namespace Tests\Feature\Phone;

use App\User;
use App\Phone;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StorePhoneNumberTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function user_can_store_phone_number()
    {
        $john = factory(User::class)->create(['name' => 'john']);

        $response = $this->json('post', "/api/users/{$john->id}/phones", [
            'phone_number' => '2222222222'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('2222222222', $john->fresh()->phones()->first()->number);
    }

    /** @test */
    public function user_cannot_store_phone_number_with_empty_value()
    {
        $john = factory(User::class)->create(['name' => 'john']);

        $response = $this->json('post', "/api/users/{$john->id}/phones", [
            'phone_number' => ''
        ]);

        $response->assertStatus(422);
        $this->assertCount(0, Phone::all());
    }
}
