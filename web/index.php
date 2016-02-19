<?php
use josegonzalez\Dotenv\Loader as Dotenv;
use Radar\Adr\Boot;
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Zend\Diactoros\Response as Response;
use Zend\Diactoros\ServerRequestFactory as ServerRequestFactory;

/**
 * Bootstrapping
 */
require '../vendor/autoload.php';

Dotenv::load([
    'filepath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env',
    'toEnv' => true,
]);

$boot = new Boot();
$adr = $boot->adr();

/**
 * Middleware
 */
$adr->middle(new ResponseSender());
$adr->middle(new ExceptionHandler(new Response()));
$adr->middle('Radar\Adr\Handler\RoutingHandler');
$adr->middle('Radar\Adr\Handler\ActionHandler');

/**
 * Routes
 */
 

$adr->put('UpdateShift', '/shift/{id}', ['Scheduler\Domain\ApplicationService', 'updateShift']);
$adr->post('CreateShift', '/createShift', ['Scheduler\Domain\ApplicationService', 'createShift']);
$adr->get('Shift', '/shift/{id}', ['Scheduler\Domain\ApplicationService', 'readShift']);
$adr->get('User', '/user/{id}', ['Scheduler\Domain\ApplicationService', 'readUser']);
$adr->get('ReadSchedule', '/readSchedule', ['Scheduler\Domain\ApplicationService', 'readSchedule']);
$adr->get('ReadUserSchedule', '/readUserSchedule', ['Scheduler\Domain\ApplicationService', 'readUserSchedule']);
$adr->get('ReadUserWeeklyHours', '/readUserWeeklyHours', ['Scheduler\Domain\ApplicationService', 'readUserWeeklyHours']);
$adr->get('ReadCoworkers', '/readCoworkers', ['Scheduler\Domain\ApplicationService', 'readCoworkers']);
 
/**
 * Run
 */
$adr->run(ServerRequestFactory::fromGlobals(), new Response());
