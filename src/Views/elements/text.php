<?php
/** @var object $elementData */
?>

<input class="form-control" id="<?=url_title($elementData->name);?>" type="text" name="<?=url_title($elementData->name);?>" value="<?=set_value(url_title($elementData->name), $elementData->value);?>" />