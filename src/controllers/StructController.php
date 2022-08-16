<?php

namespace roms\controllers;

use Yii;
use app\models\SearchPages;
use app\models\Content;

class StructController extends AdminController
{
    public $layout = 'admin';

    public function actionIndex()
    {
        return true;
    }

    public function actionShow($id = '', $offset = false) {
        if (!$id)
            $id = Yii::$app->params['catalog_root_id'];

        $model = new SearchPages();
        $data = $model->structMenu($id, 1, array('offset' => $offset));

        $linkTemplate = '<span class="ctrl-arrow"></span><a href="{url}">{label}</a>';

        $params = [
            'items' => [$data],
            'linkTemplate' => $linkTemplate,
        ];

        if (\Yii::$app->request->isAjax){
            $params['items'] = $data['items'];
            $params['options'] = ['class' => 'sort'];
            $html = $this->renderPartial('structure', ['data' => $params]);
            if (!empty($data['controls'])) {
                $html = $data['controls'] . $html;
            }
            
            return $html;
        }

        $params['options'] = ['class' => 'struct'];

        return $this->render('structure', ['data' => $params]);
    }

    public function actionEdit($id) {
        if (empty($id)) {
            return false;
        }

        $model = new SearchPages();
        $page = $model->findOne($id);

        $model2 = new Content();
        $content = $model2->findOne($id);
        if (!$content) {
            $model2->id = $id;
            $model2->content = '';
            $model2->save();
            $content = $model2;
        }

        if ($page->load(Yii::$app->request->post()) && $page->validate()) {
            $page->save();

            if ($page->pagevalues->load(Yii::$app->request->post()) && $page->pagevalues->validate()) {
                $page->pagevalues->save();
            }

            if ($content->load(Yii::$app->request->post()) && $content->validate()) {
                $content->save();
            }

            return $this->redirect('/struct/show');
        }

        $url = "http://" . Yii::$app->params['main_domen'];
        $url .= $model->getUrlById($id);

        return $this->render('editpage',
            [
                'page' => $page,
                'content' => $content,
                'url' => $url,
            ]
        );
    }

    function actionReorderpages()
    {
        if (\Yii::$app->request->isAjax) {
            $res = array();
            $data = Yii::$app->request->post();
            $model = new SearchPages();
            $res = $model->reorder($data);
            return $res;
        }
    }
}
