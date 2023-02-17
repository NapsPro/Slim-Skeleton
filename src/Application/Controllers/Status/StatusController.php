<?php

namespace App\Application\Controllers\Status;


use App\Infrastructure\Repository\Status\StatusRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;
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
     *     summary= "Get all Status",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *       @OA\Response
     *        (response=200, description="List all Status",
     *          @OA\JsonContent(type="array", @OA\Items (ref="#/components/schemas/Status"))
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getAll(Request $request, Response $response, $args): Response
    {

        $status = $this->model->getAll($request->getParsedBody());
        $response->getBody()->write(json_encode($status));
        $response->withHeader("Content-Type","application/json")->withStatus(200);

        return $response;
    }

    /**
     * @OA\Get(
     *     tags={"status"},
     *     path="/status/{id}",
     *     operationId="getStatus",
     *     summary= "Get specific Status",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="id",
     *        in="path",
     *        description = "Status id",
     *        @OA\Schema (type="integer")
     *      ),
     *       @OA\Response
     *        (response=200, description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/Status")
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getElement(Request $request, Response $response, $args): Response
    {

        $status= $this->model->getByID($args["id"]);
        $response->getBody()->write(json_encode($status));
        $response->withHeader("Content-Type","application/json")->withStatus(200);

        return $response;

    }
    /**
     * @OA\Put(
     *     tags={"tab"},
     *     path="/status/{id}",
     *     operationId="editStatus",
     *     summary= "Edit Status",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *      @OA\Parameter (
     *        name="id",
     *        in="path",
     *        required=true,
     *        description = "Status id",
     *        @OA\Schema (type="integer")
     *      ),
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={"name"},
     *                   @OA\Property(
     *                      property="name",
     *                      type="string",
     *                  ),
     *                  example = {"name": "My Status v2"}
     *              )
     *          )
     *     ),
     *      @OA\Response(response="200",description="Status updated"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function editElement(Request $request, Response $response, $args): Response
    {

        $params = $request->getParsedBody();
        $this->model->editElement($args["id"],$params);
        $response->getBody()->write("Status edit");
        return $response->withStatus(200);
    }

    /**
     * @OA\Delete(
     *     tags={"status"},
     *     path="/status/{id}",
     *     operationId="deleteStatus",
     *     summary= "Delete Status",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *      @OA\Parameter (
     *        name="id",
     *        in="path",
     *        required=true,
     *        description = "Status id",
     *        @OA\Schema (type="integer")
     *      ),
     *      @OA\Response(response="200",description="Delete Status"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function deleteElement(Request $request, Response $response, $args): Response
    {
        $this->model->deleteElement($args["id"],$request->getParsedBody());
        $response->getBody()->write("Deletion complete");
        return $response->withStatus(200);
    }

    /**
     * @OA\Post(
     *     tags={"status"},
     *     path="/status/create",
     *     operationId="createStatus",
     *     summary= "Create Status",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={"name"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  example = {"name": "My tab"}
     *              )
     *          )
     *     ),
     *      @OA\Response(response="201",description="Status created"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function createElement(Request $request, Response $response, $args): Response
    {
        $this->model->createElement($request->getParsedBody());

        $response->getBody()->write("Status created");
        return $response->withStatus(200);
    }

}