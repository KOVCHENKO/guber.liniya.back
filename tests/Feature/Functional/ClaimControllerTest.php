<?php

namespace Tests\Feature\Functional;


use App\src\Models\Call;
use App\src\Models\Problem;
use App\src\Models\ProblemType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ClaimControllerTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    private $baseUrl = 'api/claims/';

    private $dummyClaim;

    public function prepare() {
        $problemType  = factory(ProblemType::class)->create();
        $problem  = factory(Problem::class)->make([
            'problem_type_id' => $problemType->id
        ]);

        $call = factory(Call::class)->create();

        $this->dummyClaim = [
            'comments' => [],
            'description' => 'dummy claim description',
            'dispatchStatus' => 'prepared',
            'level' => 'Личная',
            'link' => 'https://records.megapbx.ru/record/pravastrobl.megapbx.ru/2018-11-29/62f2c7b9-a671-403f-aa31-b086d9a2b64e/79270718307_in_2018_11_29%2D15_53_06_79272816198_cahv.mp3',
            'name' => '',
            'phone' => '87252211782',
            'pid' => 0,
            'call' => [
                'atsStatus' => $call->ats_status,
                'callid' => $call->call_id,
                'clientPhone' => $call->phone,
                'createdAt' => $call->created_at,
                'ext' => '770',
                'id' => $call->id,
                'link' => $call->link,
                'processingStatus' => $call->processing_status,
                'type' => $call->in
            ],
            'problem' => [
                'description' => $problem->description,
                'name' => $problem->name,
                'id' => $problem->id
            ]
        ];
    }

    /** @test */
    public function claim_can_be_created()
    {
        $this->prepare();

        $response = $this->call('POST', $this->baseUrl.'create', $this->dummyClaim);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
