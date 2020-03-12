<?php
require_once __DIR__.'/../lib/vendor/autoload.php';

use \PHPCFEnv\CFEnv\CFServiceBindings;

$bindings = new CFServiceBindings();
$binding = $bindings->getMongoDBBinding();
$title = "No MongoDB Database Connected";
$collections = false;
if(!is_null($binding)) {
    $client = $binding->getMongoDBClient();
    $databaseName = $binding->getDatabaseName();
    $database = $client->selectDatabase($databaseName);
    $collections = $database->listCollections();
    $title = "Collections in database $database";
}
?>

<html>
<body>
<h1><?php echo $title; ?></h1>
<?php if($collections !== false) { ?>
<ul>
<?php foreach($collections as $collection) { ?>
<li><?php echo $collection['name']?></li>
<?php } ?>
</ul>
<?php } ?>
