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

use Noodlehaus\Config;

/**
 * A simple Config, based on an array.
 *
 * @package ITstrategen
 * @author Max Vogler <max@itstrategen.de>
 * @copyright 2015, ITstrategen GmbH
 */
class ArrayConfig extends Config
{

    /**
     * Creates a new Config, based on the given array.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->cache = $data;
    }

} 