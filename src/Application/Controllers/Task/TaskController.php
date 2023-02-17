<?php

namespace App\Application\Controllers\Task;

use App\Application\Controllers\Ticket\TicketControllerInterface;
use App\Infrastructure\Repository\Tasks\TasksRepositoryInterface;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TaskController implements TicketControllerInterface
{

    private $model;

    public function __construct(TasksRepositoryInterface $model){
        $this->model = $model;
    }


    /**
     * @OA\Get(
     *     tags={"task"},
     *     path="/{section_id}/task",
     *     operationId="GetAllTasks",
     *     summary= "all the task associated with a section",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="section_id",
     *        in="path",
     *        description = "Section id",
     *        @OA\Schema (type="integer")
     *      ),
     *       @OA\Response
     *        (response=200, description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/Tasks")
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getAll(Request $request, Response $response, $args): Response
    {
        $tasks = $this->model->getAll($args);
        $response->getBody()->write(json_encode($tasks));
        $response->withHeader("Content-Type","application/json")->withStatus(200);
        return $response;
    }

    /**
     * @OA\Get(
     *     tags={"task"},
     *     path="/{section_id}/task/{id}",
     *     operationId="getTaskOrGetAllTasks",
     *     summary= "Can get a specific task by querying wit task or all the task without query",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="section_id",
     *        in="path",
     *        description = "Section id",
     *        @OA\Schema (type="integer")
     *      ),
     *     @OA\Parameter (
     *        name="id",
     *        in="path",
     *        description = "Task id",
     *        @OA\Schema (type="integer")
     *      ),
     *       @OA\Response
     *        (response=200, description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/Tasks")
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getElement(Request $request, Response $response, $args): Response
    {

        $task= $this->model->getByID($args);
        $response->getBody()->write(json_encode($task));
        $response->withHeader("Content-Type","application/json")->withStatus(200);
        return $response;
    }

    /**
     * @OA\Put(
     *     tags={"ticket"},
     *     path="/{section_id}/task/{id}",
     *     operationId="editTask",
     *     summary= "Edit Task",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="section_id",
     *        in="path",
     *        description = "Section id",
     *        @OA\Schema (type="integer")
     *      ),
     *     @OA\Parameter (
     *        name="id",
     *        in="path",
     *        description = "Task id",
     *        @OA\Schema (type="integer")
     *      ),
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  @OA\Property(
     *                      property="summary",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="name",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="status_id",
     *                      type="integer",
     *                  ),
     *                  example = {"name": "My checklist v2","status_id": 2}
     *              )
     *          )
     *     ),
     *      @OA\Response(response="200",description="Task updated"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function editElement(Request $request, Response $response, $args): Response
    {
        $this->model->editElement($args["id"], $request->getParsedBody());
        $response->getBody()->write("Task edit");
        return $response->withStatus(200);
    }

    /**
     * @OA\Delete(
     *     tags={"task"},
     *     path="/{section_id}/task/{id}",
     *     operationId="deleteTask",
     *     summary= "Delete Task",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="section_id",
     *        in="path",
     *        description = "Section id",
     *        @OA\Schema (type="integer")
     *      ),
     *     @OA\Parameter (
     *        name="id",
     *        in="path",
     *        description = "Task id",
     *        @OA\Schema (type="integer")
     *      ),
     *      @OA\Response(response="200",description="Delete task"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function deleteElement(Request $request, Response $response, $args): Response
    {
        $this->model->deleteElement($args["id"],$request->getQueryParams());
        $response->getBody()->write("Deletion complete");
        return $response->withStatus(200);
    }

    /**
     * @OA\Post(
     *     tags={"task"},
     *     path="/{section_id}/task",
     *     operationId="createTask",
     *     summary= "Create Task",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="section_id",
     *        in="path",
     *        required=true,
     *        description = "Section id",
     *        @OA\Schema (type="integer")
     *      ),
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  @OA\Property(
     *                      property="summary",
     *                      type="string"
     *                  ),
     *                   @OA\Property(
     *                      property="name",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="status_id",
     *                      type="integer",
     *                  ),
     *                  example = {"name": "My checklist of love", "summary": "Things to do" , "status_id": 2}
     *              )
     *          )
     *     ),
     *      @OA\Response(response="201",description="Task created"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function createElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["section_id"] = $args["section_id"];
        $this->model->createElement($params);
        $response->getBody()->write("Task created");
        return $response->withStatus(200);
    }

}