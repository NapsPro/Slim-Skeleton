<?php

namespace App\Application\Controllers\Section;

use App\Infrastructure\Repository\Sections\SectionRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use OpenApi\Annotations as OA;
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
     *     summary= "Get all Sections",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="tab_id",
     *        in="path",
     *        description = "Tab id",
     *        @OA\Schema (type="integer")
     *      ),
     *       @OA\Response
     *        (response=200, description="List all Sections",
     *          @OA\JsonContent(type="array", @OA\Items (ref="#/components/schemas/Sections"))
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
     *     operationId="getSection",
     *     summary= "Get specific Section",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\Parameter (
     *        name="tab_id",
     *        in="path",
     *        description = "Tab id",
     *        @OA\Schema (type="integer")
     *      ),
     *     @OA\Parameter (
     *        name="id",
     *        in="path",
     *        description = "Section id",
     *        @OA\Schema (type="integer")
     *      ),
     *       @OA\Response
     *        (response=200, description="List all Sections",
     *          @OA\JsonContent(type="array", @OA\Items (ref="#/components/schemas/Sections"))
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
     *     summary= "Edit Section",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *      @OA\Parameter (
     *        name="tab_id",
     *        in="path",
     *        description = "Tab id",
     *        @OA\Schema (type="integer")
     *      ),
     *     @OA\Parameter (
     *        name="id",
     *        in="path",
     *        description = "Section id",
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
     *                  example = {"name": "My Section v2"}
     *              )
     *          )
     *     ),
     *      @OA\Response(response="200",description="Section updated"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function editElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["tab_id"] = $args["tab_id"];
        $this->model->editElement($args["id"], $params);
        $response->getBody()->write("Section edit");
        return $response->withStatus(200);
    }

    /**
     * @OA\Delete(
     *     tags={"section"},
     *     path="/{tab_id}/sections/{id}",
     *     operationId="deleteSection",
     *     summary= "Delete Section",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *       @OA\Parameter (
     *        name="tab_id",
     *        in="path",
     *        description = "Tab id",
     *        @OA\Schema (type="integer"),
     *      ),
     *     @OA\Parameter (
     *        name="id",
     *        in="path",
     *        description = "Section id",
     *        @OA\Schema (type="integer"),
     *      ),
     *      @OA\Response(response="200",description="Delete Section"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function deleteElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["tab_id"] = $args["tab_id"];
        $this->model->deleteElement($args["id"], $params);
        $response->getBody()->write("Deletion complete");
        return $response->withStatus(200);
    }

    /**
     * @OA\Post(
     *     tags={"section"},
     *     path="/{tab_id}/sections/create",
     *     operationId="createSection",
     *     summary= "Create Section",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *      @OA\Parameter (
     *        name="tab_id",
     *        in="path",
     *        description = "Tab id",
     *        @OA\Schema (type="integer")
     *      ),
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={"name"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                  ),
     *                  example = {"name": "My tab"}
     *              )
     *          )
     *     ),
     *      @OA\Response(response="201",description="Section created"),
     *      @OA\Response(response="400",description="Problem with request body"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function createElement(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();
        $params["tab_id"] = $args["tab_id"];
        $section = $this->model->createElement($params);
        $response->getBody()->write("Section created");
        return $response->withStatus(200);
    }
}