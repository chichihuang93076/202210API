<?php

declare(strict_types=1);

require dirname(__DIR__) . "/vendor/autoload.php";

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$parts = explode("/", $path);

$resourse = $parts[3];

$id = $parts[4] ?? null;

//echo $resourse, ", ", $id;

//echo $_SERVER["REQUEST_METHOD"], "\n";

if ($resourse != "tasks") {

  http_response_code(404);
  exit;
}

header("Content-type: application/json; charset=UTF-8");

$database = new Database();

$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);