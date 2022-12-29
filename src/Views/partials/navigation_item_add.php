<?php
/** @var array $content */
/** @var Content $contentItem */
/** @var Navigation $navigation */

use Webigniter\Libraries\Content;
use Webigniter\Libraries\Navigation;

?>

<div class="modal fade" id="addNavItem" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mt-6" role="document">
        <div class="modal-content border-0">
            <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                    <h4 class="mb-1" id="staticBackdropLabel"><?=ucfirst(lang('general.navigation_item_add'));?></h4>
                </div>
                <div class="p-4">
                    <form method="post" id="addNavItemForm">
                        <input type="hidden" name="navigation_id" value="<?=$navigation->getId();?>">
                        <div class="mb-3">
                            <label class="form-label" for="name"><?=ucfirst(lang('general.name'));?></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder="<?=ucfirst(lang('general.name'));?>" value="<?=set_value('name');?>" required/>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="content_id"><?=ucfirst(lang('general.content'));?></label>
                            <select class="form-select js-choice" id="content_id" size="1" name="content_id" data-options='{"removeItemButton":true,"placeholder":true}'>
                                <option value=""><?=ucfirst(lang('general.content_select'));?></option>
                                <?php foreach($content as $contentItem):?>
                                    <option value="<?=$contentItem->getId();?>"><?=$contentItem->getName();?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="link">
                                <?=ucfirst(lang('general.or_external'));?>
                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('tooltips.external_link'));?>"><i class="far fa-question-circle"></i></i>
                            </label>
                            <input class="form-control" id="basic-form-name" type="text" name="link" placeholder="<?=ucfirst(lang('general.external_link'));?>" value="<?=set_value('name');?>" />
                        </div>

                        <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.save'));?></button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>