<?php

use Slim\Http\Request;
use Slim\Http\Response;

//CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Home
$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    // return $this->renderer->render($response, 'index.phtml', $args);
    return $this->response->withRedirect('/api/users', 301);
});

// get all users
$app->get('/api/users', function (Request $request, Response $response, array $args) {
    $this->logger->info("Slim-Skeleton '/users' route");

    $filter = [];
    $options = [
        'sort' => ['_id' => -1],
        'projection' => ['_id' => 0]
    ];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $this->mongo->executeQuery($this->sCollectionName, $query);

    $obj = new stdClass;
    $obj->responseCode = 0;
    $obj->responseText = "Demo Response text";
    $obj->Users = $cursor->toArray();
    return $this->response->withJson($obj);
});

// Retrieve user with id 
$app->get('/api/user/[{id}]', function ($request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/user/:id' route");

    // $filter = ['_id' => new MongoDB\BSON\ObjectID($args['id'])];
    $filter = ['cid' => (int) $args['id']];
    $options = [
        'projection' => ['_id' => 0]
        ];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $this->mongo->executeQuery($this->sCollectionName, $query);
    
    $obj = new stdClass;
    $obj->responseCode = 0;
    $obj->responseText = "Demo Response text";
    $obj->Users = $cursor->toArray();
    return $this->response->withJson($obj);
});

// Add a new user
$app->post('/api/user', function ($request, $response) {
    $this->logger->info("Slim-Skeleton '/user' POST");
    $input = $request->getParsedBody();
    $input['cid'] = rand(1, 100000);
        
    $insRec = new MongoDB\Driver\BulkWrite;
    $_id = $insRec->insert(['cid' => $input['cid'], 'name' => $input['name'], 'email' => $input['email'], 'mobile' => $input['mobile']]);
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result = $this->mongo->executeBulkWrite($this->sCollectionName, $insRec, $writeConcern);

    // $newID = (array) $_id;
    // $input['id'] = $newID['oid'];
    
    $obj = new stdClass;
    if($result) {       // TODO: Need to see how can we exact whether user was saved or not
        $obj->responseCode = 0;
        $obj->responseText = "Successfully Registered.";
    } else {
        $obj->responseCode = 1;
        $obj->responseText = "Some error occurred. Please try again.";
    }
    
    return $this->response->withJson($obj, 201);
});

// Update user with given id
$app->put('/api/user/[{id}]', function ($request, $response, $args) {
    $input = $request->getParsedBody();
    
    $insRec = new MongoDB\Driver\BulkWrite;
    // $insRec->update(['_id' => new MongoDB\BSON\ObjectID($args['id'])],['$set' =>['name' => $input['name'], 'email' => $input['email'], 'mobile' => $input['mobile']]], ['multi' => false, 'upsert' => false]);
    $insRec->update(['cid' => (int) $args['id']],['$set' =>['name' => $input['name'], 'email' => $input['email'], 'mobile' => $input['mobile']]], ['multi' => false, 'upsert' => false]);
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result = $this->mongo->executeBulkWrite($this->sCollectionName, $insRec, $writeConcern);

    $input['id'] = $args['id'];

    $obj = new stdClass;
    if($result) {  // TODO: Need to see how can we exact whether user was saved or not
        $obj->responseCode = 0;
        $obj->responseText = "Successfully Updated.";
    } else {
        $obj->responseCode = 1;
        $obj->responseText = "Some error occurred. Please try again.";
    }

    return $this->response->withJson($obj);
});

// DELETE a user with given id
$app->delete('/api/user/[{id}]', function ($request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/users' route");
    
    $delRec = new MongoDB\Driver\BulkWrite;
    // $delRec->delete(['_id' => new MongoDB\BSON\ObjectID($args['id'])], ['limit' => 1]);
    $delRec->delete(['cid' => (int) $args['id']], ['limit' => 1]);
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result = $this->mongo->executeBulkWrite($this->sCollectionName, $delRec, $writeConcern);

    $obj = new stdClass;
    if($result) {  // TODO: Need to see how can we exact whether user was saved or not
        $obj->responseCode = 0;
        $obj->responseText = "Successfully deleted.";
    } else {
        $obj->responseCode = 1;
        $obj->responseText = "Some error occurred. Please try again.";
    }
    return $this->response->withJson($obj);
});