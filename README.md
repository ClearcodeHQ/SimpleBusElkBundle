[![Build Status](https://travis-ci.org/ClearcodeHQ/SimpleBusElkBundle.svg?branch=master)](https://travis-ci.org/ClearcodeHQ/SimpleBusElkBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ClearcodeHQ/SimpleBusElkBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ClearcodeHQ/SimpleBusElkBundle/?branch=master)
[![MIT License](https://img.shields.io/packagist/l/clearcode/SimpleBusElkBundle.svg)](https://github.com/ClearcodeHQ/SimpleBusElkBundle/blob/master/LICENSE)

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
    enabled: ~
    logstash_namespace: your_app
    channel: event_bus_elk

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

Setting ``elk_bridge.enabled`` to ``false`` will disable feature.

``elk_bridge.logstash_namespace`` will be logged in ``@type`` field in Kibana. We suggest to change it to name of project, from which you want to log events
(it is important especially if you want to log events from more than one project in one ELK instance).

``elk_bridge.channel`` will be logged in ``@fields.channel`` & ``@tags`` fields in Kibana. It is important when you log something else in ELK instance
at the same Kibana's index pattern.

###### Note:

To convert Event (object of class) to json we use simple object to array converter, which converts object using some functions from that object class.
Functions must be not magic (name not prefixed by "__"), not static and not have any parameter,
so class of object that is used to store data to log on ELK must implements methods that fulfill above assumptions,
that will return data, which you want to log on ELK. If such function returns object, our converter is trying to convert it as well, with one exception:
if function returns instance of class of that object (for example ``return new self();``), it will skip such function to avoid infinity loop.

# Enjoy!
