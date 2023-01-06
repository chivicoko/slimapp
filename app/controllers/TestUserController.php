<?php

namespace App\Controller;

use App\Constants\Constants;
use App\Model\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TestUserController {

    public function index(Request $request, Response $response) {
        $users = User::all();
        $response->getBody()->write($users->toJson());
        return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(200);
    }    

    public function findById(Request $request, Response $response, $args) {
        $id = $args['id'];
        $user = User::find($id);

        if(!$user) {
            $response->getBody()->write(json_encode(['error' => 'User not found']));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(404);
        }
        
        $response->getBody()->write($user->toJson());
        return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(200);
    }
    
    public function create(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        
        $createdAt = date('Y-m-d H:i:s');

        $user = User::create([
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'created_at' => $createdAt
        ]);
        $user->save();

        $response->getBody()->write($user->toJson());
        return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(200);
    }
    
    public function update(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $id = $args['id'];
        $user = User::find($id);
        
        if(!$user) {
            $response->getBody()->write(json_encode(['error' => 'User not found']));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(404);
        }

        $user->user_name = $data['user_name'];
        $user->save();
        
        $response->getBody()->write($user->toJson());
        return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(200);
    }
    
    public function delete(Request $request, Response $response, $args) {
        $id = $args['id'];
        $user = User::find($id);
        
        if(!$user) {
            $response->getBody()->write(json_encode(['error' => 'User not found']));
            return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(404);
        }

        $user->delete();
        
        $response->getBody()->write(json_encode(['message' => 'User deleted successfully']));
        return $response->withHeader(Constants::CONTENT_TYPE_HEADER, Constants::APPLICATION_JSON)->withStatus(200);
    }
       

}