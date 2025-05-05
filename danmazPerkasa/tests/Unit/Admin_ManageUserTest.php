<?php

namespace Tests\Unit;
use Tests\TestCase;
use App\Models\User;
use function PHPUnit\Framework\assertSame;

class Admin_ManageUserTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_activateAccount(): void
    {
        $email = fake()->firstName().'@'.fake()->lastName();
        
        $user = User::factory()->create([
            'emailUser'     => $email,
            'passwordUser'  => '123',
        ]);
        // dd($user->id_User);
        $response = $this->get('/DeactiveAccount/'.$user->id_User);
        $response = $this->get('/DeactiveAccount/'.$user->id_User);
        // assertSame($response->json(),0);
        assertSame($response->json(),1);

        // $response->assertRedirect(('/Register'));
        // $response->assertJson(['message' => "Email have been Exist try another email..."]);
    }

    public function test_DeactiveAccount(): void
    {
        $email = fake()->firstName().'@'.fake()->lastName();
        
        $user = User::factory()->create([
            'emailUser'     => $email,
            'passwordUser'  => '123',
        ]);
        $response = $this->get('/DeactiveAccount/'.$user->id_User);
        assertSame($response->json(),0);

        // $response->assertJson(['message' => "Email have been Exist try another email..."]);
    }
    
    public function test_DeleteAccount(): void
    {
        
        $email = fake()->firstName().'@'.fake()->lastName();
        
        $user = User::factory()->create([
            'emailUser'     => $email,
            'passwordUser'  => '123',
        ]);
        
        $response = $this->get('/DeleteAccount/'.$user->id_User);
        $response->assertRedirect(('/Manage/User'));
        // $response->assertJson(['message' => 'Successfully Delete']);
    }




}
