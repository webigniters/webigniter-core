<?php
/** @var Content $content */
/** @var array $elements */
/** @var Element $element */

use Webigniter\Libraries\Content;
use Webigniter\Libraries\Element;

?>

<div class="modal fade" id="elementsList" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mt-6" role="document">
        <div class="modal-content border-0">
            <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                    <h4 class="mb-1" id="staticBackdropLabel"><?=ucfirst(lang('general.element_add_extended'));?></h4>
                </div>
                <div class="p-4">
                    <?php foreach($elements as $element):?>
                        <div class="flex-1">
                            <p>
                                <a class="btn btn-falcon-default mt-2" data-bs-toggle="collapse" href="#element-<?=$element->getId();?>" role="button" aria-expanded="false" aria-controls="collapseExample"><?=ucfirst(lang($element->getLanguage()));?></a>
                            </p>
                            <div class="collapse" id="element-<?=$element->getId();?>">
                                <div class="border p-x1 rounded">
                                    <form method="post" action="/cms/content/<?=$content->getId();?>/add-element/<?=$element->getId();?>">
                                        <?php foreach(json_decode($element->getSettings(), true) as $name => $type):
                                            if($type === 'text'):?>
                                                <label class="form-label" for="<?=$name;?>"><?=ucfirst(lang('elements.'.$name));?></label>
                                                <input class="form-control" id="<?=$name;?>" type="text" name="<?=$name;?>"/>
                                            <?php endif;
                                        endforeach; ?>

                                        <button class="btn btn-primary mt-3" type="submit"><?=ucfirst(lang('general.element_add'));?></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>