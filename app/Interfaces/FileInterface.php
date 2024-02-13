<?php

namespace App\Interfaces;

use App\Models\File;

interface FileInterface
{
    /**
	 * The function "storeFiles" takes in a file name, file URL, and type as parameters, and creates a new
	 * file object with those values.
	 * 
	 * @param string $fileName The name of the file that you want to store.
	 * @param string $filepath The real filepath.
	 * @param string $fileUrl The fileUrl parameter is a string that represents the URL or path of the file
	 * that you want to store.
	 * @param string $type The "type" parameter is a string that represents the type or category of the
	 * file being stored. It can be used to classify files into different groups or to indicate the
	 * purpose or content of the file.
	 * 
	 * @return File a File object.
	 */
    public function storeFiles(string $fileName, string $filepath, string $fileUrl, string $type): File;

    /**
	 * The function deletes a file with the given file ID and returns true if the deletion is successful,
	 * otherwise it returns false.
	 * 
	 * @param int $fileId The fileId parameter is an integer that represents the unique identifier of the
	 * file that needs to be deleted.
	 * 
	 * @return bool a boolean value. It returns true if the file is successfully deleted, and false if the
	 * file is not found or unable to be deleted.
	 */
    public function deleteFile(int $fileId): bool;
}