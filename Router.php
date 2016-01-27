<?php

namespace Hermes\Router;


class Router {
    public static $halts = false;

    public static $routes = [];
    public static $methods = [];
    public static $callbacks = [];

    public static $pattern = array(
        ':any' => '[^/]+',
        ':num' => '[0-9]+',
        ':all' => '.*'
    );

	public function info()
	{
		echo "This is a function in Hermes, Router...";
	}

}
