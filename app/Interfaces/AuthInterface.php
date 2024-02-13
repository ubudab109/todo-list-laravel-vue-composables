<?php

namespace App\Interfaces;

interface AuthInterface
{
    /**
     * The login function checks if the user exists and if the password is correct, and returns a token
     * and user data if successful.
     * 
     * @param string $email The email parameter is a string that represents the user's email address.
     * @param string $password The password parameter is a string that represents the user's password.
     * 
     * @return array an object.
     */
    public function login(string $email, string $password): array;

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
    public function register(string $email, string $password, string $name): array;
}