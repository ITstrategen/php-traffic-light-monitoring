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


use ITstrategen\Configurable;
use ITstrategen\Status;

/**
 * An output for the current status. Outputs may display the current status (e.g. in the form of traffic lights,
 * try to restart systems on failure or do further actions.
 *
 * @package ITstrategen\outputs
 * @author Max Vogler <max@itstrategen.de>
 * @copyright 2015, ITstrategen GmbH
 */
abstract class Output extends Configurable
{

    /**
     * Update the output, e.g. by turning traffic lights on and off accordingly.
     * @param Status $status the current status
     * @return void
     */
    public abstract function updateOutput(Status $status);

    /**
     * Turn off the output. This method can be implemented for systems,
     * where the outputs shall be turned completely off,
     * e.g. after the end of working hours.
     *
     * @return void
     */
    public function off()
    {

    }

} 