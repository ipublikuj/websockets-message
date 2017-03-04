# Javascript API Documentation

API for WebSockets is accessible in global object `window.IPub.WebSockets.Message`.

## Include JavaScript files

You have co copy one file to your document root:

```javascript
/vendor/IPub/websockets/public/js/ipub.websockets.message.js
```

or you could use **bower** components installer od other ways how to include static files into your webpage.

## Start using client WebSockets API

Once the javascript is included, you can start using IPub.WebSockets to interact with the web socket server.

A *IPub.WebSockets.Message* object is made available in the global scope of the page. This can be used to connect to the server as follows:

```javascript
var webSocket = IPub.WebSockets.Message('ws://127.0.0.1:8080').connect();
```

The following commands are available to a IPub.WebSockets object returned by IPub.WebSockets.initialize.

#### IPub.WebSockets.Message.on(event, callback)

This allows you to listen for events called by the server. The only events fired currently are **"socket/open"**, **"socket/close"**, **"socket/error"** and **"socket/message"**.

```javascript
var webSocket = IPub.WebSockets.Message('ws://127.0.0.1:8080').connect();

webSocket.on('socket/open', function(){
    // This event is fired when connection is established

    // Sending data with additional path (more in chapter about routing)
    webSocket.send('/custom/path', 'Message content');

    // or

    // Sending only data
    webSocket.send('Message content');
});

webSocket.on('socket/message', function(msg){
    // New message was recieved from server
    // This event is fired only for simple text responses
});

webSocket.on('socket/error', function(error){
    // Server send error message
});

webSocket.on('socket/close', function(closed){
    // Server ended session

    console.log('Disconnected for ' + closed.reason + ' with code ' + closed.code);
});
```
