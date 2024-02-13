<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileDeleteRequest;
use App\Http\Requests\FileRequest;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /** @var FileService */
    private $fileService;

    /**
     * File Controller Constructor
     * 
     * @return void
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * The function stores a file by creating it using the input data and returns a JSON response.
     * 
     * @param FileRequest $request The parameter `` is an instance of the `FileRequest` class.
     * It is used to validate and retrieve the input data from the HTTP request. It contains methods to
     * access the request data, such as `all()` which returns an array of all the input data.
     * 
     * @return JsonResponse a JsonResponse.
     */
    public function store(FileRequest $request): JsonResponse
    {
        $input = $request->all();
        $response = $this->fileService->createFile($input);
        return response()->json($response, $response['status_code']);
    }

    /**
     * The function takes a file delete request and an ID, deletes the specified file, and returns a
     * JSON response.
     * 
     * @param FileDeleteRequest $request The  parameter is an instance of the FileDeleteRequest
     * class. It is used to retrieve the input data from the HTTP request made to the server. This
     * class may contain validation rules and other methods to handle the request data.
     * @param int $id The `id` parameter is an integer that represents the unique identifier of the file
     * that needs to be deleted.
     * 
     * @return JsonResponse a JsonResponse.
     */
    public function destroy(FileDeleteRequest $request, int $id): JsonResponse
    {
        $input = $request->all();
        $response = $this->fileService->deleteFile($id, $input['filename']);
        return response()->json($response, $response['status_code']);
    }
}
