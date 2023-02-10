<?php

namespace App\Application\Controllers\Ticket;



use App\Infrastructure\Repository\Tickets\TicketRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TicketController implements TicketControllerInterface

{
    private $model;

    public function __construct(TicketRepositoryInterface $model){
        $this->model = $model;
    }

    /**
     * @OA\Get(
     *     tags={"ticket"},
     *     path="/tickets/{ticket_slug}",
     *     operationId="getAllTicket",
     *     summary= Get all Tickets
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
     *                      property="user_id",
     *                      type="integer"),
     *                  example = {"user_id"= 1}
     *                  )
     *              )
     *       )
     *       @OA\Response
     *        (response=200, description="List all Ticket",
     *          @OA\JsonContent(type="array", @OA\Items (ref="#/components/schemas/Ticket"))
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getAll(Request $request, Response $response, $args): Response
    {
        $tickets = $this->model->getAll(["user_id"=>13]);

        $response->getBody()->write(json_encode($tickets));
        $response->withHeader("Content-Type","application/json")->withStatus(200);

        return $response;

    }
    /**
     * @OA\Get(
     *     tags={"ticket"},
     *     path="/tickets/{ticket_slug}",
     *     operationId="getTicket",
     *     summary= Get specific Ticket
     *     @OA\Parameter (
     *        name:"ticket_slug",
     *        in="path",
     *        required=true,
     *        description = "Ticket slug"
     *        @OA\Schema (type="string")
     *      )
     *       @OA\Response
     *        (response=200, description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/Ticket")
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getElement(Request $request, Response $response, $args): Response
    {
         $ticket= $this->model->getByID($args);

         $response->getBody()->write(json_encode($ticket));
         $response->withHeader("Content-Type","application/json")->withStatus(200);

         return $response;
    }

    /**
     * @OA\Put(
     *     tags={"ticket"},
     *     path="/tickets/{ticket_slug}",
     *     operationId="editTicket",
     *     summary= Edit Ticket
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer"
     *                  ),
     *                   @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="status_id",
     *                      type="integer",
     *                      required=false
     *                  ),
     *                  example = {"name": "My checklist v2", "id": 1 , "status_id": 2}
     *              )
     *          )
     *     )
     *      @OA\Response(response="200",description="Ticket updated")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function editElement(Request $request, Response $response, $args): Response
    {
        $this->model->edit_element($request->getParsedBody());

        return $response->withStatus(200);
    }

    /**
     * @OA\Delete(
     *     tags={"ticket"},
     *     path="/tickets/{ticket_slug}",
     *     operationId="deleteTicket",
     *     summary= Delete Ticket
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
     *                      property="user_id",
     *                      type="integer"
     *                  ),
     *                   @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="status_id",
     *                      type="integer",
     *                      required=false
     *                  ),
     *                  example = {"name": "My checklist", "user_id": 1 , "status_id": 2}
     *              )
     *          )
     *     )
     *      @OA\Response(response="201",description="Ticket created")
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
     *     tags={"ticket"},
     *     path="/tickets/create",
     *     operationId="createTicket",
     *     summary= Create Ticket
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  @OA\Property(
     *                      property="user_id",
     *                      type="integer"
     *                  ),
     *                   @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="status_id",
     *                      type="integer",
     *                      required=false
     *                  ),
     *                  example = {"name": "My checklist", "user_id": 1 , "status_id": 2}
     *              )
     *          )
     *     )
     *      @OA\Response(response="201",description="Ticket created")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function createElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["user_id"] = 13; // TODO need to solve about user_id
        $this->model->create_element($params);

        return $response->withStatus(201);
    }
}