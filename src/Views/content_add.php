<?php
/** @var Category $category */

use Webigniter\Libraries\Category;
?>

<?= $this->extend('\Webigniter\Views\layout') ?>

<?= $this->section('content') ?>
    <div class="card mb-3 mt-5">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h3 class="mb-4" data-anchor="data-anchor"><?=ucfirst(lang('general.content_add_to', [$category->getName()]));?></h3>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label" for="basic-form-name"><?=ucfirst(lang('general.name'));?></label>
                    <input class="form-control" id="basic-form-name" type="text" name="name" placeholder="<?=ucfirst(lang('general.name'));?>" value="<?=set_value('name');?>" />
                </div>

                <?php $checked = count($_POST) === 0 || (key_exists('require_slug', $_POST) && $_POST['require_slug']) == 'on'? 'checked' : '';?>

                <div class="form-check form-switch mb-3">
                    <label class="form-label" for="require_slug">
                        <?=ucfirst(lang('general.url_required'));?>
                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('tooltips.url_required'));?>"><i class="far fa-question-circle"></i></i>
                    </label>
                    <input class="form-check-input require_slug_toggle" id="require_slug" type="checkbox" name="require_slug" <?=$checked;?>/>
                </div>

                <div class="mb-3 my-box" <?=$checked === '' ? 'style="display:none"' : '';?>>
                    <label class="form-label" for="slug">
                        <?=ucfirst(lang('general.url'));?>
                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('tooltips.url'));?>"><i class="far fa-question-circle"></i></i></label>
                    <input class="form-control" id="slug" type="text" name="slug" value="<?=set_value('slug');?>" />
                </div>

                <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.create'));?></button>
                <a href="/cms/category/<?=$category->getId();?>" class="btn btn-secondary"><?=ucfirst(lang('general.discard'));?></a>
            </form>
        </div>
    </div>

<?= $this->endSection() ?>