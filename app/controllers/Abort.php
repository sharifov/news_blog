<?php
class Abort extends Controller {
	
	public function index() {
        // Исполнение Вида - Ошибок
		$this->view->render('index', ['title' => 'My Site'] );
	}
}