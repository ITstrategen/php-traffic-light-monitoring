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

namespace ITstrategen\outputs;

use DI\Container;
use ITstrategen\Status;
use ITstrategen\utilities\UdpClient;
use Noodlehaus\Config;

/**
 * A traffic light output, using UDP. For example, this type of output may communicate with a network-controllable
 * outlet board to turn lamps on and off.
 *
 * @author Max Vogler <max@itstrategen.com>
 * @copyright 2015, ITstrategen GmbH
 */
class UdpTrafficLightOutput extends AbstractTrafficLightOutput
{

    /**
     * @var UdpClient
     */
    private $client;

    /**
     * @param string $id the id, used as namespace for getting configuration values
     * @param Config $config the configuration
     * @param Container $container the DI container, used for instantiating an UdpClient
     */
    public function __construct($id, Config $config, Container $container)
    {
        parent::__construct($id, $config);
        $this->client = $container->make('\ITstrategen\utilities\UdpClient', ['id' => $id]);
    }

    protected function getRequiredConfiguration()
    {
        return ['messages.on', 'messages.off', 'status.good', 'status.okay', 'status.bad'];
    }

    /**
     * Converts a Status to a string representation, using the given Config
     * @param Status $status
     * @return string
     */
    protected function getStatusString(Status $status)
    {
        $id = strtolower($status->getName());
        return $this->getConfig("status.{$id}");
    }

    protected function turnLightOff(Status $light)
    {
        $socket = $this->getStatusString($light);
        $message = str_replace('{status}', $socket, $this->getConfig('messages.off'));
        $this->client->send($message);
    }

    protected function turnLightOn(Status $light)
    {
        $socket = $this->getStatusString($light);
        $message = str_replace('{status}', $socket, $this->getConfig('messages.on'));
        $this->client->send($message);
    }
}
