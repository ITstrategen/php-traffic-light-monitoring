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

namespace ITstrategen\Inputs;


use ITstrategen\Configurable;

/**
 * An input source, returning the status of a monitored service, e.g. a build process.
 *
 * @package ITstrategen\Inputs
 * @author Max Vogler <max@itstrategen.de>
 * @copyright 2015, ITstrategen GmbH
 */
abstract class Input extends Configurable
{

    /**
     * Determine the current status. Return Status::GOOD(), Status::OKAY() or Status::BAD().
     * @return \ITstrategen\Status the current status, either GOOD, OKAY or BAD.
     */
    public abstract function getCurrentStatus();

} 