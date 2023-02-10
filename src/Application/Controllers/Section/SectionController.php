<?php

namespace App\Application\Controllers\Section;

use App\Infrastructure\Repository\Sections\SectionRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SectionController implements SectionControllerInterface
{
    private $model;

    public function __construct(SectionRepositoryInterface $model){
        $this->model = $model;
    }

    /**
     * @OA\Get(
     *     tags={"section"},
     *     path="/{tab_id}/sections",
     *     operationId="getAllSections",
     *     summary= Get all Sections
     *     @OA\Parameter (
     *        name:"tab_id",
     *        in="path",
     *        required=true,
     *        description = "Tab id"
     *        @OA\Schema (type="integer")
     *      )
     *       @OA\Response
     *        (response=200, description="List all Sections",
     *          @OA\JsonContent(type="array", @OA\Items (ref="#/components/schemas/Section"))
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getAll(Request $request, Response $response, $args): Response
    {
        $sections = $this->model->getAll($args);
        $response->getBody()->write(json_encode($sections));
        $response->withHeader("Content-Type","application/json")->withStatus(200);
        return $response;
    }

    /**
     * @OA\Get(
     *     tags={"section"},
     *     path="/{tab_id}/sections/{id}",
     *     operationId="getAllSections",
     *     summary= Get all Sections
     *     @OA\Parameter (
     *        name:"tab_id",
     *        in="path",
     *        required=true,
     *        description = "Tab id"
     *        @OA\Schema (type="integer")
     *      )
     *     @OA\Parameter (
     *        name:"id",
     *        in="path",
     *        required=true,
     *        description = "Section id"
     *        @OA\Schema (type="integer")
     *      )
     *       @OA\Response
     *        (response=200, description="List all Sections",
     *          @OA\JsonContent(type="array", @OA\Items (ref="#/components/schemas/Section"))
     *      ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getElement(Request $request, Response $response, $args): Response
    {
        $section= $this->model->getByID($args);

        $response->getBody()->write(json_encode($section));
        $response->withHeader("Content-Type","application/json")->withStatus(200);

        return $response;
    }

    /**
     * @OA\Put(
     *     tags={"section"},
     *     path="/{tab_id}/sections/{id}",
     *     operationId="editSection",
     *     summary= Edit Section
     *      @OA\Parameter (
     *        name:"tab_id",
     *        in="path",
     *        required=true,
     *        description = "Tab id"
     *        @OA\Schema (type="integer")
     *      )
     *     @OA\Parameter (
     *        name:"id",
     *        in="path",
     *        required=true,
     *        description = "Section id"
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
     *                  example = {"name": "My Section v2"}
     *              )
     *          )
     *     )
     *      @OA\Response(response="200",description="Section updated")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function editElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["id"] = $args["id"];
        $params["tab_id"] = $args["tab_id"];
        $this->model->edit_element($params);

        return $response->withStatus(200);
    }

    /**
     * @OA\Delete(
     *     tags={"section"},
     *     path="/{tab_id}/sections/{id}",
     *     operationId="deleteStatus",
     *     summary= Delete Status
     *       @OA\Parameter (
     *        name:"tab_id",
     *        in="path",
     *        required=true,
     *        description = "Tab id"
     *        @OA\Schema (type="integer")
     *      )
     *     @OA\Parameter (
     *        name:"id",
     *        in="path",
     *        required=true,
     *        description = "Section id"
     *        @OA\Schema (type="integer")
     *      )
     *      @OA\Response(response="200",description="Delete Section")
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
     *     tags={"section"},
     *     path="/{tab_id}/sections/create",
     *     operationId="createSection",
     *     summary= Create Section
     *      @OA\Parameter (
     *        name:"tab_id",
     *        in="path",
     *        required=true,
     *        description = "Tab id"
     *        @OA\Schema (type="integer")
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
     *      @OA\Response(response="201",description="Section created")
     *      @OA\Response(response="400",description="Problem with request body")
     *      @OA\Response(response="500",description="Something went wrong")
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function createElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["tab_id"] = $args["tab_id"];
        $this->model->create_element($params);

        return $response->withStatus(200);
    }
}