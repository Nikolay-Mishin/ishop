<?php
// контроллер валют - смена активной валюты и пересчет корзины в новую валюту

namespace app\controllers;

use app\models\Comment; // модель комментариев
use app\widgets\comment\Comment as W_Comment; // модель виджета комментариев

class CommentController extends AppController {

	// метод добавления комментария
	public function addAction(){
		$data = $_POST;
		if(!empty($data)){
			$comment = new Comment($data);
			//$comment = new Comment(['rate' => 6], 3, 'update');
			debug($comment->tbl, 1);
			$content = !empty($data['content']) ? $data['content'] : null;
			$product_id = !empty($data['product_id']) ? (int)$data['product_id'] : null;
			$user_id = !empty($data['user_id']) ? (int)$data['user_id'] : null;
			$comments = Comment::getByProductId($product_id);
			$w_Comment = $this->getComments($comments, $product_id);

			// если данные пришли ajax, загружаем вид и передаем соответствующие данные
			if($this->isAjax()){
				exit(json_encode(['data' => $data, 'comments' => $comments, 'w_Comment' => "$w_Comment"]));
			}
			debug("$comments", 1);
			redirect(); // перезапрашиваем текущую страницу
		}
	}

	// метод добавления комментария
	public function rateAction(){
		$data = $_GET;
		if(!empty($data)){
			$rate = Comment::getRate($data['id']);
			switch($data['action']){
				case 'plus': $rate++;
					break;
				case 'minus': $rate--;
					break;
			}
			$comment = $this->changeRate($rate, $data['id']);

			// если данные пришли ajax, загружаем вид и передаем соответствующие данные
			if($this->isAjax()){
				exit(json_encode(['rate' => $comment->rate, 'comment' => $comment->bean]));
			}
			redirect(); // перезапрашиваем текущую страницу
		}
	}

	protected function getComments($comments, $product_id){
		return new W_Comment([
			'data' => $comments,
			'id' => $product_id,
			'isAjax' => true
		]);
	}

	protected function changeRate($rate, $id){
		return new Comment(['rate' => $rate], $id, 'update');
	}

}
