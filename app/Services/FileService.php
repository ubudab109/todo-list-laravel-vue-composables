<?php

namespace App\Services;

use App\Interfaces\FileInterface;
use App\Utilities\InternalResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FileService extends InternalResponse
{
    /** @var FileInterface */
    private $fileInterface;

    /**
     * File Service Constructor
     * 
     * @return void
     */
    public function __construct(FileInterface $fileInterface)
    {
        $this->fileInterface = $fileInterface;
    }

    /**
     * The function `storeFiles` in PHP is used to store uploaded files, generate a file URL, and save
     * file data in the database.
     * 
     * @param array $data The  parameter is an array that contains the file data. It should have a
     * key named 'file' which holds the file object or file path that needs to be stored.
     * 
     * @return array a success response with the message "File upload successfully" and the file data,
     * or an internal error response with the error message.
     */
    public function createFile(array $data): array
    {
        DB::beginTransaction();
        try {
            $fileDataRes = [];
            foreach($data['files'] as $file) {
                $fileUploaded = storeFile('public/files/', $file);
                if (config('filesystems.default') == 'local') {
                    $fileUrl = URL::to('storage/files/'. $fileUploaded['name']);
                } else {
                    $fileUrl = Storage::url($fileUploaded['name']);
                }
                $fileData = $this->fileInterface->storeFiles(
                    $fileUploaded['name'], 
                    Storage::url('files/' . $fileUploaded['name']),
                    $fileUrl, 
                    getFileType(strtolower($fileUploaded['extension']))
                );
                $fileDataRes[] = $fileData;
            }
            DB::commit();
            return $this->respondSuccess('File upload successfully', $fileDataRes);
        } catch (\Exception $err) {
            DB::rollBack();
            return $this->respondInternalError($err->getMessage());
        }
    }

    /**
     * The function deletes a file by calling the deleteFile method from the fileInterface and then
     * removing the file from the file system.
     * 
     * @param int $fileId The fileId parameter is an integer that represents the unique identifier of
     * the file to be deleted.
     * @param string $filePath The  parameter is a string that represents the path of the file
     * that needs to be deleted.
     * 
     * @return array an array.
     */
    public function deleteFile(int $fileId, string $filePath): array
    {
        $deleteFile = $this->fileInterface->deleteFile($fileId);
        if ($deleteFile) {
            removeFile(public_path($filePath));
            return $this->respondNotContent('File deleted successfully');
        } else {
            return $this->respondNotFound('File not found');
        }
    }
}