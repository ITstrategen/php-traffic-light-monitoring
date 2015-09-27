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

use ITstrategen\Status;
use Noodlehaus\Config;
use ZabbixApi\ZabbixApi;

/**
 * An input source for the Zabbix monitoring solution.
 *
 * @package ITstrategen\inputs
 * @author Max Vogler <max@itstrategen.de>
 * @author Sebastian Krein <sebastian@itstrategen.de>
 * @copyright 2015, ITstrategen GmbH
 */
class ZabbixInput extends Input
{

    /**
     * @Inject
     * @var ZabbixApi
     */
    protected $api;

    /**
     * Creates a new Zabbix input
     *
     * @param string $id the id, used as namespace for getting configuration values
     * @param Config $config the configuration
     * @param ZabbixApi $api
     */
    public function __construct($id, Config $config, ZabbixApi $api)
    {
        parent::__construct($id, $config);

        $this->api = $api;
        $this->api->setApiUrl($this->getConfig("url"));
        $this->api->userLogin(['user' => $this->getConfig("username"), 'password' => $this->getConfig("password")]);
    }

    protected function getRequiredConfiguration()
    {
        return ['url', 'username', 'password'];
    }

    public function getCurrentStatus()
    {
        // create filter for 'disaster', 'high', 'average', 'warning' and 'not classified'
        // further set flag to sort for 'a plain trigger' (not acknowledged)
        $filter = [
            'priority' => ['5', '4', '3', '2', '0'],
            'value' => '1'
        ];

        $triggers = $this->api->triggerGet([
            'monitored' => true,
            'active' => true,
            'only_true' => true,
            'filter' => $filter,
            'withLastEventUnacknowledged' => true,
            'output' => ['priority'],
            'sortfield' => 'priority',
            'sortorder' => 'DESC'
        ]);

        if (empty($triggers)) {
            return Status::GOOD();
        } else if ($triggers[0]->priority >= 4) {
            return Status::BAD();
        } else {
            return Status::OKAY();
        }
    }
}