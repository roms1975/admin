<?php
use yii\helpers\Html;
use yii\widgets\Menu;

?>

<?php if (!empty($data)) : ?>
    <?=Menu::widget($data) ?>
<?php endif; ?>
