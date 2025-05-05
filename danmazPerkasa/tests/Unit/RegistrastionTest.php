<?php

namespace Tests\Unit;
use App\Models\User;
use Tests\TestCase;

class RegistrastionTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_Register_Exist_Email():void{

        $response = $this->json('POST', 'cekLogin/Regist', [
            'el' => 'a@a',
            'pu' => '123',
        ]);
        $response->assertJson(['message' => "Email have been Exist try another email..."]);
    }
    public function test_Register_Successfull():void{
        $email = fake()->firstName().'@'.fake()->lastName();

        $response = $this->post('/RegistrationAccount',[
            'firstName'     =>'aa',
            'lastName'     =>'lk',
            'emailUser'     => $email,
            'passwordUser'  => '12345678aA@',
        ]);

        $response->assertRedirect(('/Login'));
    }
    public function test_Register_Not_Successfully():void{

        $response = $this->post('/RegistrationAccount',[
            'firstName'     =>'aa',
            'lastName'     =>'aa',
            'emailUser'     => 'a@a',
            'passwordUser'  => '12345678aA@',
        ]);

        $response->assertRedirect(('/Register'));
    }
}
