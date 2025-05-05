<?php

namespace Tests\Unit;
use App\Models\User;

use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_Login_Data_By_LoginPage_EmailNotRegistered(): void
    {
        $response = $this->json('POST', 'cekLogin/Login', [
            'el' => '1@1',
            'pu' => '124',
        ]);
        // dd($response);
        $response->assertJson(['message' => "Email Not Registered"]);
    }

    public function test_Login_Data_By_LoginPage_AllTrue(): void
    {
        $response = $this->json('POST', '/cekLogin/Login', [
            'el' => 'q@q',
            'pu' => '123',
        ]);
        $response->assertJson(['message' => "Good"]);
    }

    public function test_Login_Data_By_LoginPage_WrongPassword(): void
    {
        $response = $this->json('POST', '/cekLogin/Login', [
            'el' => 'q@q',
            'pu' => '124',
        ]);
        $response->assertJson(['message' => "Wrong Password"]);
    }

    public function test_login_succesfull_UserNonAdmin():void{
        $email = fake()->firstName().'@'.fake()->lastName();

        $user = User::factory()->create([
            'emailUser'     => $email,
            'passwordUser'  => '123',
        ]);
        $response = $this->post('/loginAccount',[
            'emailUser'     => $email,
            'passwordUser'  => '123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response = $this->get('/Logout');
        $response->assertRedirect('/Login');
        $response = $this->get('/Logout');

    }

    public function test_login_succesfull_Admin():void{
        $response = $this->get('/Logout');
        $response = $this->post('/loginAccount',[
            'emailUser'     => 'a@a',
            'passwordUser'  => '123',
        ]);
        
        $response->assertRedirect('/Login');
        $response = $this->get('/Logout');
    }

    public function test_login_Not_succesfull_existData():void{

        $response = $this->post('/loginAccount',[
            'emailUser'     => 'yt@y',
            'passwordUser'  => '123',
        ]);

        $response->assertRedirect(('/Login'));
        $response->assertSessionHas('pesan','Registration Not Succesfull');
    }
}
