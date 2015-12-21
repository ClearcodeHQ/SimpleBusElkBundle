[![Build Status](https://travis-ci.org/ClearcodeHQ/SimpleBusElkBundle.svg?branch=master)](https://travis-ci.org/ClearcodeHQ/SimpleBusElkBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ClearcodeHQ/SimpleBusElkBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ClearcodeHQ/SimpleBusElkBundle/?branch=master)

# ELK Bridge for Symfony2

## How to use:

### Requirements:

ELK installed, Logstash config:

```json
input {
  tcp {
    port => 5000
    codec => "json"
  }
}

output {
  elasticsearch { }
}
```

Tested on ELK docker: https://github.com/deviantony/docker-elk

### Installation & configuration:

##### I. Register a bundle

```php
<?php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Clearcode\ElkBridgeBundle\ElkBridgeBundle(),
            ...
        )
    }
    ...
}
```

##### II. Add this line to your config file:

```yaml
elk_bridge:
    enable_simple_bus_middleware: true
    logstash_namespace: your_app
    monolog_channel: event_bus_elk

monolog:
    channels: ["event_bus_elk"]
    handlers:
        event_bus_logstash:
            type: socket
            connection_string: localhost:5000 // <-- this part can be parametrized!
            level: debug
            channels: ["event_bus_elk"]
            formatter: elk_bridge.monolog.logstash_formatter // <-- you can use our default, beautiful formatter or write your own if you want to!
```

##### III. Enable monolog logger in service:

1. If you are using SimpleBus bundle just set ``elk_bridge.enable_simple_bus_middleware`` to ``true``. It will enable Event middleware which will log your events automatically on ELK.

2. Otherwise, just use our monolog channel as follow:

```yaml
services:
    service.do_amazing_thing:
        ...
        arguments:
            ...
            - @logger
        tags:
            ...
            - { name: monolog.logger, channel: '%elk_bridge.channel%' }
```

(see Clearcode\ElkBridgeBundle\CommandBus\LogEventMiddleware as example)

###### Note:

``elk_bridge.logstash_namespace`` will be logged in ``@type`` field in Kibana. We suggest to change it to name of project, from which you want to log events
(it is important especially if you want to log events from more than one project in one ELK instance).

``elk_bridge.channel`` will be logged in ``@fields.channel`` & ``@tags`` fields in Kibana. It is important when you log something else in ELK instance
at the same Kibana's index pattern.

# Enjoy!
