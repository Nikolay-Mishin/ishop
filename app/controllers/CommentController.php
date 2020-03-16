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
			$w_Comment = $this->getComments($data['product_id']);
			// если данные пришли ajax, загружаем вид и передаем соответствующие данные
			if($this->isAjax()){
				exit(json_encode(['html' => "$w_Comment", 'count' => $w_Comment->getCount(), 'data' => $data, 'info' => $w_Comment->getInfo()]));
			}
			redirect(); // перезапрашиваем текущую страницу
		}
	}

	public function rateAction(){
		$data = $_GET;
		if(!empty($data)){
			$rate = Comment::getRate($id = $data['id']);
			if($rate === null) exit(json_encode(['error' => "Невозможно получить оценку для комментария с id = $id!"]));
			switch($data['action']){
				case 'plus': $rate++;
					break;
				case 'minus': $rate--;
					break;
			}
			$comment = $this->changeRate($rate, $id);
			// если данные пришли ajax, загружаем вид и передаем соответствующие данные
			if($this->isAjax()){
				exit(json_encode(['rate' => "$comment->rate"]));
			}
			redirect(); // перезапрашиваем текущую страницу
		}
	}

	public function replyAction(){
		$data = $_GET;
		if(!empty($data)){
			$w_Comment = new W_Comment([
				'editor_id' => 'reply_editor',
				'isAjax' => true,
				'meta' => [
					'form_id' => 'comment_reply',
					'id' => $data['product_id'],
					'parent_id' => $data['parent_id']
				]
			]);
			// если данные пришли ajax, загружаем вид и передаем соответствующие данные
			if($this->isAjax()){
				exit(json_encode(['editor' => "{$w_Comment->getEditor()}"]));
			}
			redirect(); // перезапрашиваем текущую страницу
		}
	}

	private function getComments($product_id, $data = []){
		$comments = Comment::getByProductId($product_id);
		$comments[] = $comments[3];
		return new W_Comment([
			'data' => $comments,
			'isAjax' => true,
			'editor' => false,
			'meta' => ['id' => $product_id]
		]);
	}

	private function changeRate($rate, $id){
		return new Comment(['rate' => $rate], $id, 'update');
	}

}
