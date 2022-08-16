<?php

namespace app\models;

use app\models\Content;
use Yii;

class SearchPages extends Pages
{
    public $tree = [];
    public $count = 1;

    public function createTree($id, $depth = false) {
        $depth--;
        $page = $this->findOne($id);
        $url = $this->getUrlById($id);
        $childs = $this->find()->where(['parent' => $id])->all();

        if (count($childs) > 1 && (!$depth || $depth > 0)) {
            foreach ($childs as $child) {
                $arr[] = self::createTree($child->id, $depth);
            }

            return array(
                'label' => $page->pagevalues->name,
                'url' => $url,
                'items' => $arr,
                'count' => $this->count++,
            );
        }

        return array(
            'label' => $page->pagevalues->name,
            'url' => $url,
            'count' => $this->count++,
        );
    }

    public function getUrlById($id) {
        $page = $this->findOne($id);
        $url = $page['alt_name'];
        $parent = $page->parent;

        if (empty($parent)) {
            return $url;
        }

        $url = $this->getUrlById($parent) . "/" . $url;
        return $url;
    }

//    public function getContent($id) {
//        $model = new Content();
//        $content = $model->findOne($id);
//
//        return $content;
//    }

    public function structMenu($id, $depth = false, $pager = false)
    {
        $depth--;
        $page = $this->findOne($id);
        $url = '/struct/edit/' . $id;

        if (!empty($pager)) {
            $offset = isset($pager['offset']) ? $pager['offset'] : 0;
            $limit = isset($pager['limit']) ? $pager['limit'] : Yii::$app->params['struct_perpage'];
            $count = $this->find()->where(['parent' => $id])->count();
            $childs = $this->find()->where(['parent' => $id])->offset($offset * $limit)->limit($limit)->orderBy('ord desc')->all();
            //$this->pr_log($childs);
        } else {
            $childs = $this->find()->where(['parent' => $id])->orderBy('ord')->all();
        }

        $childs_count = count($childs);

        if ($childs_count > 0 && (!$depth || $depth > 0)) {
            foreach ($childs as $child) {
                $arr[] = self::structMenu($child->id, $depth);
            }

            $data = array(
                'label' => $page->pagevalues->name,
                'url' => array($url),
                'items' => $arr,
            );

            //$data['options'] = ['data-id' => $id, 'class' => 'has-children'];

            if (!empty($pager)) {
                $controls = $this->getControls($offset, $limit, $count);
                $data['controls'] = $controls;
            }

            return $data;
        }

        $data = array(
            'label' => $page->pagevalues->name,
            'url' => $url,
            'options' => ['data-id' => $id],
        );

        if ($childs_count > 0) {
            $data['options'] = ['data-id' => $id, 'class' => 'has-children'];
        }

        return $data;
    }

    public function getControls($offset, $limit, $count)
    {
        $controls = '';
        $pages = ceil($count / $limit);
        if ($pages < 2)
            return $controls;

        for ($i = 0; $i < $pages; $i++) {
            $class = " pager-item";
            if ($i == $offset) {
                $class .= " active";
            }
            //$active = ($i == $offset) ? " class=\"active\" " : "";

            $controls .= "\n<span class=\"" . $class . "\"data-id=\"" . $i . "\">\n" . ($i + 1) . "\n</span>\n";
        }

        return '<div class="pager">' . $controls . '</div>';
    }

    public function reorder($data)
    {
        if (($data['id'] == "") || empty($data['parent'])) {
            return false;
        }

        $item = self::findOne($data['id']);
        $item->parent = $data['parent'];

        if (!empty($data['after'])) {
            $after = self::findOne($data['after']);
            $after_ord = $after->ord - 5;
        } else {
            $after_ord = 5;
        }
        $item->ord = $after_ord;
        $item->save();
        $childs = self::find()->where(['parent' => $data['parent']])->orderBy('ord desc')->all();

        foreach ($childs as $key => $child) {
            $child->{"ord"} = (count($childs) - $key) * 10;
            $child->save();
        }

        //$res = structMenu($id, 1, $pager = false);

        return true;
    }

    function pr_log($item)
    {
        error_log($item . "\n", 3, "/var/www/html/web/log/item.log");
    }
}

?>