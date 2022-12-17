<?php
/** @var object $elementData */
?>

<textarea class="form-control" id="<?=url_title($elementData->name);?>"  name="<?=url_title($elementData->name);?>"><?=set_value(url_title($elementData->name), $elementData->value);?></textarea>