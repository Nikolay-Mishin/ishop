<?php

namespace app\controllers\admin;

use app\models\admin\FilterAttr;
use app\models\admin\FilterGroup;

class FilterController extends AppController{

    // экшен просмотра списка групп фильтров
    public function attributeGroupAction(){
        $attrs_group = FilterGroup::getAll(); // получаем список групп фильтров
        $this->setMeta('Группы фильтров'); // устанавливаем мета-данные
        $this->set(compact('attrs_group')); // передаем данные в вид
    }

    // экшен удаления групп фильтров
    public function groupDeleteAction(){
        FilterGroup::delete($this->getRequestID()); // удаляем группу фильтров
    }

    // экшен отображения группы фильтров
    public function groupViewAction(){
        $group = FilterGroup::getById($this->getRequestID()); // получаем данные группы фильтров из БД
        $this->setMeta("Редактирование группы {$group->title}"); // устанавливаем мета-данные
        $this->set(compact('group')); // передаем данные в вид
    }

    // экшен редактирования группы фильтров
    public function groupEditAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            new FilterGroup($_POST, [$this->getRequestID(false), 'attribute_group'], 'update'); // объект модели группы фильтров
        }
    }

    // экшен добавления групп фильтров
    public function groupAddAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            new FilterGroup($_POST, ['attribute_group', false]); // объект модели группы фильтров
        }
        $this->setMeta('Новая группа фильтров'); // устанавливаем мета-данные
    }

    // экшен просмотра списка аттрибутов фильтров
    public function attributeAction(){
        $attrs = FilterAttr::getAll(); // получаем список аттрибутов фильтров
        $this->setMeta('Фильтры'); // устанавливаем мета-данные
        $this->set(compact('attrs')); // передаем данные в вид
    }

    // экшен удаления аттрибутов фильтров
    public function attributeDeleteAction(){
        FilterAttr::delete($this->getRequestID()); // удаляем аттрибут фильтров
    }

    // экшен редактирования аттрибутов фильтров
    public function attributeViewAction(){
        list($attr, $groups) = [FilterAttr::getById($this->getRequestID()), FilterGroup::getAll()];
        $this->setMeta('Редактирование аттрибута'); // устанавливаем мета-данные
        $this->set(compact('attr', 'groups')); // передаем данные в вид
    }

    // экшен редактирования аттрибутов фильтров
    public function attributeEditAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            new FilterAttr($_POST, [$this->getRequestID(false), 'attribute_value'], 'update'); // объект модели фильтров
        }
    }

    // экшен добавления аттрибутов фильтров
    public function attributeAddAction(){
        // если получены данные из формы, обрабатываем их
        if(!empty($_POST)){
            new FilterAttr($_POST, ['attribute_value', false]); // объект модели фильтров
        }
        $groups = FilterGroup::getAll(); // получаем данные групп фильтров из БД
        $this->setMeta('Новый фильтр'); // устанавливаем мета-данные
        $this->set(compact('groups')); // передаем данные в вид
    }

}
