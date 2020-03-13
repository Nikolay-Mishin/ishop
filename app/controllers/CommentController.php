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
			$product_id = !empty($data['product_id']) ? (int)$data['product_id'] : null;
			$comments = $this->getComments($product_id);
			// если данные пришли ajax, загружаем вид и передаем соответствующие данные
			if($this->isAjax()){
				exit(json_encode(['html' => $comments->html, 'count' => $comments->count]));
			}
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
				exit("$comment->rate");
			}
			redirect(); // перезапрашиваем текущую страницу
		}
	}

	private function getComments($product_id, $data = []){
		$comments = Comment::getByProductId($product_id);
		$comments[] = $comments[3];
		return (object) [
			'html' => (string) new W_Comment([
				'data' => $comments,
				'id' => $product_id,
				'isAjax' => true
			]),
			'count' => count($comments)
		];
	}

	private function changeRate($rate, $id){
		return new Comment(['rate' => $rate], $id, 'update');
	}

}
