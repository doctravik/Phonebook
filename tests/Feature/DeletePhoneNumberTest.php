<?php

namespace Tests\Feature;

use App\Phone;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeletePhoneNumberTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_delete_phone_number()
    {
        $phone = factory(Phone::class)->create();

        $response = $this->json('delete', "/api/phones/$phone->id");

        $response->assertStatus(200);
        $this->assertCount(0, Phone::all());
    }
}
