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

abstract class BaseTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        date_default_timezone_set("UTC");
        ini_set("date.timezone", "UTC");
    }

    protected function createConfigFile($content) {
        if(!file_exists('tests/config/')) {
            mkdir('tests/config');
        }

        $filename = 'tests/config/test-'.date('Y-m-d-H-i-s-').mt_rand(0, 10000).'.json';
        file_put_contents($filename, $content);
        return realpath($filename);
    }

    protected function getConstantInputConfig($value)
    {
        return json_decode($this->getConstantInputConfigJson($value), true);
    }

    protected function getConstantInputConfigJson($value) {
        return sprintf('
            {
                "inputs": {
                    "test-in" : {
                        "class": "ITstrategen\\\\inputs\\\\ConstantInput",
                        "value": "%1$s"
                    }
                },

                "outputs": {
                    "test-out": {
                        "class": "ITstrategen\\\\outputs\\\\TestOutput"
                    }
                },

                "routes": [
                    {"from": "test-in", "to": "test-out"}
                ]
            }
        ', $value);
    }

} 