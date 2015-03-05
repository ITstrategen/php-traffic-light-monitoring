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

namespace ITstrategen\utilities;
use Exception;
use ITstrategen\Configurable;
use Noodlehaus\Config;

/**
 * UDP_Client
 *
 * Wrapper for PHP's socket_* functions
 *
 * @author Stephan Bothur <stephan@itstrategen.com>
 * @copyright 2015, ITstrategen GmbH
 */
class UdpClient extends Configurable
{

    /**
     * @const time in seconds to wait between UDP requests
     * @access private
     */
    const SLEEP_BETWEEN_REQUESTS_IN_SECONDS = 0;

    /**
     * @var Resource socket resource
     * @access private
     */
    private $socketResource;

    /**
     * initialize connection to UDP server
     *
     * @param string $id the id, used as namespace for getting configuration values
     * @param Config $config the configuration
     * @throws Exception
     * @return \ITstrategen\utilities\UdpClient @access public
     */
    public function __construct($id, Config $config)
    {
        parent::__construct($id, $config);
        $this->connect();
    }

    /**
     * connect to UDP server
     *
     * @throws Exception
     * @return boolean true on success, Exception otherwise
     * @access public
     */
    public function connect()
    {

        if (!($this->socketResource = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP))) {

            $errorCode = socket_last_error();
            $errorMsg = socket_strerror($errorCode);

            throw new Exception("Couldn't create socket: [" . $errorCode . "] " . $errorMsg);
        }

        return true;
    }

    /**
     * disconnect from UDP server
     *
     * @return void
     * @access public
     */
    public function disconnect()
    {

        socket_close($this->socketResource);
    }

    /**
     * send UDP message to server
     *
     * @param string $message
     *
     * @throws Exception
     * @return boolean true on success, Exception otherwise
     * @access public
     */
    public function send($message)
    {
        /*
         * can't send a message if there's no connection to a server
         */
        if (!$this->isConnected()) {

            throw new Exception("Not connected to a server");
        }

        /*
         * send the message
         */
        if (!socket_sendto($this->socketResource, $message, strlen($message), 0, $this->getConfig('host'), $this->getConfig('port'))) {

            $errorCode = socket_last_error();
            $errorMsg = socket_strerror($errorCode);

            throw new Exception("Could not send data: [" . $errorCode . "] " . $errorMsg);
        }

        /*
         * wait until executing next request because UDP server only allows 1 request in 0.5 seconds
         */
        sleep($this->getConfig("delay", self::SLEEP_BETWEEN_REQUESTS_IN_SECONDS));

        return true;
    }

    /**
     * check if UDP connection is still alive
     *
     * @return true if UDP connection is still alive, false otherwise
     * @access public
     */
    public function isConnected()
    {

        return (is_null($this->socketResource) ? false : true);
    }

    protected function getRequiredConfiguration()
    {
        return ['host', 'port'];
    }
}

?>
