<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$app->setBasePath("/slimAPI");

$app->get('/users/all', function (Request $request, Response $response) {
   
    $sql = "Select * from users";
    try{
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $friends = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->getBody()->write(json_encode($friends));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    }catch (PDOException $e){
        $error = array(
            "message"=> $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }

    
});
$app->get('/users/{id}', function (Request $request, Response $response, array $args) {
   $id = $args['id'];
    $sql = "Select * from users where id = $id";
    try{
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $friend = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $response->getBody()->write(json_encode($friend));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    }catch (PDOException $e){
        $error = array(
            "message"=> $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }

    
});
$app->post('/users/add', function (Request $request, Response $response, array $args) {
   $name = $request->getParam('name');
   $email = $request->getParam('email');
   $phone = $request->getParam('phone');
    $sql = "insert into users (name, email, phone) values (:name, :email, :phone)";
    try{
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->blindParam(':name', $name);
        $stmt->blindParam(':email', $email);
        $stmt->blindParam(':phone', $phone);

        $result = $stmt->execute();
        $db = null;
        $response->getBody()->write(json_encode($result));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    }catch (PDOException $e){
        $error = array(
            "message"=> $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }

    
});
$app->delete('/users/delete/{id}', function (Request $request, Response $response, array $args) {
   $id = $args['id'];
    $sql = "Delete from users where id = $id";
    try{
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $result= $stmt->execute();
        $db = null;
        $response->getBody()->write(json_encode($result));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    }catch (PDOException $e){
        $error = array(
            "message"=> $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }

    
});