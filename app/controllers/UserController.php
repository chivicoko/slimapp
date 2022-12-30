<?php

namespace App\Controller;

use DB\DB;
use PDO;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
    private $db;

    public function __construct()
    {
        $this->db = (new DB())->connect();
    }

    public function index(Request $request, Response $response, $args){
        try {
            $query = $this->db->prepare("SELECT * FROM users");
            $query->execute();
            $users = $query->fetchAll();
            $response->getBody()->write(json_encode($users));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (PDOException $ex) {
            $response->getBody()->write(json_encode([ 'error' => $ex->getMessage() ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function getUser(Request $request, Response $response, $args) {
        $id = $args['id'];

        try {
            $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $user = $query->fetch();

            if (!$user) {
                $response->getBody()->write(json_encode([ 'error' => 'User not found' ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }

            $response->getBody()->write(json_encode($user));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (PDOException $ex) {
            $response->getBody()->write(json_encode([ 'error' => $ex->getMessage() ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
