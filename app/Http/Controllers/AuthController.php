<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Utilities\InternalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** @var AuthService */
    private $services;

    /**
     * AuthController Constructor
     * 
     * @return void
     */
    public function __construct(AuthService $services)
    {
        $this->services = $services;
    }

    /**
     * The login function takes a LoginRequest object as input, retrieves the email and password from
     * the request, calls a login service with the email and password, and returns a JSON response with
     * the login result and status code.
     * 
     * @param LoginRequest $request The `` parameter is an instance of the `LoginRequest` class.
     * It is used to retrieve the input data from the login form submitted by the user. The `all()`
     * method is called on the `` object to retrieve all the input data as an array.
     * 
     * @return JsonResponse a JsonResponse.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $input = $request->all();
        $login = $this->services->login($input['email'], $input['password']);
        return response()->json($login, $login['status_code']);
    }

    /**
     * The function registers a user by taking in a RegisterRequest object, extracting the email and
     * password from the request, hashing the password, and returning a JSON response with the
     * registration status and status code.
     * 
     * @param RegisterRequest $request The `` parameter is an instance of the `RegisterRequest`
     * class. It is used to retrieve the input data from the HTTP request made to the `register`
     * endpoint.
     * 
     * @return JsonResponse a JsonResponse.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $input = $request->all();
        $register = $this->services->register($input['email'], Hash::make($input['password']), $input['name']);
        return response()->json($register, $register['status_code']);
    }

    /**
     * The above function logs out the user by deleting their current access token and returns a JSON
     * response.
     * 
     * @param Request $request The `` parameter is an instance of the `Illuminate\Http\Request`
     * class. It represents an incoming HTTP request and contains information such as the request
     * method, headers, and payload.
     * 
     * @return JsonResponse a JsonResponse.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user('sanctum:users')->currentAccessToken()->delete();
        $internalResponse = new InternalResponse();
        $respond = $internalResponse->respond('Logout successfully', null, Response::HTTP_NO_CONTENT);
        return response()->json($respond, $respond['status_code']);
    }
}
