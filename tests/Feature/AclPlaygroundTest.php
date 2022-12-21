<?php

namespace Tests\Feature;

use App\Models\ClientApplication;
use App\Models\Printer;
use App\Models\PrintServer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use RenokiCo\Acl\Acl;
use RenokiCo\Acl\Statement;
use Tests\TestCase;

class AclPlaygroundTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        /** @var ClientApplication $client */
        $client = ClientApplication::factory()->create();

        $server = PrintServer::factory()
            ->recycle($client->Team)
            ->create();

        $printers = Printer::factory()
            ->active()
            ->for($server, 'Server')
            ->count(3)
            ->create();

        $policy = Acl::createPolicy([
            Statement::make(
                effect: 'Allow',
                action: 'printer:Print',
                resource: [
                    'arn:webprint:server:testing:'.$client->Team->ulid.':printer/*',
                ],
            ),
            Statement::make(
                effect: 'Deny',
                action: 'printer:Print',
                resource: [
                    'arn:webprint:server:testing:'.$client->Team->ulid.':printer/'.$printers[1]->ulid,
                ],
            ),
        ]);

//        dd(
//            Printer::resourceIdAgnosticArn($client),
//            json_encode($policy->toArray(), JSON_PRETTY_PRINT),
//        );

        $client->loadPolicies($policy);

        $this->assertTrue($client->isAllowedTo('printer:Print', $printers[0]));
        $this->assertFalse($client->isAllowedTo('printer:Print', $printers[1]));
        $this->assertTrue($client->isAllowedTo('printer:Print', $printers[2]));
    }
}
