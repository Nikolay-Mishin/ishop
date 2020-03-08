<?php
// контроллер валют - смена активной валюты и пересчет корзины в новую валюту

namespace app\controllers;

use app\models\Comment; // модель комментариев
use app\widgets\comment\Comment as w_Comment; // модель виджета комментариев

class CommentController extends AppController {

	// метод добавления комментария
	public function addAction(){
		$data = $_POST;
		if(!empty($data)){
			// если данные пришли ajax, загружаем вид и передаем соответствующие данные
			if($this->isAjax()){
				//$comment = new Comment($data);
				$content = !empty($data['content']) ? $data['content'] : null;
				$id = !empty($data['product_id']) ? (int)$data['product_id'] : null;
				$user_id = !empty($data['user_id']) ? (int)$data['user_id'] : null;
				//if($comment->errors) exit(json_encode(['errors' => $comment->errors]));
				if(!$content || !$id || !$user_id) exit(json_encode(['errors' => 'errors']));
				$comments = Comment::getByProductId($id);
				$w_Comment = [
					'parent_id' => 0,
					'product_id' => $id,
					'content' => $content,
					'date' => "2020-03-05 23:15:33",
					'update_at' => null,
					'status' => "1",
					'rate' => 0,
					'user_id' => $user_id,
					'name' => "Admin",
					'avatar' => "avatar1.jpg",
					'login' => "admin"
				];
				$comments[] = $w_Comment;
				//$w_Comment = new w_Comment([
				//    'data' => $comments,
				//    'id' => $id,
				//    'isAjax' => true
				//]);
				exit(json_encode(['data' => $data, 'comments' => $comments, 'w_Comment' => $w_Comment]));
			}
			new Comment($data);
			redirect(); // перезапрашиваем текущую страницу
		}
	}

	// метод добавления комментария
	public function rateAction(){
		$data = $_GET;
		if(!empty($data)){
			// если данные пришли ajax, загружаем вид и передаем соответствующие данные
			if($this->isAjax()){
				$rate = Comment::getRate($data['id']);
				$comment = \R::load('comment', $data['id']);
				switch($data['action']){
					case 'plus': $comment->rate++;
						break;
					case 'minus': $comment->rate--;
						break;
				}
				exit(json_encode(['data' => $rate, 'rate' => $comment->rate]));
			}
		}
	}

}
