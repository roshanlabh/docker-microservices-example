<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
/*
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
*/

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

// get all users
$app->get('/users', function (Request $request, Response $response, array $args) {
    $this->logger->info("Slim-Skeleton '/users' route");

    $filter = [];
    $options = [
        'sort' => ['_id' => -1],
    ];

    // Query Class
    $query = new MongoDB\Driver\Query($filter, $options);
    
    // Output of the executeQuery will be object of MongoDB\Driver\Cursor class
    $cursor = $this->mongo->executeQuery('onlinestore.products', $query);

    // echo "<pre>"; print_r($cursor->toArray());
    /*
    $sth = $this->db->prepare("SELECT * FROM users ORDER BY id");
    $sth->execute();
    $users = $sth->fetchAll();
    echo "<pre>"; print_r($cursor->toArray()); exit;
    return $this->response->withJson($users);
    */
    return $this->response->withJson($cursor->toArray());
});

// Retrieve user with id 
$app->get('/user/[{id}]', function ($request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/user/:id' route");
    $sth = $this->db->prepare("SELECT * FROM users WHERE id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    $users = $sth->fetchObject();
    return $this->response->withJson($users);
});

// Search for user with given search term in their name
$app->get('/users/search/[{query}]', function ($request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/users/search/:query' route");
    $sth = $this->db->prepare("SELECT * FROM users WHERE UPPER(name) LIKE :query ORDER BY name");
    $query = "%".$args['query']."%";
    $sth->bindParam("query", $query);
    $sth->execute();
    $users = $sth->fetchAll();
    return $this->response->withJson($users);
});

// Add a new user
$app->post('/user', function ($request, $response) {
    $this->logger->info("Slim-Skeleton '/user' POST");
    $input = $request->getParsedBody();
    // echo "<pre>"; print_r($input); echo "</pre>"; 
    $sql = "INSERT INTO users (`name`, `email`, `password`, `type`) VALUES (:name, :email, :password, :type)";
    // echo "QUERY: $sql"; exit;
    $sth = $this->db->prepare($sql);
    $sth->bindParam("name", $input['name']);
    $sth->bindParam("email", $input['email']);
    $sth->bindParam("password", $input['password']);
    $sth->bindParam("type", $input['type']);
    $sth->execute();
    $input['id'] = $this->db->lastInsertId();
    return $this->response->withJson($input);
});

// DELETE a user with given id
$app->delete('/user/[{id}]', function ($request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/users' route");
    $sth = $this->db->prepare("DELETE FROM users WHERE id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    $users = $sth->fetchAll();
    return $this->response->withJson($users);
});

// Update user with given id
$app->put('/user/[{id}]', function ($request, $response, $args) {
    $input = $request->getParsedBody();
    $sql = "UPDATE users SET `name`=:name, `email`=:email, `password`=:password, `type`=:type WHERE id=:id";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("id", $args['id']);
    $sth->bindParam("name", $input['name']);
    $sth->bindParam("email", $input['email']);
    $sth->bindParam("password", $input['password']);
    $sth->bindParam("type", $input['type']);
    $sth->execute();
    $input['id'] = $args['id'];
    return $this->response->withJson($input);
});