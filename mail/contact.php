
<h3><?=$form_name?></h3>
<?php
if($office < 100){ ?>
<div>Имя: <strong><?= $name ?></strong></div>
<?php
}
?>
<div>Телефон: <strong><?= $phone ?></strong></div>
<?php
if($office < 100){ ?>
<div>Офис: <strong><?= $cities[$office] ?></strong></div>
<?php
}
?>