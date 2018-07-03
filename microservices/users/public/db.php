<?php

// connect to mongodb
$manager = new MongoDB\Driver\Manager("mongodb://rlabh:rlabh123@mongodb:27017");

$command = new MongoDB\Driver\Command(array("ping" => 1));
try {
    $result = $manager->executeCommand("slimapp", $command);
    echo "<pre>Hello: ";
    var_dump($result, $result->toArray());
    echo "</pre>";
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

$manager = new MongoDB\Driver\Manager("mongodb://rlabh:rlabh123@mongodb:27017");
echo "<pre>";
var_dump($manager);
echo "</pre>";

$input = [
    'cid' => rand(1, 100),
    'name' => '',
    'email' => '',
    'mobile' => ''
];

$insRec = new MongoDB\Driver\BulkWrite;
$_id = $insRec->insert(['cid' => $input['cid'], 'name' => $input['name'], 'email' => $input['email'], 'mobile' => $input['mobile']]);
$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
$result = $manager->executeBulkWrite('slimapp.users', $insRec, $writeConcern);

echo ($result) ? "<br />Yes": "<br />No";

$filter = [];
$options = [
    'sort' => ['_id' => -1],
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('slimapp.users', $query);
foreach ($cursor as $document) {
    echo "<pre>cursor: "; print_r($document); echo "</pre>";
}
