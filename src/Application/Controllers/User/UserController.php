<?php

namespace App\Application\Controllers\User;

use App\Application\Controllers\Sessions\SessionControllerInterface;
use App\Application\Controllers\User\UserControllerInterface;
use OpenApi\Annotations as OA;
use App\Infrastructure\Repository\User\UserRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class UserController implements UserControllerInterface
{
    private $model;
    private $session_c;

    public function __construct(UserRepositoryInterface $model, SessionControllerInterface $session_con){
        $this->model = $model;
        $this->session_c = $session_con;
    }

    /**
     * @OA\Post(
     *     tags={"user"},
     *     path="/users/login",
     *     operationId="userLogin",
     *     summary= "User login",
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={"username","password"},
     *                  @OA\Property(
     *                      property="username",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  ),
     *                  example = {"name": "xD", "password":"lol"}
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Succesfull login",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Sessions")
     *        )
     *      ),
     *      @OA\Response(response="400",description="Problem with password or username"),
     *      @OA\Response(response="404",description="User not found"),
     * )
     */
    public function login(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();

        $user = $this->model->findUserByUsernamePassword($params);
        $session = $this->session_c->createSession($user);

        $response->getBody()->write(json_encode($session));
        $response->withHeader("Content-Type","application/json")->withStatus(201);

        return $response;
    }
    /**
     * @OA\Post(
     *     tags={"user"},
     *     path="/users/logout",
     *     operationId="userLogout",
     *     summary= "User logout",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *       *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={"access_token"},
     *                  @OA\Property(
     *                      property="access_token",
     *                      type="string",
     *                  ),
     *                  example = {"access_token": "secret expired token"},
     *              )
     *          )
     *     ),
     *      @OA\Response(response="200",description="Succesfull logout"),
     *      @OA\Response(response="500",description="Something went wrong"),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function logout(Request $request, Response $response, $args): Response{
        $this->session_c->deleteSession($request->getParsedBody());

        $response->getBody()->write("Deletion successful");
        $response->withStatus(200);

        return $response;
    }

    /**
     * @OA\Post(
     *     tags={"user"},
     *     path="/users/register",
     *     operationId="userRegister",
     *     summary= "User Register",
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={"username", "password", "email"},
     *                  @OA\Property(
     *                      property="username",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="password",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                  ),
     *                  example = {"username": "xD", "password":"lol", "email": "xD@gmail.com"}
     *              )
     *          )
     *     ),
     *      @OA\Response(response="201",description="User created"),
     *      @OA\Response(response="400",description="Problem with password or username"),
     *      @OA\Response(response="500",description="Something went wrong"),
     * )
     */
    public function register(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();

        $this->model->registerUser($params);

        $response->getBody()->write("Successful Registration");
        return $response->withStatus(200);
    }


    /**
     * @OA\Post(
     *   path="/users/refresh",
     *   tags={"users"},
     *   operationId="updateSession",
     *   summary="Refresh access token",
     *     @OA\Parameter (
     *      name="Authorization",
     *      in="header",
     *      @OA\Schema (type="string", required={"Authorization"})
     *     ),
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={"access_token","refresh_token","id"},
     *                  @OA\Property(
     *                      property="access_token",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="refresh_token",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="id",
     *                      type="int",
     *                  ),
     *
     *                  example = {"access_token": "secret expired token", "refresh_token":"secret refresh token", "id":1}
     *              )
     *          )
     *     ),
     *      @OA\Response
     *          (response=200, description="OK",
     *                 @OA\JsonContent(ref="#/components/schemas/Sessions")
     *          ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function updateSession(Request $request, Response $response): Response
    {

        $data = $this->session_c->updateSession($request->getParsedBody());
        $response->getBody()->write(json_encode($data));
        $response->withHeader("Content-Type","application/json")->withStatus(200);

        return $response;
    }
}