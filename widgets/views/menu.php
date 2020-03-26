<?php
use yii\helpers\Url;

?>

<ul class="header-nav__list">
    <?php
        array_unshift($pages, [
            'label' => 'Главная',
            'alias' => '',
            'page_link_title' => 'Главная страница',
            'id_page' => '0',
            'items' => []
        ]);//Добавление в меню ссылки на главную страницу.
        foreach ($pages AS $page){?>

            <li title="<?=$page['page_link_title']?>"><a href="/<?=$page['alias']?>"><?=$page['label']?></a></li>

        <?}
    ?>
</ul>

