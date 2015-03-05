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

use Exception;
use Noodlehaus\Config;

/**
 * Configurable objects.
 *
 * @package ITstrategen
 * @author Max Vogler <max@itstrategen.de>
 * @copyright 2015, ITstrategen GmbH
 */
class Configurable
{

    private $config;

    private $id;

    /**
     * Create a new instance.
     *
     * @param string $id the id, used as namespace for getting configuration values
     * @param Config $config the configuration
     * @throws Exception if not all required configuration keys are present
     */
    public function __construct($id, Config $config)
    {
        $this->config = $config;
        $this->id = $id;

        $this->requireConfig($this->getRequiredConfiguration());
    }

    /**
     * Returns the id
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id
     * @param string $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get a configuration value by its key. A dot syntax may be used to traverse through arrays,
     * e.g. getConfig('foo.bar.baz') returns $config['foo']['bar']['baz'].
     *
     * @param string $key
     * @param mixed $default
     * @return mixed|null the configuration value
     */
    public function getConfig($key, $default = null)
    {
        if($this->id != null) {
            $key = $this->id . '.' . $key;
        }

        $root = $this->config;
        $segs = explode('.', $key);

        foreach ($segs as $part) {
            if (isset($root[$part])) {
                $root = $root[$part];
                continue;
            } else {
                $root = $default;
                break;
            }
        }

        return $root;
    }

    /**
     * Require a list of configuration keys.
     * @param array $keys the required keys
     * @throws Exception if not all keys are present
     */
    public function requireConfig(array $keys)
    {
        foreach ($keys as $key) {
            if ($this->getConfig($key) === null) {
                throw new Exception("Configuration {$this->getId()}.{$key} is required!");
            }
        }
    }

    /**
     * Returns an array of all required configuration keys.
     * @return array all required configuration keys
     */
    protected function getRequiredConfiguration()
    {
        return [];
    }


}