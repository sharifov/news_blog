<main role="main">
  <div class="container">
    <div class="row">
			<div class="col-md-4">
				<h3>Добавить рубрику синхронно</h3>
				<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
				<p><button class="btn btn-primary">Добавить</button></p>
			</div>
			<div class="col-md-4">
				<h3>Добавить рубрику асинхронно</h3>
				<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
				<p><button class="btn btn-primary">Добавить</button></p>
			</div>
			<div class="col-md-4">
				<form action="<?=$this->route('main/generate_news')?>" class="" method="post">
					<?=$this->csrf()?>
					<h3>Добавить еще 10 новостей в базу</h3>
					<p>В базей сейчас <span class="red"><?=$count_news?></span> запией!</p>
					<p><button class="btn btn-primary">Генерировать новость</button></p>
				</form>
			</div>
    </div>
    <hr>
  </div> <!-- /container -->
</main>