<?php

namespace App\Interfaces;

use App\Models\User;

interface UserInterface
{
    /**
	 * The function `getUserByEmail` retrieves a user from the database based on their email and returns
	 * the user object if found, or null if not found.
	 * 
	 * @param string $email The email parameter is a string that represents the email address of the user
	 * you want to retrieve.
	 * 
	 * @return User|null a User object if a user with the specified email is found in the database. If no
	 * user is found, it returns null.
	 */
    public function getUserByEmail(string $email): User|null;

    /**
	 * The function createUser takes an array of data and inserts it into the model to create a new user.
	 * 
	 * @param array $data The  parameter is an array that contains the data needed to create a new
	 * user. This data typically includes information such as the user's name, email address, password,
	 * and any other relevant details.
	 * 
	 * @return User the result of the insert method call on the model object.
	 */
    public function createUser(array $data): User;
}