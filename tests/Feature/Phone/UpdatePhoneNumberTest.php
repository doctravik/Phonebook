<?php

namespace Tests\Feature;

use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdatePhoneNumberTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function contact_can_update_phone_number()
    {
        $john = factory(Contact::class)->create(['name' => 'john']);
        $phone = $john->addPhone('1111111111');

        $response = $this->json('patch', "/api/phones/{$phone->id}", [
            'phone_number' => '2222222222'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('2222222222', $john->fresh()->phones()->first()->phone_number);
    }

    /** @test */
    public function contact_cannot_update_phone_number_with_empty_value()
    {
        $john = factory(Contact::class)->create(['name' => 'john']);
        $phone = $john->addPhone('1111111111');

        $response = $this->json('patch', "/api/phones/{$phone->id}", [
            'phone_number' => ''
        ]);

        $response->assertStatus(422);
        $this->assertEquals('1111111111', $john->fresh()->phones()->first()->phone_number);
    }
}
