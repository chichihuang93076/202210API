<?php

declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

//require dirname(__DIR__) . "/vendor/autoload.php";

//set_error_handler("ErrorHandler::handleError");
//set_exception_handler("ErrorHandler::handleException");

//http://localhost/2022api/api/tasks
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

$database = new Database();
// header X-API-KEY
//$api_key = $_SERVER["HTTP_X_API_KEY"];

$user_gateway = new UserGateway($database);

$auth = new Auth($user_gateway);

if ( !$auth->authenticationAPIKey())
{
  exit;
}

$user_id = $auth->getUserID();

$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway, $user_id);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);