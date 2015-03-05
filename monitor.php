<?php
/**
 * A command-line script to display server health using traffic lights.
 *
 * @author    Max Vogler <max@itstrategen.de>
 * @author    Stephan Bothur <stephan@itstrategen.com>
 * @editor    Sebastian Krein <sebastian@itstrategen.de>
 *
 * @copyright 2015, ITstrategen GmbH
 * @license   GNU GPL v3.0
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Noodlehaus\Config;

// Initialize Composer autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Load CLI arguments
$filename = $_SERVER['argv'][0];
$arguments = CommandLine::parseArgs($_SERVER['argv']);

// Enable debug mode if --debug is present
if (isset($arguments['debug'])) {
    error_reporting(E_ALL);
}

// Determine configuration file
$configFile = isset($arguments['config']) ?
    $arguments['config'] :
    dirname(__FILE__) . '/config/config.json';

// Load configuration file
$config = new Config($configFile);

$container = (new \DI\ContainerBuilder())->build();
$container->set('Noodlehaus\Config', $config);
$container->set('Monolog\Logger', DI\factory(function () {
    $logger = new Logger('MonitoringService');
    $handler = new StreamHandler("php://stdout");
    $handler->setFormatter(new LineFormatter("%message%\n"));
    $logger->pushHandler($handler);
    return $logger;
}));

// Initialize the monitoring service
$monitoring = $container->make('ITstrategen\MonitoringService', ['id' => null]);

// parse the command-line argument
$command = isset($arguments[0]) ? $arguments[0] : 'run';

switch ($command) {
    case 'off':
        $monitoring->off();
        break;

    case 'run':
        $monitoring->run();
        break;

    default:
        die("Unknown argument $command.\nUsage: php {$filename} [run|off] [--config=config/config.json] [--debug]\n");
}


