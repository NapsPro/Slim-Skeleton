<?php

namespace App\Application\Controllers\Task;

use App\Application\Controllers\Ticket\TicketControllerInterface;
use App\Infrastructure\Repository\Tasks\TasksRepositoryInterface;
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
     *     operationId="getAllTasks",
     *     summary= Get all Tasks
     *     @OA\Parameter (
     *        name:"section_id",
     *        in="path",
     *        required=true,
     *        description = "Section id"
     *        @OA\Schema (type="integer")
     *      )
     *       @OA\Response
     *        (response=200, description="List all Tasks",
     *          @OA\JsonContent(type="array", @OA\Items (ref="#/components/schemas/Task"))
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getAll(Request $request, Response $response, $args): Response
    {
        //Todo try catch
        $tasks = $this->model->getAll($args);
        $response->getBody()->write(json_encode($tasks));
        $response->withHeader("Content-Type","application/json")->withStatus(200);
        return $response;
    }

    /**
     * @OA\Get(
     *     tags={"task"},
     *     path="/{section_id}/task",
     *     operationId="getTask",
     *     summary= Get specific Task
     *     @OA\Parameter (
     *        name:"section_id",
     *        in="path",
     *        required=true,
     *        description = "Section id"
     *        @OA\Schema (type="integer")
     *      )
     *      @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  @OA\Property(
     *                      property="task",
     *                      type="integer",
     *                      require=true
     *                  ),
     *                  example = {"task"=1}
     *                )
     *              )
     *            )
     *       @OA\Response
     *        (response=200, description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/Task")
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getQueryParams();
        $params["section_id"] = $args["section_id"];
        $task= $this->model->getByID($params);
        if ($task){
            $response->getBody()->write(json_encode($task));
            $response->withHeader("Content-Type","application/json")->withStatus(200);
        }else{
            $response->withStatus(404);
        }
        return $response;
    }

    /**
     * @OA\Put(
     *     tags={"ticket"},
     *     path="/{section_id}/task",
     *     operationId="editTask",
     *     summary= Edit Task
     *     @OA\Parameter (
     *        name:"section_id",
     *        in="path",
     *        required=true,
     *        description = "Section id"
     *        @OA\Schema (type="integer")
     *      )
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  @OA\Property(
     *                      property="summary",
     *                      type="string",
     *                      require=false
     *                  ),
     *                   @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      require=false
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
     *      @OA\Response(response="200",description="Task updated")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function editElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["section_id"] = $args["section_id"];

        return ($this->model->edit_element($params)) ?
            $response->withStatus(200): $response->withStatus(406);
    }

    /**
     * @OA\Delete(
     *     tags={"task"},
     *     path="/{section_id}/task",
     *     operationId="deleteTask",
     *     summary= Delete Task
     *     @OA\Parameter (
     *        name:"section_id",
     *        in="path",
     *        required=true,
     *        description = "Section id"
     *        @OA\Schema (type="integer")
     *      )
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  @OA\Property(
     *                      property="task_id",
     *                      type="integer"
     *                  ),
     *                  example = {"task_id": 1}
     *              )
     *          )
     *     )
     *      @OA\Response(response="200",description="Delete task")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function deleteElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getQueryParams();
        $params["section_id"] = $args["section_id"];
        return ($this->model->delete_element($params)) ?
            $response->withStatus(200): $response->withStatus(403);
    }

    /**
     * @OA\Post(
     *     tags={"task"},
     *     path="/{section_id}/task",
     *     operationId="createTask",
     *     summary= Create Task
     *     @OA\Parameter (
     *        name:"section_id",
     *        in="path",
     *        required=true,
     *        description = "Section id"
     *        @OA\Schema (type="integer")
     *      )
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  @OA\Property(
     *                      property="summary",
     *                      type="string"
     *                      required=false
     *                  ),
     *                   @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      required=false
     *                  ),
     *                  @OA\Property(
     *                      property="status_id",
     *                      type="integer",
     *                      required=false
     *                  ),
     *                  example = {"name": "My checklist of love", "summary": "Things to do" , "status_id": 2}
     *              )
     *          )
     *     )
     *      @OA\Response(response="201",description="Task created")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function createElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["section_id"] = $args["section_id"];
        $this->model->create_element($params);
        return $response->withStatus(200);
    }

    public function distributor(Request $request, Response $response, $args): Response
    {
        switch ($request->getMethod()){
            case "GET":
                    if (array_key_exists("task", $request->getQueryParams())){
                        return $this->getElement($request, $response, $args);
                    }else{
                        return $this->getAll($request, $response, $args);
                    }

            case "POST":
                return $this->createElement($request, $response, $args);


            case "PUT":
                    return $this->editElement($request, $response, $args);

            case "DELETE":
                if (array_key_exists("task", $request->getQueryParams())){
                    return $this->deleteElement($request, $response, $args);
                }else{
                    return $response->withStatus(403);
                }
            default:
                return $response->withStatus(405);
        }

    }
}