<?php

namespace Tests\Unit;

use Tests\TestCase;

use function PHPUnit\Framework\assertSame;

class Admin_ManageTransactionTest extends TestCase
{
    public $idTransaction;
    /**
     * A basic unit test example.
     */

    public function make_transaction_success(){
        
    }
    public function test_AcceptTransaction(): void{
        $response = $this->get('/Transaction/AcceptOrder/10');
        // dd($response->json());
        assertSame($response->json(),'Success');
    }
    
    public function test_RejectTransaction(): void{
        $response = $this->get('/Transaction/RejectOrder/8');
        assertSame($response->json(),'Success');
    }
}
