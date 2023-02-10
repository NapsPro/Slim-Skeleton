<?php

namespace App\Application\Controllers\Tab;

use App\Infrastructure\Repository\Tabs\TabsRepositoryInterface ;
use Psr\Http\Message\ResponseInterface as Response;
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
     *     summary= Get all Tabs
     *     @OA\Parameter (
     *        name:"ticket_slug",
     *        in="path",
     *        required=true,
     *        description = "Ticket slug"
     *        @OA\Schema (type="string")
     *      )
     *       @OA\Response
     *        (response=200, description="List all Tabs",
     *          @OA\JsonContent(type="array", @OA\Items (ref="#/components/schemas/Tab"))
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
     *     summary= Get specific Tab
     *     @OA\Parameter (
     *        name:"ticket_slug",
     *        in="path",
     *        required=true,
     *        description = "Ticket slug"
     *        @OA\Schema (type="string")
     *      )
     *     @OA\Parameter (
     *        name:"id",
     *        in="path",
     *        required=true,
     *        description = "Tab id"
     *        @OA\Schema (type="integer")
     *      )
     *       @OA\Response
     *        (response=200, description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/Tab")
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getElement(Request $request, Response $response, $args): Response
    {
        $tab= $this->model->getByID($args);
        if ($tab){
            $response->getBody()->write(json_encode($tab));
            $response->withHeader("Content-Type","application/json")->withStatus(200);
        }else{
            $response->withStatus(404);
        }
        return $response;
    }

    /**
     * @OA\Put(
     *     tags={"tab"},
     *     path="/{ticket_slug}/tabs/{id}",
     *     operationId="editTab",
     *     summary= Edit Tab
     *     @OA\Parameter (
     *        name:"ticket_slug",
     *        in="path",
     *        required=true,
     *        description = "Ticket slug"
     *        @OA\Schema (type="string")
     *      )
     *      @OA\Parameter (
     *        name:"id",
     *        in="path",
     *        required=true,
     *        description = "Tab id"
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
     *                  example = {"name": "My tab v2"}
     *              )
     *          )
     *     )
     *      @OA\Response(response="200",description="Tab updated")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function editElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["ticket_slug"] = $args["ticket_slug"];

        return ($this->model->edit_element($params)) ?
            $response->withStatus(200): $response->withStatus(406);
    }

    /**
     * @OA\Delete(
     *     tags={"tab"},
     *     path="/{ticket_slug}/tabs/{id}",
     *     operationId="deleteTab",
     *     summary= Delete Tab
     *     @OA\Parameter (
     *        name:"ticket_slug",
     *        in="path",
     *        required=true,
     *        description = "Ticket slug"
     *        @OA\Schema (type="string")
     *      )
     *      @OA\Parameter (
     *        name:"id",
     *        in="path",
     *        required=true,
     *        description = "Tab id"
     *        @OA\Schema (type="integer")
     *      )
     *      @OA\Response(response="200",description="Delete tab")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function deleteElement(Request $request, Response $response, $args): Response
    {
        return ($this->model->delete_element($args)) ?
            $response->withStatus(200): $response->withStatus(403);
    }
    /**
     * @OA\Post(
     *     tags={"tab"},
     *     path="/{ticket_slug}/tabs/create",
     *     operationId="createTab",
     *     summary= Create Tab
     *     @OA\Parameter (
     *        name:"ticket_slug",
     *        in="path",
     *        required=true,
     *        description = "Ticket slug"
     *        @OA\Schema (type="string")
     *      )
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
     *      @OA\Response(response="201",description="Tab created")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function createElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["ticket_slug"] = $args["ticket_slug"];
        return ($this->model->create_element($params)) ?
            $response->withStatus(200): $response->withStatus(403);
    }
}