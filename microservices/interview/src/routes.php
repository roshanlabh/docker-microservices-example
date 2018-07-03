<?php

use Slim\Http\Request;
use Slim\Http\Response;
error_reporting(1);

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


// Routes

$app->get('/api/interview/[{cid}]', function (Request $request, Response $response, array $args) {
    $res = array();
    // Validating the user input
    if(isset($args['cid']) && $args['cid'] != ""){
        // Fetching details from Database
        $sth = $this->db->prepare("SELECT * FROM interview where cid=:cid");
        $sth->bindParam("cid", $args['cid']);
        $sth->execute();
        $res = $sth->fetch();
        if($res == false){
            $res["cid"] = $args['cid'];
            $res["status"] = 0;
        }
        $res["responseCode"] = 0;
    }else{
        $res["responseCode"] = 1;
        $res["responseText"] = "Invalid Candidate ID";
    }
    
    // Returning the response    
    return $this->response->withJson($res);
});



$app->post('/api/interview/[{cid}]', function (Request $request, Response $response, array $args) {
    $input = $request->getParsedBody();

    $res = array();
    // Validating the user input
    if(!isset($args['cid']) || $args['cid'] == ""){
        $res["responseCode"] = 1;
        $res["responseText"] = "Invalid Candidate ID";
        return $this->response->withJson($res);
    } elseif(!isset($input['rating']) || $input['rating'] == "" || !is_numeric($input['rating']) || $input['rating'] < 1 || $input['rating'] > 5){
        $res["responseCode"] = 2;
        $res["responseText"] = "Invalid Rating Provided";
        return $this->response->withJson($res);
    }

    $status = 1;
    $sql = "INSERT INTO interview (cid,status,rating,notes) VALUES (:cid,:status,:rating,:notes)";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("cid", $args['cid']);
    $sth->bindParam("status", $status);
    $sth->bindParam("rating", $input['rating']);
    $sth->bindParam("notes", $input['notes']);
    $sth->execute();
    $input['id'] = $this->db->lastInsertId();

    if($status){
        $res["responseCode"] = 0;
        $res["responseText"] = "Ratings has been submitted.";
    }else{
        $res["responseCode"] = 3;
        $res["responseText"] = "Some error occurred. Please try again.";
    }

    // Returning the response    
    return $this->response->withJson($res);
});


$app->put('/api/interview/[{cid}]', function (Request $request, Response $response, array $args) {
    $input = $request->getParsedBody();

    $res = array();
    // Validating the user input
    if(!isset($args['cid']) || $args['cid'] == ""){
        $res["responseCode"] = 1;
        $res["responseText"] = "Invalid Candidate ID";
        return $this->response->withJson($res);
    } elseif(!isset($input['rating']) || $input['rating'] == "" || !is_numeric($input['rating']) || $input['rating'] < 1 || $input['rating'] > 5){
        $res["responseCode"] = 2;
        $res["responseText"] = "Invalid Rating Provided---" . $input['notes'];
        return $this->response->withJson($res);
    }


    //Updating database//
    $sql = "UPDATE interview SET rating=:rating,notes=:notes WHERE cid=:cid";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("cid", $args['cid']);
    $sth->bindParam("rating", $input['rating']);
    $sth->bindParam("notes", $input['notes']);
    if($sth->execute()){
        $status = true;
    }else{
        $status = false;
    }

    if($status){
        $res["responseCode"] = 0;
        $res["responseText"] = "Ratings has been updated";
    }else{
        $res["responseCode"] = 3;
        $res["responseText"] = "Some error occurred. Please try again.";
    }

    // Returning the response    
    return $this->response->withJson($res);
    
    
});


// Catch-all route to serve a 404 Not Found page if none of the routes match
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});







