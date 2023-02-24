<?php

namespace App\Application\Controllers\Tab;

use App\Infrastructure\Repository\Tabs\TabsRepositoryInterface ;
use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ServerRequestInterface as Request;

class TabController implements TabControllerInterface
{
    private $model;

    public function __construct(TabsRepositoryInterface $model){
        $this->model = $model;
    }
    /**
     * @OA\Get(
     *     tags={"tab"},
     *     path="/{ticket_slug}/tabs",
     *     operationId="getAllTabs",
     *     summary= "Get all Tabs",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="ticket_slug",
     *        in="path",
     *        description = "Ticket slug",
     *        @OA\Schema (type="string")
     *      ),
     *       @OA\Response
     *        (response=200, description="List all Tabs",
     *          @OA\JsonContent(type="array", @OA\Items (ref="#/components/schemas/Tabs"))
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getAll(Request $request, Response $response, $args): Response
    {
        $tabs = $this->model->getAll($args);
        $response->getBody()->write(json_encode($tabs));
        $response->withHeader("Content-Type","application/json")->withStatus(200);
        return $response;
    }

    /**
     * @OA\Get(
     *     tags={"tab"},
     *     path="/{ticket_slug}/tabs/{id}",
     *     operationId="getTab",
     *     summary= "Get specific Tab",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="ticket_slug",
     *        in="path",
     *        description = "Ticket slug",
     *        @OA\Schema (type="string")
     *      ),
     *     @OA\Parameter (
     *        name="id",
     *        in="path",
     *        description = "Tab id",
     *        @OA\Schema (type="integer")
     *      ),
     *       @OA\Response
     *        (response=200, description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/Tabs")
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getElement(Request $request, Response $response, $args): Response
    {
        $tab= $this->model->getByID($args);
        $response->getBody()->write(json_encode($tab));
        $response->withHeader("Content-Type","application/json")->withStatus(200);

        return $response;
    }

    /**
     * @OA\Put(
     *     tags={"tab"},
     *     path="/{ticket_slug}/tabs/{id}",
     *     operationId="editTab",
     *     summary= "Edit Tab",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="ticket_slug",
     *        in="path",
     *        description = "Ticket slug",
     *        @OA\Schema (type="string")
     *      ),
     *      @OA\Parameter (
     *        name="id",
     *        in="path",
     *        description = "Tab id",
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
     *                  example = {"name": "My tab v2"}
     *              )
     *          )
     *     ),
     *      @OA\Response(response="200",description="Tab updated"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function editElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["ticket_slug"] = $args["ticket_slug"];
        $this->model->editElement($args["id"],$params);
        $response->getBody()->write("Tab edit");
        return $response->withStatus(200);

    }

    /**
     * @OA\Delete(
     *     tags={"tab"},
     *     path="/{ticket_slug}/tabs/{id}",
     *     operationId="deleteTab",
     *     summary= "Delete Tab",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="ticket_slug",
     *        in="path",
     *        required=true,
     *        description = "Ticket slug",
     *        @OA\Schema (type="string")
     *      ),
     *      @OA\Parameter (
     *        name="id",
     *        in="path",
     *        required=true,
     *        description = "Tab id",
     *        @OA\Schema (type="integer")
     *      ),
     *      @OA\Response(response="200",description="Delete tab"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function deleteElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["ticket_slug"] = $args["ticket_slug"];
        $this->model->deleteElement($args["id"],$params);
        $response->getBody()->write("Deletion complete");
        return $response->withStatus(200);
    }
    /**
     * @OA\Post(
     *     tags={"tab"},
     *     path="/{ticket_slug}/tabs/create",
     *     operationId="createTab",
     *     summary= "Create Tab",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="ticket_slug",
     *        in="path",
     *        required=true,
     *        description = "Ticket slug",
     *        @OA\Schema (type="string")
     *      ),
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
     *      @OA\Response(response="201",description="Tab created"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function createElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["ticket_slug"] = $args["ticket_slug"];
        $this->model->createElement($params);
        $response->getBody()->write("Tab created");
        return $response->withStatus(200);
    }
}