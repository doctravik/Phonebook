<?php

namespace Tests\Unit;

use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function contact_can_be_found_by_name_with_like_scope()
    {
        factory(Contact::class)->create(['name' => 'JohnDoe']);

        $contact = Contact::findLikeName('Johndoe')->first();

        $this->assertNotNull($contact);
    }

    /** @test */
    public function contact_can_be_found_when_name_is_equals_partly()
    {
        factory(Contact::class)->create(['name' => 'JohnDoe']);

        $contact = Contact::findLikeName('John')->first();

        $this->assertNotNull($contact);
    }

    /** @test */
    public function contact_cannot_be_found_when_name_isnt_exist()
    {
        factory(Contact::class)->create(['name' => 'JohnDoe']);

        $contacts = Contact::findLikeName('Leon');

        $this->assertNull($contacts->first());
       $this->assertEmpty($contacts->get());
    }

    /** @test */
    public function searching_contacts_by_name_is_case_insensitive()
    {
        factory(Contact::class)->create(['name' => 'JohnDoe']);

        $contacts = Contact::findLikeName('john')->first();

        $this->assertNotNull($contacts);
    }
}
