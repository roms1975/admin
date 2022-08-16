<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\User;
use app\models\LoginForm;
use app\models\SearchPages;

class AdminController extends Controller
{
    public $layout = 'admin';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        //'actions' => ['index', 'panel', 'logout'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['index', 'personal', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],                   [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    $this->redirect('/');
                    //throw new \Exception('У вас нет доступа к этой странице');
                }
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    //'logout' => ['post'],
//                ],
//            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'main';
        $model = new LoginForm();
        $user = new User();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $identity = $user::findOne(['name' => $model->login]);

                if ($identity && ($identity->password == md5($model->password))) {
                    Yii::$app->user->login($identity);

                    $auth = Yii::$app->authManager;
                    $res = $auth->getAssignments($identity->id);

                    if (empty($res)) {
                        return $this->redirect('/admin/personal');
                    } else {
                        return $this->redirect('/struct/show');
                    }
                } else {
                    return $this->refresh();
                }
            }
        }

        return $this->render('index', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        $this->redirect('/');
    }

    public function actionPersonal() {
        $this->layout = 'main';
        return $this->render('personal');
    }
}
