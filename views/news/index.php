<?php
use yii\helpers\Url;
use app\widgets\Pagination;
$actins = [1=>'new', 'blog', 'article'];
?>

<div class="container">
<div class="news-page">
    <div class="news">
        <?php
        $DIR = Yii::$app->params['path_to_news_images'];
        $resolt = '190x170/';
        foreach ($news AS $new){
            $url = Url::to(['news/'.$actins[$type], 'alias'=>$new['alias']]);
            vd($url);
            $img = $DIR.$resolt.$new['file_name'];
        ?>
            <div class="item">
                <?php
                if(!empty($new['file_name'])){ ?>
                    <div class="img-wrapper">
                        <a href="<?=$url;?>">
                            <img src="<?=$img;?>" />
                        </a>
                    </div>
                <?php
                }
                ?>
                <div class="title"><a href="<?=$url;?>"><?=$new['name']?></a></div>
                <div class="text"><?=$new['anons']?></div>
            </div>
        <?php
        }
        ?>
    </div>
    <?php
    $array_count = [1=>'count_news_items', 'count_blogs_items', 'count_art_items'];
    echo Pagination::widget(['count'=>$count, 'pageSize'=>Yii::$app->params[$array_count[$type]], 'page'=>$page]);?>
</div>
</div>
