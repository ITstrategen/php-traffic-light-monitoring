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

/**
 * An abstract output, which triggers traffic lights-like systems.
 *
 * @package ITstrategen\outputs
 * @author Max Vogler <max@itstrategen.de>
 * @copyright 2015, ITstrategen GmbH
 */
abstract class AbstractTrafficLightOutput extends Output
{

    public function updateOutput(Status $status)
    {
        // Turn off all inactive lights
        foreach (Status::getValues() as $s) {
            if ($s != $status) {
                $this->turnLightOff($s);
            }
        }

        // Turn on the active light afterwards
        // If the light is turned on in the foreach()-loop above,
        // some traffic light systems with slow relays may show multiple
        // lights active at the same time!
        $this->turnLightOn($status);
    }

    public function off() {
        foreach(Status::getValues() as $status) {
            $this->turnLightOff($status);
        }
    }

    /**
     * Turns a light off.
     * @param Status $light the light to be turned off
     * @return void
     */
    protected abstract function turnLightOff(Status $light);

    /**
     * Turns a light on.
     * @param Status $light the light to be turned on
     * @return void
     */
    protected abstract function turnLightOn(Status $light);
}