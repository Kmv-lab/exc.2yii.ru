<?php
use yii\helpers\Url;


if(!$isFooter){


?>

<ul class="header-nav__list">
    <?php
        /*array_unshift($pages, [
            'label' => 'Главная',
            'alias' => '',
            'page_link_title' => 'Главная страница',
            'id_page' => '0',
            'items' => []
        ]);//Добавление в меню ссылки на главную страницу.*/
        foreach ($pages AS $page){
            $url = Yii::$app->request->pathInfo;
            $url = str_replace('/', '', $url);
            if (strcasecmp($url, $page['alias']) == 0){?>
                <li title="<?=$page['page_link_title']?>"><a href="/<?=$page['alias']?>" class="active"><?=$page['label']?></a></li>
            <?}
            else{?>
                <li title="<?=$page['page_link_title']?>"><a href="/<?=$page['alias']?>"><?=$page['label']?></a></li>
            <?}?>



        <?}
    ?>
</ul>

<?}
else{?>

    <ul>
        <?php
        /*array_unshift($pages, [
            'label' => 'Главная',
            'alias' => '',
            'page_link_title' => 'Главная страница',
            'id_page' => '0',
            'items' => []
        ]);//Добавление в меню ссылки на главную страницу.*/
        foreach ($pages AS $page){
            $url = Yii::$app->request->pathInfo;
            $url = str_replace('/', '', $url);
            ?>
                <li title="<?=$page['page_link_title']?>"><a href="/<?=$page['alias']?>"><?=$page['label']?></a></li>

        <?}
        ?>
    </ul>

<?}