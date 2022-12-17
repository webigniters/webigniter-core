<?php
/** @var Content $content */
/** @var array $attachedElements */
/** @var array $viewsList */
/** @var AttachedElement $attachedElement */

use Webigniter\Libraries\AttachedElement;
use Webigniter\Libraries\Content;

?>

<?= $this->extend('\Webigniter\Views\layout') ?>

<?= $this->section('content') ?>
    <div class="w-auto pb-4 pt-2 d-flex flex-row-reverse">
        <button class="btn btn-primary me-4 mb-1" type="button" data-bs-toggle="modal" data-bs-target="#elementsList"><?=ucfirst(lang('general.element_add'));?></button>
    </div>
    <form method="post">
        <div class="card mb-3">
            <div class="card-header">
                <div class="row flex-between-end">
                    <div class="col-auto align-self-center">
                        <h3 class="mb-2" data-anchor="data-anchor"><?=ucfirst(lang('general.edit')).' '.$content->getName();?></h3>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="mb-3">
                    <label class="form-label" for="name"><?=ucfirst(lang('general.name'));?></label>
                    <input class="form-control" id="name" type="text" name="name" value="<?=set_value('name', $content->getName());?>" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="slug">
                        <?=ucfirst(lang('general.url'));?>
                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('tooltips.url'));?>"><i class="far fa-question-circle"></i></i></label>
                    <input class="form-control" id="slug" type="text" name="slug" value="<?=set_value('slug', $content->getSlug());?>" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="view_file"><?=ucfirst(lang('general.view_file'));?></label>
                    <select class="form-select js-choice" id="view_file" size="1" name="view_file" data-options='{"removeItemButton":true,"placeholder":true}'>
                        <option value=""><?=ucfirst(lang('general.view_file_select'));?></option>
                        <?php foreach($viewsList as $file):
                            if(str_ends_with($file, '.php')):?>
                                <option <?=set_value('view_file', $content->getViewFile()) == $file ? 'selected' : ''?>><?=$file;?></option>
                            <?php endif;
                        endforeach; ?>
                    </select>
                </div>

                <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.save'));?></button>
                <a href="/cms/category/<?=$content->getCategoryId();?>" class="btn btn-secondary"><?=ucfirst(lang('general.discard'));?></a>
            </div>
        </div>


        <?php if(count($attachedElements) > 0):?>

            <div class="card mb-3">
                <div class="card-header">
                    <div class="row flex-between-end">
                        <div class="col-auto align-self-center">
                            <h3 class="mb-2" data-anchor="data-anchor"><?=ucfirst(lang('general.elements'));?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <?php foreach($attachedElements as $attachedElement):
                        $elementData = json_decode($attachedElement->getSettings());
                        ?>
                        <div class="mb-3">
                            <label class="form-label" for="<?=url_title($elementData->name);?>"><?=$elementData->name;?></label>

                            <a data-bs-toggle="modal" data-bs-target="#config-<?=url_title($elementData->name);?>" style="cursor: pointer"><span class="ms-2 fas fa-cog"></span></a>

                            <?php include('elements\\'.$attachedElement->getElementName().'.php');?>
                            <?php include('partials\element_config.php');?>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        <?php endif; ?>
    </form>

<?= $this->include('\Webigniter\Views\partials\elements_list') ?>

<?= $this->endSection() ?>