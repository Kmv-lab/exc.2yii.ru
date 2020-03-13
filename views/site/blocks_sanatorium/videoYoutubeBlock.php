<?php

if (strrpos($video, "ch?v=")){
    $content = substr($video, strrpos($video, "ch?v=")+5, 11);
}
elseif (strrpos($video, "u.be/")){
    $content = substr($video, strrpos($video, "u.be/")+5, 11);
}
elseif (strrpos($video, "mbed/")){
    $content = substr($video, strrpos($video, "mbed/")+5, 11);
}
else{
    $content = '0r9iEuDCnrw';
}

?>
<div class="sanatorium-block">
    <div class="center-content">
        <a class="gallery" href="#testube"><button class="btn watch-video-btn btn-primary">Смотреть видео</button></a>
    </div>
</div>


<div style="display:none" id="testube">
    <iframe
            src="https://www.youtube.com/embed/<?=$content?>"
            frameborder="0"
            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>

    </iframe>
</div>
