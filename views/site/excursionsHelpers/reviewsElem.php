<?php
//vd($review);
if ($review->type == 0){
    ?>
    <div class="reviews-page-item" id="reviews-item-<?=$id?>">
        <div class="reviews-page-item__title">Экскурсия <?=$excName?></div>
        <div class="reviews-page-item__main">
            <div class="reviews-page-item__row">
                <div class="reviews-page-item__col">
                    <div class="reviews-page-item__name"><?=$review->name?></div>
                    <time class="reviews-page-item__time" datetime="2019-09-13T08:23:11+03:00"><?=$review->date?></time>
                </div>
                <div class="rating-of_comment-block">
                    <img src="/content/icons/5_hole_of_stars.png" class="rating-of-comment"></img>
                    <div style="left: -<?=100-($review->rating/5*100)?>%" class="yellow-back-of-rating"></div>
                    <div class="gray-back-of-rating"></div>
                </div>
            </div>
            <div class="reviews-page-item__txt"><?=$review->content?></div>
        </div>
    </div>
<?}
else{?>
    <div class="reviews-page-item" id="reviews-item-<?=$id?>">
        <div class="reviews-page-item__title">Экскурсия <?=$excName?></div>
        <div class="reviews-page-item__video js-blind" data-href="https://www.youtube.com/embed/<?=$review->content?>" style="background-image: url(//img.youtube.com/vi/<?=$review->content?>/mqdefault.jpg);">
        </div>
    </div>
<?}
