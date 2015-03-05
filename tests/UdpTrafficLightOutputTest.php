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


class UdpTrafficLightOutputTest extends PHPUnit_Framework_TestCase
{

    public function testGood()
    {
        $this->createOutput(function ($mock) {
            $mock->expects($this->exactly(3))->method('send')->withConsecutive(
                $this->equalTo('off_o'),
                $this->equalTo('off_b'),
                $this->equalTo('on_g')
            );
        })->updateOutput(Status::GOOD());
    }

    public function testOkay()
    {
        $this->createOutput(function ($mock) {
            $mock->expects($this->exactly(3))->method('send')->withConsecutive(
                $this->equalTo('off_g'),
                $this->equalTo('off_b'),
                $this->equalTo('on_o')
            );
        })->updateOutput(Status::OKAY());
    }

    public function testBad()
    {
        $this->createOutput(function ($mock) {
            $mock->expects($this->exactly(3))->method('send')->withConsecutive(
                $this->equalTo('off_g'),
                $this->equalTo('off_o'),
                $this->equalTo('on_b')
            );
        })->updateOutput(Status::BAD());
    }

    public function testOff() {
        $this->createOutput(function ($mock) {
            $mock->expects($this->exactly(3))->method('send')->withConsecutive(
                $this->equalTo('off_g'),
                $this->equalTo('off_o'),
                $this->equalTo('off_b')
            );
        })->off();
    }

    protected function createOutput($clientFunc) {
        $config = new \ITstrategen\ArrayConfig([
            'test' => [
                'host' => '',
                'port' => '',

                'messages' => [
                    'on' => 'on_{status}',
                    'off' => 'off_{status}'
                ],

                'status' => [
                    'good' => 'g',
                    'okay' => 'o',
                    'bad' => 'b'
                ]
            ]
        ]);

        $client = $this->getMock('\ITstrategen\utilities\UdpClient', ['send'], ['id' => 'test', 'config' => $config]);
        $clientFunc($client);

        $container = $this->getMockBuilder('\DI\Container')->disableOriginalConstructor()->getMock();
        $container->method('make')
            ->with('\ITstrategen\utilities\UdpClient')
            ->willReturn($client);

        $this->assertEquals($client, $container->make('\ITstrategen\utilities\UdpClient'));

        $output = new \ITstrategen\outputs\UdpTrafficLightOutput('test', $config, $container);
        return $output;
    }

} 