<?php
require_once __DIR__.'/../vendor/autoload.php';

use \PHPCFEnv\CFEnv\CFServiceBindings;

$bindings = new CFServiceBindings(__DIR__.'/application.json', __DIR__.'/services.json');
$binding = $bindings->getMongoDBBinding();
$title = "No MongoDB Database Connected";
$connected = false;
if(!is_null($binding)) {
    $connected = true;
    $app = $bindings->getEnv()->getApplication();
    $limits = $app->getLimits();
    $client = $binding->getMongoDBClient();
    $service = $binding->getService();
    $databaseName = $binding->getDatabaseName();
    $database = $client->selectDatabase($databaseName);
    $collections = $database->listCollections();
    $title = "Collections in database $database";
}
?>

<html>
<head>
  <style>
    body {
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 12px;
        line-height: 1.42857143;
        color: #333;
        background-color: #fff;
    }
    h1 {
        padding: 4px;
        background-image: linear-gradient(to bottom, #337ab7 0%, #265a88 100%);
        color: white;
    }
    .column {
        float: left;
        width: 50%;
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    th {
        text-align: left;
    }
  </style>
</head>

<body>
<h1>PHP-CFEnv Demonstration</h1>

<h2><?php echo $title; ?></h2>
<?php if($connected) { ?>
<div class="row">
<div class="column">
<h3>Binding Information</h3>
<table><tbody>
<tr><th>Name</th><td><?php echo $service->getName()?></td></tr>
<tr><th>Label</th><td><?php echo $service->getLabel()?></td></tr>
<tr><th>Plan</th><td><?php echo $service->getPlan()?></td></tr>
<tr><th colspan="2">Tags</th></tr>
<?php foreach($service->getTags() as $tag) { ?>
<tr><td></td><td><?php echo $tag?></td></tr>
<?php } ?>
</tbody></table>
</div>

<div class="column">
<h3>Application Information</h3>
<table><tbody>
<tr><th>Name</th><td><?php echo $app->getName()?></td></tr>
<tr><th>URIs</th><td>
<?php foreach($app->getUris() as $uri) { ?>
<span class="uri"><a target="app" href="http://<?php echo $uri?>"><?php echo $uri?></a></span>
<?php } ?>
</td></tr>

<tr><th>Application Name</th><td><?php echo $app->getApplicationName()?></td></tr>
<tr><th>Application Id</th><td><?php echo $app->getApplicationID()?></td></tr>
<tr><th>Space Name</th><td><?php echo $app->getSpaceName()?></td></tr>
<tr><th>Instance Id</th><td><?php echo $app->getInstanceID()?></td></tr>
<tr><th>Instance Index</th><td><?php echo $app->getInstanceIndex()?></td></tr>
<tr><th>Limits</th></tr>
<tr><th>Mem</th><td><?php echo $limits->getMem()?></td></tr>
<tr><th>Disk</th><td><?php echo $limits->getDisk()?></td></tr>
<tr><th>File Descriptors</th><td><?php echo $limits->getFds()?></td></tr>

</tbody></table>

</div>
</div>

<h3>MongoDB Collections in this database</h3>
<ul>
<?php foreach($collections as $collection) { ?>
<li><?php echo $collection['name']?></li>
<?php } ?>
</ul>
<?php } ?>
