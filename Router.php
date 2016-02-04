<?php

namespace LPFCRouter\Hermes;

class Route {
    public static $halts = false;

    public static $routes = [];
    public static $methods = [];
    public static $callbacks = [];

    public static $patterns = array(
        ':any' => '[^/]+',
        ':num' => '[0-9]+',
        ':all' => '.*'
    );

    public static $error_callback;

    public function info()
    {
        echo "This is a function in Hermes, Router...<br>";
    }

    public static function __callstatic($method, $params)
    {
        $uri = dirname($_SERVER['PHP_SELF']).$params[0];
        $callback = $params[1];

        array_push(self::$routes, $uri);
        array_push(self::$methods, strtoupper($method));
        array_push(self::$callbacks, $callback);
    }

    public static function getRoutes()
    {
        return self::$routes;
    }

    public static function getMethods()
    {
        return self::$methods;
    }

    public static function getCallbacks()
    {
        return self::$callbacks;
    }

    public static function error($callback)
    {
        self::$error_callback = $callback;
    }

    public static function haltInto($flag = true)
    {
        self::$halts = $flag;
    }

    // Give out the callback to request
    public static function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $searches = array_keys(static::$patterns);
        $replaces = array_values(static::$patterns);
        $found_route = false;

        self::$routes = str_replace('//', '/', self::$routes);

        if (in_array($uri, self::$routes)) {
            $route_pos = array_keys(self::$routes, $uri);
            foreach ($route_pos as $route) {
                if (self::$methods[$route] == $method || self::$methods[$route] == 'ANY') {
                    $found_route = true;

                    if (!is_object(self::$callbacks[$route])) {
                        $parts = explode('/', self::$callbacks[$route]);
                        $last = end($parts);
                        $segments = explode('@', $last);
                        // $segments[0]得到一个函数(控制器)
                        $controller = new $segments[0]();
                        $controller->$segments[1]();
                    } else {
                        call_user_func(self::$callbacks[$route]);
                    }

                    if (self::$halts) return;
                }
            }
        } else {
            $pos = 0;
            foreach (self::$routes as $route) {
                if (strpos($route, ':') !== false) {
                    $route = str_replace($searches, $replaces, $route);
                }

                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    if (self::$methods[$pos] == $method) {
                        $found_route = true;
                        array_shift($matched);

                        if (!is_object(self::$callbacks[$pos])) {
                            $parts = explode('/', self::$callbacks[$pos]);
                            $last = end($parts);
                            $segments = explode('@', $last);
                            $controller = new $segments[0]();

                            if (!method_exists($controller, $segments[1])) {
                                echo "controller or action not found...";
                            } else {
                                call_user_func_array(array($controller, $segments[1]), $matched);
                            }
                            if (self::$halts) return;
                        } else {
                            call_user_func_array(self::$callbacks[$pos], $matched);
                            if (self::$halts) return;
                        }
                    }
                }
                $pos++;
            }
        }

        if ($found_route == false) {
            if (!self::$error_callback) {
                self::$error_callback = function() {
                    header($_SERVER['SERVER_PROTOCOL'] . '404 Not Found');
                    echo '404 Not Found';
                };
            }
            call_user_func(self::$error_callback);
        }
    }
}
