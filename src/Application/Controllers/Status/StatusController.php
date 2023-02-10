<?php

namespace App\Application\Controllers\Status;


use App\Infrastructure\Repository\Status\StatusRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StatusController implements StatusControllerInterface
{

    private $model;

    public function __construct(StatusRepositoryInterface $model){
        $this->model = $model;
    }

    /**
     * @OA\Get(
     *     tags={"status"},
     *     path="/status",
     *     operationId="getAllStatus",
     *     summary= Get all Status
     *       @OA\Response
     *        (response=200, description="List all Status",
     *          @OA\JsonContent(type="array", @OA\Items (ref="#/components/schemas/Status"))
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getAll(Request $request, Response $response, $args): Response
    {

        $status = $this->model->getAll($args);
        $response->getBody()->write(json_encode($status));
        $response->withHeader("Content-Type","application/json")->withStatus(200);

        return $response;
    }

    /**
     * @OA\Get(
     *     tags={"status"},
     *     path="/status/{id}",
     *     operationId="getStatus",
     *     summary= Get specific Status
     *     @OA\Parameter (
     *        name:"id",
     *        in="path",
     *        required=true,
     *        description = "Status id"
     *        @OA\Schema (type="integer")
     *      )
     *       @OA\Response
     *        (response=200, description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/Status")
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getElement(Request $request, Response $response, $args): Response
    {

        $status= $this->model->getByID($args);
        $response->getBody()->write(json_encode($status));
        $response->withHeader("Content-Type","application/json")->withStatus(200);

        return $response;

    }
    /**
     * @OA\Put(
     *     tags={"tab"},
     *     path="/status/{id}",
     *     operationId="editStatus",
     *     summary= Edit Status
     *      @OA\Parameter (
     *        name:"id",
     *        in="path",
     *        required=true,
     *        description = "Status id"
     *        @OA\Schema (type="integer")
     *      )
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                   @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      require= true
     *                  ),
     *                  example = {"name": "My Status v2"}
     *              )
     *          )
     *     )
     *      @OA\Response(response="200",description="Status updated")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function editElement(Request $request, Response $response, $args): Response
    {

        $params = $request->getParsedBody();
        $params["id"] = $args["id"];
        return ($this->model->edit_element($params)) ?
            $response->withStatus(200): $response->withStatus(406);
    }

    /**
     * @OA\Delete(
     *     tags={"status"},
     *     path="/status/{id}",
     *     operationId="deleteStatus",
     *     summary= Delete Status
     *      @OA\Parameter (
     *        name:"id",
     *        in="path",
     *        required=true,
     *        description = "Status id"
     *        @OA\Schema (type="integer")
     *      )
     *      @OA\Response(response="200",description="Delete Status")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function deleteElement(Request $request, Response $response, $args): Response
    {
        $this->model->delete_element($args);

        return $response->withStatus(200);
    }

    /**
     * @OA\Post(
     *     tags={"status"},
     *     path="/status/create",
     *     operationId="createStatus",
     *     summary= Create Status
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                      required=true
     *                  ),
     *                  example = {"name": "My tab"}
     *              )
     *          )
     *     )
     *      @OA\Response(response="201",description="Status created")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function createElement(Request $request, Response $response, $args): Response
    {
        $this->model->create_element($request->getParsedBody());

        return $response->withStatus(200);
    }

}