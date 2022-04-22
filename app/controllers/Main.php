<?php
class Main extends Controller {
	private $limit = 100;

	public $before = ['csrf'];

	public function index() {
		$this->model('news');

        if( $this->isPost() && isset($_POST['send']) ) {

            $_POST['fio'] = $this->clear($_POST['fio']);
            $_POST['email'] = $this->clear($_POST['email']);

			$this->model->insert([
				'news_id' => $_POST['news_id'],
				'cat_id' => $_POST['cat_id']
			]);

            $this->redirect('', 'Новость успешно добавлен к рубрике!');
        }

        $count_news = count( $this->model->findAll());

        // Исполнение Вида - Главной странице
        $this->view->render('index', compact('count_news'));
	}

	public function generate_news() {
		$this->model('news');
		$flash = "В базе уже {$this->limit} записей";
		$count = count($this->model->findAll());

		if( $count <= 100) {
			for( $i = 0; $i < 10; $i++ ) {
				$this->model->insert([
					'title' => 'Lorem Ipsum - ' . date('Y-m-d H:i:s'),
					'short' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using.',
					'full' => '<p>consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32</p>'
				]);
			}
			$flash = 'Новости успешно добавились!';
		}
		$this->redirect('', $flash);
	}
}