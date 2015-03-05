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

use ITstrategen\Status;


class JenkinsInputTest extends PHPUnit_Framework_TestCase {

    public function testSuccess() {
        $this->check('SUCCESS', Status::GOOD());
    }

    public function testFailure() {
        $this->check('FAILURE', Status::BAD());
    }

    protected function check($result, Status $expected) {
        $config = new \ITstrategen\ArrayConfig([
            'test' => [
                'url' => '',
                'job' => ''
            ]
        ]);

        $client = $this->getMockBuilder('\Dinke\CurlHttpClient')->getMock();
        $client->method('fetchUrl')->willReturn(sprintf('{"result":"%1$s"}', $result));

        $input = new \ITstrategen\inputs\JenkinsInput('test', $config, $client);
        $this->assertEquals($expected, $input->getCurrentStatus());
    }

} 