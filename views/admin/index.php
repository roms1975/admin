<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<video autoplay muted loop id="myVideo">
    <source src="/images/underwater.mp4" type="video/mp4">
</video>

<?php
$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>
<?= $form->field($model, 'login') ?>
<?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>