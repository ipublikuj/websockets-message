/**
 * ipub.websockets.message.js
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        iPublikuj:WebSocketMessage!
 * @subpackage     java-script
 * @since          1.0.0
 *
 * @date           26.02.17
 */

/**
 * Client-side script for iPublikuj:WebSocketMessage!
 *
 * @author        Adam Kadlec <adam.kadlec@fastybird.com>
 * @package       iPublikuj:WebSocketMessage!
 * @version       1.0.0
 *
 * @param {jQuery} $ (version > 1.7)
 * @param {Window} window
 */
;(function ($, window) {
    /* jshint laxbreak: true, expr: true */
    "use strict";

    var IPub = window.IPub || {};

    IPub.WebSockets = {}

    /**
     * WebSockets message extension definition
     *
     * @param {String} uri
     * @param {Object} options
     */
    IPub.WebSockets.Message = function (uri, options) {

        // WS server uri
        this.uri = uri;

        this.options = $.extend($.fn.ipubWebSocketsMessage.defaults, options, {});

        this.events = new IPub.WebSockets.Events();
    };


    IPub.WebSockets.Message.prototype =
    {
        /**
         * Connect to WS server
         */
        connect: function () {

            var that = this;

            this.connection = new WebSocket(this.uri);

            this.connection.onopen = function () {
                that.events.fire({type: 'socket/open'});
            };

            this.connection.onclose = function (code, reason) {
                that.events.fire({type: 'socket/close', data: {code: code, reason: reason}});
            };

            this.connection.onerror = function (error) {
                that.events.fire({type: 'socket/error', data: error});
            };

            this.connection.onmessage = function (event) {
                var data = event.data;

                try {
                    var parsed = JSON.parse(data);

                    // Call type
                    if (parsed.type !== undefined && parsed.type == 'call') {
                        if (that.handler[parsed.name]) {
                            that.handler[parsed.name](parsed.data);

                        } else {
                            console.log('Missing handler named: ' + parsed.name);
                        }
                    }

                } catch (ex) {
                }

                that.events.fire({type: 'socket/message', data: {data: data, event: event}});
            };

            window.addEventListener('unload', function () {
                that.connection.close();
                that.connection = null;
            });
        },

        /**
         * Send message via web sockets
         *
         * @param {String} path
         * @param {String} data
         */
        send: function (path, data) {
            if (!data) {
                send = path;

            } else {
                var send = {
                    route: path,
                    data: data
                };
            }

            return this.connection.send(JSON.stringify(send));
        },

        /**
         * Adds a listener for an event type
         *
         * @param {String} type
         * @param {function} listener
         */
        on: function (type, listener) {
            this.events.on(type, listener);
        },

        /**
         * Removes a listener from an event
         *
         * @param {String} type
         * @param {function} listener
         */
        off: function (type, listener) {
            this.events.off(type, listener);
        }
    };

    IPub.WebSockets.Events = function () {

        this.listeners = {};
    };

    IPub.WebSockets.Events.prototype =
    {
        /**
         * Fires an event for all listeners
         *
         * @param {String} event
         */
        fire: function (event) {
            if (typeof event == "string") {
                event = {
                    type: event
                };
            }

            if (!event.target) {
                event.target = this;
            }

            if (!event.type) {  // Falsy
                throw new Error("Event object missing 'type' property.");
            }

            if (this.listeners[event.type] instanceof Array) {
                var listeners = this.listeners[event.type];

                for (var i = 0, len = listeners.length; i < len; i++) {
                    listeners[i].call(this, event.data);
                }
            }
        },

        /**
         * Adds a listener for an event type
         *
         * @param {String} type
         * @param {function} listener
         */
        on: function (type, listener) {
            if (typeof this.listeners[type] == "undefined") {
                this.listeners[type] = [];
            }

            this.listeners[type].push(listener);
        },

        /**
         * Removes a listener from an event
         *
         * @param {String} type
         * @param {function} listener
         */
        off: function (type, listener) {
            if (this.listeners[type] instanceof Array) {
                var listeners = this.listeners[type];

                for (var i = 0, len = listeners.length; i < len; i++) {
                    if (listeners[i] === listener) {
                        listeners.splice(i, 1);

                        break;
                    }
                }
            }
        }
    };

    /**
     * Web socket client initialization
     *
     * @param {String} uri
     * @param {Object} options
     *
     * @returns {Object}
     */
    IPub.WebSockets.Message.initialize = function (uri, options) {
        var connection = new IPub.WebSockets.Message(uri, options);

        connection.connect();

        return connection;
    }

    /**
     * IPub WebSockets plugin definition
     */

    var old = $.fn.ipubWebSocketsMessage;

    $.fn.ipubWebSocketsMessage = function (uri, options) {
        new IPub.WebSockets.Message(uri, options).connect();
    };

    /**
     * IPub WebSockets plugin no conflict
     */

    $.fn.ipubWebSocketsMessage.noConflict = function () {
        $.fn.ipubWebSocketsMessage = old;

        return this;
    };

    /**
     * Complete plugin
     */

    // Assign plugin data to DOM
    window.IPub = IPub;

    return IPub;

})(jQuery, window);
