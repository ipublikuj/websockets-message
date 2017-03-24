# WebSockets Message

[![Build Status](https://img.shields.io/travis/iPublikuj/websockets-message.svg?style=flat-square)](https://travis-ci.org/iPublikuj/websockets-message)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/iPublikuj/websockets-message.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/websockets-message/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/websockets-message.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/websockets-message/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/websockets-message.svg?style=flat-square)](https://packagist.org/packages/ipub/websockets-message)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/websockets-message.svg?style=flat-square)](https://packagist.org/packages/ipub/websockets-message)
[![License](https://img.shields.io/packagist/l/ipub/websockets-message.svg?style=flat-square)](https://packagist.org/packages/ipub/websockets-message)

Extension for implementing classic websockets messaging into [ipub/websockets](https://github.com/iPublikuj/websockets) 

## Installation

The best way to install ipub/websockets-message is using [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/websockets-message
```

After that you have to register extension in config.neon.

```neon
extensions:
	webSocketsMessage: IPub\WebSocketsMessage\DI\WebSocketsMessageExtension
```

## Documentation

Learn how to create WAMP application & integrate it into websockets in [documentation](https://github.com/iPublikuj/websockets-message/blob/master/docs/en/index.md).

***
Homepage [http://www.ipublikuj.eu](http://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/websockets-message](http://github.com/iPublikuj/websockets-message).
