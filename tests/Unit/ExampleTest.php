<?php

namespace Tests\Unit;


use Database\Seeders\UsersTableSeeder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    //Check if login page exists
    public function test_cart()
    {
        $response = $this->get('/cart');

        $response->assertStatus(200);
    }

    public function test_index()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_admin()
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
    }

    public function test_search()
    {
        $response = $this->get('/search/lenovo');

        $response->assertStatus(200);
    }

    public function test_Category_Desktop()
    {
        $response = $this->get('/category/desktop');

        $response->assertStatus(200);
    }

    public function test_Laptop()
    {
        $response = $this->get('/category/laptop');

        $response->assertStatus(200);
    }

    public function test_Product()
    {
        $response = $this->get('/product/asus-proart-studiobook-pro-16-oled-w7600h3a-core-i7-11th-gen-rtx-a3000-6gb-graphics-16-oled-laptop');

        $response->assertStatus(200);
    }    

    //Check if user exists in database
    public function test_user_duplication()
    {
        $user1 = DB::table('customers')->where('email', '=', 'mahidul5130@gmail.com')->first();

        $this->assertIsString($user1->email);
        $this->assertIsString($user1->name);
        $this->assertIsInt($user1->is_verify);
    }

    // //Test if a user can be deleted (make sure that you add the middleware)
    // public function test_delete_user()
    // {
    //     $user = User::factory()->count(1)->make();

    //     $user = User::first();

    //     if($user) {
    //         $user->delete();
    //     }

    //     $this->assertTrue(true);
    // }

    // //Perform a post() request to add a new user
    // public function test_if_it_stores_new_users()
    // {
    //     $response = $this->post('/register', [
    //         'name' => 'Dary',
    //         'email' => 'dary@gmail.com',
    //         'password' => 'dary1234',
    //         'password_confirmation' => 'dary1234'
    //     ]);

    //     $response->assertRedirect('/home');
    // }

    // public function test_if_data_exists_in_database()
    // {
    //     $this->assertDatabaseHas('users', [
    //         'name' => 'Dary'
    //     ]);
    // }

    // public function test_if_data_does_not_exists_in_database()
    // {
    //     $this->assertDatabaseHas('users', [
    //         'name' => 'John'
    //     ]);
    // }

    // public function test_if_seeders_works()
    // {
    //     $this->seed();
    // }

    // public function test_if_seeder_works()
    // {
    //     $this->seed(UsersTableSeeder::class);
    // }
}