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

namespace ITstrategen\inputs;


use Dinke\CurlHttpClient;
use ITstrategen\Status;
use Noodlehaus\Config;

/**
 * A Jenkins input source, returning the status of the last build of a job.
 *
 * @package ITstrategen\inputs
 * @author Max Vogler <max@itstrategen.de>
 * @copyright 2015, ITstrategen GmbH
 */
class JenkinsInput extends Input
{

    /**
     * @var CurlHttpClient
     */
    private $client;

    /**
     * Creates a new Jenkins input.
     *
     * @param string $id the id, used as namespace for getting configuration values
     * @param Config $config the configuration
     * @param CurlHttpClient $client
     */
    public function __construct($id, Config $config, CurlHttpClient $client)
    {
        parent::__construct($id, $config);
        $this->client = $client;
    }

    protected function getRequiredConfiguration()
    {
        return ['url', 'job'];
    }

    public function getCurrentStatus()
    {
        // reads the build result of the last build
        // TODO implement authentication via username and token
        $url = sprintf("%s/job/%s/lastBuild/api/json", $this->getConfig('url'), rawurlencode($this->getConfig('job')));
        $data = json_decode($this->client->fetchUrl($url));
        return $data->result == 'SUCCESS' ? Status::GOOD() : Status::BAD();
    }
}
