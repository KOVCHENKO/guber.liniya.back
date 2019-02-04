<?php

namespace Tests\Feature\Functional;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ApplicantControllerTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    private $baseUrl = 'api/applicants/';

    private $dummyApplicant = [
        'firstname' => 'John',
        'lastname' => 'Doe',
        'middlename' => 'Johnson',
        'email' => 'johndoe@mail.com',
        'phone' => '89170863637',
        'address' => [
            'city' => 'Tash',
            'street' => 'Kent',
            'building' => '8',
            'district' => 'Tash'
        ],
    ];

    /** @test */
    public function applicant_can_be_created()
    {
        $response = $this->call('POST', $this->baseUrl.'create', $this->dummyApplicant);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function applicants_can_be_fetched()
    {
        $response = $this->call('GET', $this->baseUrl.'all');

        $this->assertEquals(200, $response->getStatusCode());
    }
}
