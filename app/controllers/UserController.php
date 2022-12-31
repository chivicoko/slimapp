<?php

namespace App\Controller;

use App\Constants\Constants;
use DB\DB;
use LDAP\Result;
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

    // getting all users
    public function index(Request $request, Response $response, $args){
        try {
            $query = $this->db->prepare("SELECT * FROM users");
            $query->execute();
            $users = $query->fetchAll();
            $response->getBody()->write(json_encode($users));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(200);
        } catch (PDOException $ex) {
            $response->getBody()->write(json_encode([ 'error' => $ex->getMessage() ]));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(500);
        }
    }

    // get a single user
    public function getUser(Request $request, Response $response, $args) {
        $id = $args['id'];

        try {
            $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $user = $query->fetch();

            // if user does not exist
            if (!$user) {
                $response->getBody()->write(json_encode([ 'error' => 'User not found' ]));
                return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(404);
            }

            $response->getBody()->write(json_encode($user));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(200);
        } catch (PDOException $ex) {
            $response->getBody()->write(json_encode([ 'error' => $ex->getMessage() ]));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(500);
        }
    }

    // creating a user
    public function create(Request $request, Response $response, $args) {
        $requestData = $request->getParsedBody();

        // so it can receive json data, instead of the above, use json_decode():
        // $requestData = json_decode($request->getBody(), true); //but since we have attached middleware, the former can now serve

        $fullName = $requestData['user_name'];
        $email = $requestData['email'];
        $gender = $requestData['gender'];
        $createdAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO users (user_name, email, gender, created_at)
            VALUES (:user_name, :email, :gender, :created_at)";

        try {
            $query = $this->db->prepare($sql);
            $query->bindParam(':user_name', $fullName);
            $query->bindParam(':email', $email);
            $query->bindParam(':gender', $gender);
            $query->bindParam(':created_at', $createdAt);
            $query->execute();

            $response->getBody()->write(json_encode([ 'message' => 'User created successfully' ]));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(200);
        } catch (PDOException $ex) {
            $response->getBody()->write(json_encode([ 'error' => $ex->getMessage() ]));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(400);
        }
    }

    // Updating a user
    public function update(Request $request, Response $response, $args) {
        $id = $args['id'];
        $fullName = $request->getParsedBody()['user_name'];
        $email = $request->getParsedBody()['email'];

        try {
            $query = $this->db->prepare("UPDATE users SET user_name = :user_name, email = :email WHERE id = $id");
            $query->bindParam(':user_name', $fullName);
            $query->bindParam(':email', $email);
            $query->execute();

            $response->getBody()->write(json_encode([ 'message' => 'User updated successfully' ]));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(200);
        } catch (PDOException $ex) {
            $response->getBody()->write(json_encode([ 'error' => $ex->getMessage() ]));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(400);
        }
    }
    
    // Deleting a user
    public function delete(Request $request, Response $response, $args) {
        $id = $args['id'];

        try {
            $query = $this->db->prepare("DELETE FROM users WHERE id = $id");
            $query->execute();

            $response->getBody()->write(json_encode([ 'message' => 'User deleted successfully' ]));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(200);
        } catch (PDOException $ex) {
            $response->getBody()->write(json_encode([ 'error' => $ex->getMessage() ]));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(400);
        }
    }
    

}
