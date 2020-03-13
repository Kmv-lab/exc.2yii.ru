<?php
use yii\helpers\Html;

$editLink       = Html::a($page['page_menu_name'],['pages/update','id'=>$page['id_page']], ['class'=>'btn btn-primary btn-sm '] );
$addChildLink   = Html::a('<span class="glyphicon glyphicon-plus"></span>', ['pages/create','id'=>$page['id_page']],
    ['title'=>'Создать подстраницу для «'.$page['page_name'].'»', 'class'=>'btn btn-success btn-sm '] );
$deleteLink = '';
if($page['no_del'] == 0)
    $deleteLink     = Html::a('<span class="glyphicon glyphicon-remove"></span>',['pages/delete','id'=>$page['id_page']],array('class'=>'deleteItem btn btn-danger btn-sm '));



echo '<div>'.$editLink.' — '.$addChildLink.' | '.$deleteLink.'</div>';
?>