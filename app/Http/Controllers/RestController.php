<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class RestController extends Controller
{
    const STATUS_OK = 'OK';
    const STATUS_INVALID_REQUEST = 'INVALID_REQUEST';
    const STATUS_INVALID_DATA = 'INVALID_DATA';
    const STATUS_NOT_FOUND = 'NOT_FOUND';

    /**
     * @param int $limit
     * @param int $offset
     * @return ResourceCollection
     */
    abstract protected function getResourcesList(int $limit, int $offset): ResourceCollection;

    /**
     * @param int $id
     * @throws ModelNotFoundException
     * @return JsonResource
     */
    abstract protected function getResource(int $id);

    /**
     * @param Request $request
     * @throws ValidationException
     */
    abstract protected function insertModel(Request $request): void;

    /**
     * @param int $id
     * @param Request $request
     * @throws ModelNotFoundException
     * @throws ValidationException
     */
    abstract protected function updateModel(int $id, Request $request): void;

    /**
     * @param int $id
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    abstract protected function deleteModel(int $id): void;

    /**
     * @param Request $request
     * @return JsonResponse|JsonResource
     */
    public function list(Request $request)
    {
        try {
            $this->validate($request, [
                'offset' => 'nullable|numeric',
                'limit' => 'nullable|numeric',
            ]);

            $limit = (int) $request->get('limit', 100);
            $offset = (int) $request->get('offset', 0);

            return $this->respondResource($this->getResourcesList($limit, $offset));

        } catch (ValidationException $e) {

            return $this->respondBadRequest();
        }
    }

    /**
     * @param int $id
     * @return JsonResponse|JsonResource
     */
    public function one(int $id)
    {
        try {
            return $this->respondResource($this->getResource($id));

        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $this->insertModel($request);

            return $this->respondOk(Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            return $this->respondValidationError($e->errors());
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        try {
            $this->updateModel($id, $request);

            return $this->respondOk(Response::HTTP_OK);

        } catch (ValidationException $e) {
            return $this->respondValidationError($e->errors());
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function remove(int $id)
    {
        try {
            $this->deleteModel($id);

            return $this->respondOk(Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }
    }

    protected function getMessageByStatus(string $status): string
    {
        return [
            self::STATUS_OK => __('messages.ok'),
            self::STATUS_NOT_FOUND => __('messages.not_found'),
            self::STATUS_INVALID_REQUEST => __('messages.invalid_request'),
            self::STATUS_INVALID_DATA => __('messages.invalid_data'),
        ][$status] ?? '';
    }

    protected function respondValidationError(array $errors, int $httpStatus = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return $this->respond(
            $httpStatus,
            self::STATUS_INVALID_DATA,
            static::getMessageByStatus(self::STATUS_INVALID_DATA),
            null,
            $errors
        );
    }

    protected function respondBadRequest(int $httpStatus = Response::HTTP_BAD_REQUEST)
    {
        return $this->respond(
            $httpStatus,
            self::STATUS_INVALID_REQUEST,
            static::getMessageByStatus(self::STATUS_INVALID_REQUEST)
        );
    }

    protected function respondResource(JsonResource $resource)
    {
        return $resource->additional([
            'status' => self::STATUS_OK,
            'message' => $this->getMessageByStatus(self::STATUS_OK)
        ]);
    }

    protected function respondOk(int $httpStatus = Response::HTTP_OK)
    {
        return $this->respond(
            $httpStatus,
            self::STATUS_OK,
            static::getMessageByStatus(self::STATUS_OK),
        );
    }

    protected function respondNotFound(int $httpStatus = Response::HTTP_NOT_FOUND)
    {
        return $this->respond(
            $httpStatus,
            self::STATUS_NOT_FOUND,
            static::getMessageByStatus(self::STATUS_NOT_FOUND)
        );
    }

    protected function respond(int $httpCode, string $status, string $message, ?array $data = null, ?array $errors = null)
    {
        $jsonData = [
            'status' => $status,
            'message' => $message,
        ];

        if (null !== $data) {
            $jsonData['data'] = $data;
        }

        if (null !== $errors) {
            $jsonData['errors'] = $errors;
        }

        return new JsonResponse($jsonData, $httpCode);
    }
}
