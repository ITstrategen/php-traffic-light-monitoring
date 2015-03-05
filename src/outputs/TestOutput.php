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


use ITstrategen\Status;

class TestOutput extends Output
{

    /**
     * The last assigned status.
     * @var mixed
     */
    private $lastStatus = null;

    public function updateOutput(Status $status)
    {
        $this->lastStatus = $status;
    }

    /**
     * Returns the last assigned Status (a Status instance,"off" when turned off,
     * or null if no status was assigned)
     * @return Status|string|null
     */
    public function getLastStatus()
    {
        return $this->lastStatus;
    }

    public function off()
    {
        $this->lastStatus = "off";
    }


}