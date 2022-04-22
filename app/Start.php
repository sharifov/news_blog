<?php

class Start {
	public function __construct() {

		session_start();

		$params = [];				// Допольнительные параметри
		$controller_name = 'Main';	// Имя Контроллера по умолчанию
		$action_name = 'index';		// Имя Метода по умолчанию
		
		$routes = explode('/', str_replace(['?', $_SERVER['QUERY_STRING']], '', trim($_SERVER['REQUEST_URI'], '/')));

		if( !empty($routes[0]) ) {
			$controller_name = ucfirst(strtolower($routes[0]));	// берем имя Контроллера
		}
		
		if( !empty($routes[1]) ) {
			$action_name = $routes[1];	// берем имя Метода
		}

		if( count($routes) > 2 ) {
			$params = array_slice($routes, 2);	// берем Допольнительные параметри
		}
	
		// Сформируем Имя Контроллера
		$controller_file = $controller_name . '.php';
		$controller_path = __DIR__ . '/controllers/' . $controller_file;

		if( file_exists($controller_path) ) {
			require_once $controller_path;
		} else {
			$this->Error404();
		}
	
		//Подключаем Контроллер
		$controller = new $controller_name;

		if( method_exists($controller, $action_name) ) {
			call_user_func_array([$controller, $action_name], $params);
		} else {
			$this->Error404();
		}
	}

	public function Error404() {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'abort');
		exit;
    }
}