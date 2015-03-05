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

class StatusTest extends PHPUnit_Framework_TestCase {

    public function testEquals() {
        $this->assertEquals(Status::GOOD(), Status::GOOD());
        $this->assertEquals(Status::OKAY(), Status::OKAY());
        $this->assertEquals(Status::BAD(), Status::BAD());
    }

    public function testNotEquals() {
        $this->assertNotEquals(Status::GOOD(), Status::OKAY());
        $this->assertNotEquals(Status::OKAY(), Status::BAD());
        $this->assertNotEquals(Status::BAD(), Status::GOOD());
    }

    public function testNames() {
        $this->assertEquals(Status::GOOD()->getName(), "GOOD");
        $this->assertEquals(Status::OKAY()->getName(), "OKAY");
        $this->assertEquals(Status::BAD()->getName(), "BAD");
    }

    public function testGetByName() {
        $this->assertEquals(Status::getByName("GOOD"), Status::GOOD());
        $this->assertEquals(Status::getByName("OKAY"), Status::OKAY());
        $this->assertEquals(Status::getByName("BAD"), Status::BAD());
    }

    public function testGetValues() {
        $this->assertCount(3, Status::getValues());
        $this->assertContains(Status::GOOD(), Status::getValues());
        $this->assertContains(Status::OKAY(), Status::getValues());
        $this->assertContains(Status::BAD(), Status::getValues());
    }

}