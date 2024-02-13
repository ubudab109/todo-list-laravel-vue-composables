<?php

namespace App\Services;

use App\Interfaces\AuthInterface;
use App\Interfaces\UserInterface;
use App\Utilities\InternalResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService extends InternalResponse implements AuthInterface
{
    /** @var UserInterface */
    private $repository;

    /**
     * AuthService Constructor
     * 
     * @return void
     */
    public function __construct(UserInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * The login function checks if the user exists and if the password is correct, and returns a token
     * and user data if successful.
     * 
     * @param string $email The email parameter is a string that represents the user's email address.
     * @param string $password The password parameter is a string that represents the user's password.
     * 
     * @return array an object.
     */
    public function login(string $email, string $password): array
    {
        $user = $this->repository->getUserByEmail($email);
        if (!$user) {
            return $this->respondNotFound('User Email Not Found');
        }
        if (Hash::check($password, $user->password)) {
            $token = $user->createToken('user_token')->plainTextToken;
            $data = [
                'token' => $token,
                'user'  => $user,
            ];
            return $this->respondSuccess('Logged in successfully', $data);
        }
        return $this->respondUnauthorizedError('Wrong password');
    }

    /**
     * The register function creates a new user with the given email and password, generates a token
     * for the user, and returns the token and user data.
     * 
     * @param string $email A string representing the email address of the user registering.
     * @param string $password The password parameter is a string that represents the user's password.
     * @param string $name The fullname parameter is a string that represents the user's fullname.
     * 
     * @return array an object.
     */
    public function register(string $email, string $password, string $name): array
    {
        DB::beginTransaction();
        try {
            $userData = [
                'email'    => $email,
                'password' => $password,
                'name'     => $name,
            ];
            $user = $this->repository->createUser($userData);
            $token = $user->createToken('user_token')->plainTextToken;
            $data = [
                'token' => $token,
                'user'  => $user,
            ];
            DB::commit();
            return $this->respondSuccess('Register Successfully', $data);
        } catch (\Exception $err) {
            DB::rollBack();
            return $this->respondInternalError($err->getMessage());
        }
    }
}