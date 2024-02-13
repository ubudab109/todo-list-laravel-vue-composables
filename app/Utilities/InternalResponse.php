<?php

namespace App\Utilities;

use Illuminate\Http\Response;
use Illuminate\Validation\Validator;

class InternalResponse
{

    /*
     * This is mainly used for any responses between adapters / controllers and anywhere else applicable
     */
    protected $statusCode = Response::HTTP_OK;
    protected $message = '';
    protected $error = false;
    protected $errorCode = 0;


    /**
     * Function to return an error response.
     *
     * @param $message
     * @return mixed
     */
    public function respondWithError($message): mixed
    {
        $this->error = true;
        $this->message = $message;
        return $this->respond();
    }

    /**
     * Function to return an unauthorized response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondUnauthorizedError($message = 'Unauthorized!'): mixed
    {
        $this->statusCode = Response::HTTP_UNAUTHORIZED;
        return $this->respondWithError($message);
    }


    /**
     * Function to return a bad request response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondBadRequestError($message = 'Bad Request!!'): mixed
    {
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        return $this->respondWithError($message);
    }

    /**
     * Function to return forbidden error response.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function respondForbiddenError($message = 'Forbidden!'): mixed
    {
        $this->statusCode = Response::HTTP_FORBIDDEN;
        return $this->respondWithError($message);
    }

    /**
     * Function to return a Not Found response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($message = 'Resource Not Found'): mixed
    {
        $this->statusCode = Response::HTTP_NOT_FOUND;
        return $this->respondWithError($message);
    }

    /**
     * Function to return an internal error response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondInternalError($message = 'Internal Server Error!'): mixed
    {
        $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        return $this->respondWithError($message);
    }

    /**
     * Function to return an internal error response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondMethodNotAllowed($message = 'Method not allowed!'): mixed
    {
        $this->statusCode = Response::HTTP_METHOD_NOT_ALLOWED;
        return $this->respondWithError($message);
    }

    /**
     * Function to return a service unavailable response.
     *
     * @param string $message
     * @return mixed
     */
    public function respondServiceUnavailable($message = "Service Unavailable!"): mixed
    {
        $this->statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        return $this->respondWithError($message);
    }

    /**
     * Throws a bad request exception with the validator's error messages.
     *
     * @param Validator $validator The validator to get the message from
     *
     * @return mixed
     */
    public function showValidationError(Validator $validator): mixed
    {
        $this->error = true;
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        $this->message = implode(" ", $validator->errors()->all());
        return $this->respond();
    }

    /**
     * Function to return a created response
     *
     * @param $data array The data to be included
     *
     * @return mixed
     *
     */
    public function respondCreated($data): mixed
    {
        $this->statusCode = Response::HTTP_CREATED;
        return $this->respond($data);
    }

    /**
     * Function to return a success response
     *
     * @param $data array The data to be included
     *
     * @return mixed
     *
     */
    public function respondSuccess($message = 'Success', $data = null, $statusCode = Response::HTTP_OK): mixed
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        return $this->respond($data);
    }

    /**
     * The function sets the response message and status code to indicate a successful request with no
     * content.
     * 
     * @param string $message The message parameter is optional and it represents the response message that
     * will be returned. By default, it is set to 'Success'.
     * 
     * @return mixed the result of calling the `respond()` method.
     */
    public function respondNotContent($message = 'Success'): mixed
    {
        $this->message = $message;
        $this->statusCode = Response::HTTP_NO_CONTENT;
        return $this->respond();
    }

    /**
     * Function to return a generic response.
     *
     * @param string $message message to be used in response.
     * @param mixed $data array to be used in response.
     * @param Response $statusCode status code to be used in response.

     * @return array Return the response.
     */
    public function respond($data = null): array
    {
        $meta = [
            'error' => $this->error ? true : false,
            'message' => $this->message,
            'status_code' => $this->statusCode,
        ];

        if (empty($data) && !is_array($data)) {
            $data = array_merge($meta, ['response' => null]);
        } else {
            $data = array_merge($meta, ['response' => $data]);
        }

        return $data;
    }

}
