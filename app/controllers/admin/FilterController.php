<?php

namespace app\controllers\admin;

use app\models\admin\FilterAttr;
use app\models\admin\FilterGroup;

class FilterController extends AppController{

    // экшен просмотра списка групп фильтров
    public function attributeGroupAction(){
        $attrs_group = \R::findAll('attribute_group'); // получаем список групп фильтров
        $this->setMeta('Группы фильтров'); // устанавливаем мета-данные
        $this->set(compact('attrs_group')); // передаем данные в вид
    }

    // экшен удаления групп фильтров
    public function groupDeleteAction(){
        $id = $this->getRequestID(); // получаем id группы фильтров
        $count = \R::count('attribute_value', 'attr_group_id = ?', [$id]); // считаем число аттрибутов в данной группе фильтров
        // если есть вложенные фильтры в данной группе, показываем ошибку
        if($count){
            $_SESSION['error'] = 'Удаление невозможно, в группе есть аттрибуты';
            redirect();
        }
        \R::exec('DELETE FROM attribute_group WHERE id = ?', [$id]); // удаляем группу фильтров
        $_SESSION['success'] = 'Удалено';
        redirect();
    }

    // экшен редактирования групп фильтров
    public function groupEditAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            $id = $this->getRequestID(false); // получаем id группы фильтров
            $group = new FilterGroup(); // объект модели группы фильтров
            $data = $_POST; // данные из формы
            $group->load($data); // загружаем данные в модель
            // валидируем данные
            if(!$group->validate($data)){
                $group->getErrors();
                redirect();
            }
            // сохраняем группу фильтров в БД
            if($group->update($id, 'attribute_group')){
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }
        $id = $this->getRequestID(); // получаем id группы фильтров
        $group = \R::load('attribute_group', $id); // получаем данные группы фильтров из БД
        $this->setMeta("Редактирование группы {$group->title}"); // устанавливаем мета-данные
        $this->set(compact('group')); // передаем данные в вид
    }

    // экшен добавления групп фильтров
    public function groupAddAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            $group = new FilterGroup(); // объект модели фильтров
            $data = $_POST; // данные из формы
            $group->load($data); // загружаем данные в модель
            // валидируем данные
            if(!$group->validate($data)){
                $group->getErrors();
                redirect();
            }
            // сохраняем группу фильтров в БД
            if($group->save('attribute_group', false)){
                $_SESSION['success'] = 'Группа добавлена';
                redirect();
            }
        }
        $this->setMeta('Новая группа фильтров'); // устанавливаем мета-данные
    }

    // экшен просмотра списка аттрибутов фильтров
    public function attributeAction(){
        // получаем список аттрибутов фильтров
        $attrs = \R::getAssoc("SELECT attribute_value.*, attribute_group.title FROM attribute_value JOIN attribute_group ON attribute_group.id = attribute_value.attr_group_id");
        $this->setMeta('Фильтры'); // устанавливаем мета-данные
        $this->set(compact('attrs')); // передаем данные в вид
    }

    // экшен удаления аттрибутов фильтров
    public function attributeDeleteAction(){
        $id = $this->getRequestID(); // получаем id фильтра
        \R::exec("DELETE FROM attribute_product WHERE attr_id = ?", [$id]); // удаляем фильтр из списка фильтров товаров
        \R::exec("DELETE FROM attribute_value WHERE id = ?", [$id]); // удаляем фильтр из БД
        $_SESSION['success'] = 'Удалено';
        redirect();
    }

    // экшен редактирования аттрибутов фильтров
    public function attributeEditAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            $id = $this->getRequestID(false); // получаем id фильтра
            $attr = new FilterAttr(); // объект модели фильтров
            $data = $_POST; // данные из формы
            $attr->load($data); // загружаем данные в модель
            // валидируем данные
            if(!$attr->validate($data)){
                $attr->getErrors();
                redirect();
            }
            // сохраняем группу фильтров в БД
            if($attr->update($id, 'attribute_value')){
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }
        $id = $this->getRequestID(); // получаем id фильтра
        $attr = \R::load('attribute_value', $id); // получаем данные фильтра из БД
        $groups = \R::findAll('attribute_group'); // получаем данные групп фильтров из БД
        $this->setMeta('Редактирование аттрибута'); // устанавливаем мета-данные
        $this->set(compact('attr', 'groups')); // передаем данные в вид
    }

    // экшен добавления аттрибутов фильтров
    public function attributeAddAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            $attr = new FilterAttr(); // объект модели фильтров
            $data = $_POST; // данные из формы
            $attr->load($data); // загружаем данные в модель
            // валидируем данные
            if(!$attr->validate($data)){
                $attr->getErrors();
                redirect();
            }
            // сохраняем фильтр в БД
            if($attr->save('attribute_value', false)){
                $_SESSION['success'] = 'Аттрибут добавлен';
                redirect();
            }
        }
        $groups = \R::findAll('attribute_group'); // получаем данные групп фильтров из БД
        $this->setMeta('Новый фильтр'); // устанавливаем мета-данные
        $this->set(compact('groups')); // передаем данные в вид
    }

}
