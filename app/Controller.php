<?php
class Controller {
	
	protected $accessData = [];
	public $model;
	public $view;
	public $before = [];

	public function __construct()
	{
		$_SESSION['csrf'] = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : md5(uniqid(mt_rand().microtime())); // Уcтановливаем Token для Csrf
		
		// Проверяем доступ на вход
		if( !empty($this->before) ){
			foreach( $this->before as $access ){
				if(!$this->{'filter'.ucfirst($access)}())  Start::Error404();
				$this->accessData[$access] = $_SESSION[$access];
			}
		}
	
		// Определяем Вид
		$this->view = new View($this->accessData, strtolower(get_class($this)));
	}

	public function model($model_name) {
		$model_name = ucfirst(strtolower($model_name));
		$model_path = __DIR__ . '/models/' . $model_name . '.php';
		if( file_exists($model_path) ) {
			include_once $model_path;
		}

		$this->model = new $model_name;
	}

	// Очистка данных из запросов
	public function clear($data, $int = false){
		$data = trim($data);

		if($int) {
			return abs((int)$data);
		} else {
			return htmlspecialchars(trim(strip_tags($data)));
		}
	}
	
	public function hash($str){
		return md5(strrev(bin2hex($str)));
	}

	public function redirect($url = null, $flash = null){
		if( isset($flash) ) {
			$_SESSION['flash'] = $flash;
		}
		
		header("Location: http://{$_SERVER['HTTP_HOST']}/${url}");
		exit(0);
	}

	public function checkAJAX(){
		if( 
			!(
		    	isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
		    )
		  )
		{
			Start::Error404();
		}
	}

	public function isPost(){
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}

	public function filterCsrf(){
		return !($this->isPost() && $_POST['csrf'] !== $_SESSION['csrf']);
	}

}