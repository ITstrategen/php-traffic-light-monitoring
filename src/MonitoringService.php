<?php

/*
 * PHP Traffic Light Monitoring
 * Copyright (C) 2015 ITstrategen GmbH
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

namespace ITstrategen;


use DI\Container;
use Exception;
use ITstrategen\outputs\Output;
use Monolog\Logger;
use Noodlehaus\Config;

/**
 * A monitoring service, the heart of this application. It parses the configuration, instantiates inputs and outputs
 * and routes the status values between them.
 *
 * @package ITstrategen
 * @author Max Vogler <max@itstrategen.de>
 * @copyright 2015, ITstrategen GmbH
 */
class MonitoringService extends Configurable
{

    const KEY_CLASS = 'class';

    /**
     * @var array
     */
    private $instances = [];

    /**
     * @var Status[]
     */
    private $status = [];

    /** @var Logger */
    private $logger;

    /** @var Container */
    private $container;

    /**
     * Creates a new MonitoringService.
     *
     * @param string $id the id, used as namespace for getting configuration values
     * @param Config $config the configuration
     * @param Container $container the DI container, used for instantiating inputs and outputs
     * @param Logger $logger the logger
     */
    public function __construct($id, Config $config, Container $container, Logger $logger)
    {
        parent::__construct($id, $config);
        $this->container = $container;
        $this->logger = $logger;
    }

    /**
     * Gets the logger.
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Logs a message.
     * @param string $str the log message (may be formatted printf-like)
     * @param array $vars optional arguments for the string formatting
     */
    protected function log($str, array $vars = [])
    {
        $this->getLogger()->addInfo(vsprintf($str, $vars));
    }

    /**
     * Prints general information about PHP Traffic Light Monitoring.
     */
    protected function printHeader()
    {
        // Disabled, for the sake of shorter & more readable log messages

        //$this->log("PHP Traffic Light Monitoring - Copyright (C) 2015 ITstrategen GmbH");
        //$this->log("This program comes with ABSOLUTELY NO WARRANTY.");
        //$this->log("This is free software, and you are welcome to redistribute it.");
        //$this->log("");
    }

    /**
     * Prints general information about PHP Traffic Light Monitoring.
     */
    protected function printFooter()
    {
        // currently unused!
    }

    /**
     * Runs the monitoring service, querying inputs and transmitting the status to outputs.
     * @throws Exception
     */
    public function run()
    {
        $this->printHeader();

        foreach ($this->getConfig('routes') as $route) {
            $inputId = "inputs.{$route['from']}";
            $outputId = "outputs.{$route['to']}";

            $input = $this->getConfig($inputId);
            $output = $this->getConfig($outputId);

            if (!$input) {
                throw new Exception("Configuration {$inputId} not found.");
            }

            if (!$output) {
                throw new Exception("Configuration {$outputId} not found.");
            }

            /** @var Output $outputService */
            $outputService = $this->instantiateClass($outputId);
            try {
                $status = $this->determineStatus($inputId);
                $outputService->updateOutput($status);
            } catch(\Exception $e) {
                $this->log("%s (%s)", [$e->getMessage(), $e->getTraceAsString()]);
            }

            $this->log("Routed status '%s' from %s -> %s", [$status->getName(), $inputId, $outputId]);
        }

        $this->printFooter();
    }

    /**
     * Returns all configured outputs.
     * @return Output[]
     * @throws Exception
     */
    public function getOutputs()
    {
        $outputs = [];

        foreach ($this->getConfig('outputs') as $id => $config) {
            $id = "outputs.$id";
            $outputs[$id] = $this->instantiateClass($id, $config);
        }

        return $outputs;
    }

    /**
     * Turns all outputs off.
     */
    public function off()
    {
        $this->printHeader();

        foreach ($this->getOutputs() as $id => $output) {
            $output->off();
            $this->log("Turned %s OFF", [$id]);
        }

        $this->printFooter();
    }

    /**
     * Instantiates a class using the DI container.
     *
     * @param string $id the id to assign (if it's a Configurable)
     * @return mixed the instance
     * @throws Exception if a Configuration is missing
     * @throws \DI\NotFoundException if the class is not found
     */
    protected function instantiateClass($id)
    {
        if (!isset($this->instances[$id])) {
            if (!$this->getConfig($id . '.' . self::KEY_CLASS)) {
                throw new Exception(sprintf("Configuration %s.%s is missing", $id, self::KEY_CLASS));
            }

            $name = $this->getConfig($id . '.' . self::KEY_CLASS);
            $this->instances[$id] = $this->container->make($name, ['id' => $id]);

            //$this->log("Created %s of type %s", [$id, $name]);
        }

        return $this->instances[$id];
    }

    /**
     * Gets the status of an input
     * @param string $id the input id
     * @return Status
     * @throws Exception if the input returns an invalid Status
     */
    protected function determineStatus($id)
    {
        if (!isset($this->status[$id])) {
            $status = $this->instantiateClass($id)->getCurrentStatus();

            if (!$status instanceof Status) {
                throw new Exception("Returned status of $id has the wrong type! Use Status::GOOD(), Status::OKAY() and Status::BAD().");
            }

            $this->status[$id] = $status;

            //$this->log("Determined status of %s: %s", [$id, $status->getName()]);
        }

        return $this->status[$id];
    }

} 