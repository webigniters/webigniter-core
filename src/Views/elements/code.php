<?php
/** @var object $elementData */
/** @var array|string $value */
/** @var string $fieldName */
?>

<input class="form-control" id="<?=url_title($elementData->name);?>" type="text" name="<?=$fieldName;?>" value="<?=set_value($fieldName, $value);?>" />