<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php
    $form = ActiveForm::begin([
        'id' => 'edit-page',
        'class' => 'form-horizontal',
    ]);
?>

<?= $form->field($page, 'active')->checkbox() ?>
<?= $form->field($page, 'parent') ?>
<?= $form->field($page, 'alt_name') ?>
<?= $form->field($page, 'ord') ?>
<?= $form->field($page->pagevalues, 'name') ?>
<?= $form->field($page->pagevalues, 'description') ?>
<?= $form->field($content, 'content')->textarea(['rows' => '6', 'id' => 'ckeditor']) ?>

<div class="form-group">
    <div>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Отмена', ['struct/show'], ['class' => 'btn btn-info']) ?>
		<?= Html::a('Открыть в новой вкладке', $url, ['class' => 'btn btn-success pull-right']) ?>
    </div>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs(
    "CKEDITOR.replace('ckeditor');"
);
?>
