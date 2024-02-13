<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * The function tests if a required login request returns an unprocessable entity status.
     */
    /**
     * A basic feature test example.
     */
    public function test_required_request_login(): void
    {
        $this->json('POST', 'api/login', [], ['Accept' => 'application/json'])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * The function tests for a not found email by sending a POST request to the 'api/login' endpoint
     * with a wrong email and password, and asserts that the response has a status code of 404 and a
     * JSON body containing an error message indicating that the user email was not found.
     */
    public function test_not_found_email(): void
    {
        $data = [
            'email'     => 'wrong@email.com',
            'password'  => '123412312312',
        ];

        $this->json('POST', 'api/login', $data, ['Accept' => 'application/json'])
        ->assertStatus(Response::HTTP_NOT_FOUND)
        ->assertJson([
            'error'       => true,
            'message'     => 'User Email Not Found',
            'status_code' => 404,
            'response'    => null
        ]);
    }

    /**
     * The function tests the login functionality by attempting to log in with an incorrect password
     * and expects an unauthorized response with a specific error message.
     */
    public function test_wrong_password_login(): void
    {
        $user = User::create([
            'name'      => 'test1234',
            'email'     => 'sample@test.com',
            'password'  => Hash::make('123123123'),
        ]);

        $loginData = ['email' => $user->email, 'password' => '31242342342'];
        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'error'       => true,
            'message'     => 'Wrong password',
            'status_code' => 401,
            'response'    => null
        ]);
    }

    /**
     * The function tests the successful login functionality by creating a user, logging in with the
     * user's credentials, and asserting the response structure.
     */
    public function test_success_login(): void
    {
        $user = User::create([
            'name'      => 'test1234',
            'email'     => 'sample2@test.com',
            'password'  => Hash::make('123123123'),
        ]);

        $loginData = ['email' => $user->email, 'password' => '123123123'];
        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'error',
            'message',
            'status_code',
            'response' => [
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ]
            ]  
        ]);
    }

    /**
     * The function tests for a bad request when registering a user and checks if the required fields
     * are missing.
     */
    public function test_bad_request_register()
    {
        $this->json('POST', 'api/register', [], ['Accept' => 'application/json'])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'error'     => true,
            'message'   => [
                'Email is required',
                'Name is required',
                'Password is required',
                'Confirm password is required'
            ],
            'status_code' => 422,
        ]);
    }

    /**
     * The function tests if the confirm password provided by the user does not match the password.
     */
    public function test_confirm_password_invalid()
    {
        $this->json('POST', 'api/register', [
            'email' => 'test@test.com',
            'name'  => 'test',
            'password' => '123123123',
            'c_password' => 'sdfasdfa',
        ], ['Accept' => 'application/json'])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'error'     => true,
            'message'   => [
                'Confirm password does not match'
            ],
            'status_code' => 422,
        ]);
    }

    /**
     * The function tests if an error is returned when trying to register a user with an email that is
     * already taken.
     */
    public function test_email_already_taken()
    {
        User::create([
            'name'      => 'test1234',
            'email'     => 'sample2@test.com',
            'password'  => Hash::make('123123123'),
        ]);

        $this->json('POST', 'api/register', [
            'email' => 'sample2@test.com',
            'name'  => 'test',
            'password' => '123123123',
            'c_password' => '123123123',
        ], ['Accept' => 'application/json'])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'error'     => true,
            'message'   => [
                'The email has already been taken.'
            ],
            'status_code' => 422,
        ]);
    }

    /**
     * The function tests the successful registration of a user by sending a POST request to the
     * 'api/register' endpoint with the required parameters and asserts that the response has a status
     * code of 200 and a specific JSON structure.
     */
    public function test_succes_register()
    {
        $this->json('POST', 'api/register', [
            'email' => 'sample3@test.com',
            'name'  => 'test',
            'password' => '123123123',
            'c_password' => '123123123',
        ], ['Accept' => 'application/json'])
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'error',
            'message',
            'status_code',
            'response' => [
                'token',
                'user' => [
                    'email',
                    'name',
                    'updated_at',
                    'created_at',
                    'id',
                ]
            ]
        ]);
    }
}
