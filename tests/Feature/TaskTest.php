<?php

namespace Tests\Feature;

use App\Constants\TaskLevel;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskTest extends TestCase
{

    /**
     * The function tests for an invalid request to create a task and expects a specific JSON response.
     */
    public function test_invalid_request_create_task(): void
    {
        $user = User::create([
            'name'      => 'test1234',
            'email'     => 'sample2@test.com',
            'password'  => Hash::make('123123123'),
        ]);
        
        $token = $user->createToken('user_token')->plainTextToken;
        
        $this->json('POST', 'api/tasks', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'error' => true,
            'message' => [
                'The title field is required.',
                'The description field is required.'
            ],
            'status_code' => 422,
        ]);
    }

    /**
     * The function tests the successful creation of a task by sending a POST request to the
     * 'api/tasks' endpoint with the required parameters and a valid authorization token.
     */
    public function test_success_create_task(): void
    {
        $user = User::create([
            'name'      => 'test1234',
            'email'     => 'sample2@test.com',
            'password'  => Hash::make('123123123'),
        ]);
        
        $token = $user->createToken('user_token')->plainTextToken;

        $this->json('POST', 'api/tasks', [
            'title' => 'test',
            'description' => 'task desc',
            'due_date' => '2023-11-11',
            'priority' => TaskLevel::NORMAL()->getValue(),
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $token
        ])->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'error',
            'message',
            'status_code',
            'response',
        ]);
    }
}
