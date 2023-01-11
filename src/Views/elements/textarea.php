<?php
/** @var object $elementData */
/** @var string $value */
/** @var string $fieldName */
?>

<textarea class="form-control" id="<?=url_title($elementData->name);?>"  name="<?=$fieldName;?>"><?=set_value($fieldName, $value);?></textarea>