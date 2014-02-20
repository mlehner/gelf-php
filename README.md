mlehner/gelf-php
===================

[![Build Status](https://travis-ci.org/mlehner/gelf-php.png?branch=master)](https://travis-ci.org/mlehner/gelf-php)

This library is abled to send [GELF v1.1](http://graylog2.org/gelf#specs) Messages to compatible servers like a [Graylog2 Server](http://graylog2.org/) in version v0.20.


## Status

This library is quite old and has just the single mentioned feature.

Further development and new features are _not desired_ in favor of this newer fork [bzikarsky/gelf-php](https://github.com/bzikarsky/gelf-php).


## Installation

Simply require the package by its name with composer:
```bash
$ php composer.phar require mlehner/gelf-php "~1.1.0"
```

## Usage

Example:

```php
// Create a Message and set your logging information.
$message = new Message();
$message->setFullMessage('Your full log mesage');
$message->setHost('example.com');
$message->setLevel(3); // List of supported levels: http://en.wikipedia.org/wiki/Syslog#Severity_levels
$message->setShortMessage('Short message');
$message->setTimestamp(time());
$message->setVersion('1.2.3');
// The following values are deprecated and will be transferred as additionals.
$message->setFile('foo.php');
$message->setLine(42);
$message->setFacility('server42');

// Create a Publisher and send the message to your GELF server.
// Beware: Hostnames need a DNS lookup, which might be slow!
$publisher = new MessagePublisher('192.168.99.99');
$publisher->publish($message);
```


## License

This bundle is licensed under MIT.

