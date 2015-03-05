![](http://i.imgur.com/37NH15B.jpg)
# PHP Traffic Light Monitoring
A simple monitoring solution, which can display server health using traffic lights (and much more!).

## Quick start
### Step 1: Installation
Installation requires PHP 5.5 and [Composer](https://getcomposer.org/).
```sh
$ git clone https://github.com/ITstrategen/php-traffic-light-monitoring.git .
$ composer update
```

### Step 2: Configuration
The configuration file consists of 3 sections: ``inputs`` specify status sources, e.g. by using the Jenkins API, measuring ping latency to a server or accessing Zabbix. ``outputs`` display the status to humans, e.g. by turning traffic lights on and off, sending emails or playing sounds. Finally, ``routes``connect ``inputs``to ``outputs``.

**Example ``/config/config.json``:**
```json
{
    "inputs": {
        "company-jenkins": {
            "class": "ITstrategen\\Inputs\\JenkinsInput",
            "url": "https://jenkins.example.com",
            "job": "Software Product 8000"
        }
    },

    "outputs": {
        "dev-room-traffic-light": {
            "class": "ITstrategen\\Outputs\\UdpTrafficLightOutput",
            "host": "192.168.123.123",
            "port": 123,
            "messages": {
                "on": "Sw_on{status}", "off": "Sw_off{status}"
            },
            "status": {
                "good": 1, "okay": 2, "bad": 3
            }
        },
    },

    "routes": [
        {"from": "company-jenkins", "to": "dev-room-traffic-light"}
    ]
}
```

### Step 3: Run
```sh
$ php monitor.php
``` 
This reads the status from the inputs and transmits it to the outputs. Use cronjobs for periodic execution.

# Extending
## Writing your own inputs
To parse your own input sources, extend the ``ITstrategen\inputs\Input`` class, e.g.
```php
namespace ACMECorp\inputs;
use ITstrategen\Status;
class TimeForBeerInput extends \ITstrategen\inputs\Input
{
    public function getCurrentStatus()
    {
        $time = (int) date('H');
        return $time > 16 ? Status::GOOD() : Status::BAD();
    }
}
```

## Writing your own outputs
Creating own outputs is analogeous:
```php
namespace ACMECorp\outputs;
use ITstrategen\Status;
class MessageOfTheDayOutput extends \ITstrategen\outputs\Output
{
    public function updateOutput(Status $status)
    {
        $message = $status == Status::GOOD() ?
            "Beer, it’s the best damn drink in the world. – J. Nicholson\n" :
            "The future depends on what you do today. – M. Gandhi\n";
        file_put_contents('/etc/motd', $message);
    }
}
```

## Connecting your own inputs and outputs
```json
{
    "inputs": {
        "my-input": {
            "class": "ACMECorp\\inputs\\TimeForBeerInput",
        },
    },

    "outputs": {
        "my-output": {
            "class": "ACMECorp\\outputs\\MessageOfTheDayOutput",
        }
    },

    "routes": [
        {"from": "my-input", "to": "my-output"}
    ]
}
```

# Example setup
At ITstrategen, we set up PHP Traffic Light Monitoring using:
 - [ANEL NET-PwrCtrl HOME](http://www.anel-elektronik.de/OnlineShop/main_bigware_34.php?bigPfad=22&items_id=33), which is a network controllable socket board
 - Three [12V Gummy Bear Halogen Lights](http://www.amazon.de/dp/B002AT7UZM) in the colors red, yellow and green

# License
```
PHP Traffic Light Monitoring
Copyright (C) 2015 ITstrategen GmbH
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
```
