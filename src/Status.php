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


use MabeEnum\Enum;

/**
 * The status of an input, ranging from "good" to "bad". Use Status::GOOD(), Status:OKAY() and Status::BAD()
 * for instantiation.
 *
 * @package ITstrategen
 * @method static Status GOOD()
 * @method static Status OKAY()
 * @method static Status BAD()
 * @author Max Vogler <max@itstrategen.de>
 * @copyright 2015, ITstrategen GmbH
 */
class Status extends Enum
{

    const GOOD = 0;
    const OKAY = 1;
    const BAD = 2;

    /**
     * Returns every Status.
     * @return Status[]
     */
    public static function getValues() {
        return [Status::GOOD(), Status::OKAY(), Status::BAD()];
    }

} 