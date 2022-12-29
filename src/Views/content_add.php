<?php
/** @var Category $category */
/** @var array $views */
/** @var View $view */

use Webigniter\Libraries\Category;
use Webigniter\Libraries\View;

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

                <div class="mb-3">
                    <label class="form-label" for="view_file">
                        <?=ucfirst(lang('general.view_file'));?>
                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('tooltips.view_file'));?>"><i class="far fa-question-circle"></i></i>
                    </label>
                    <select class="form-select js-choice" id="view_file" size="1" name="view_file" data-options='{"removeItemButton":true,"placeholder":true}'>
                        <option value=""><?=ucfirst(lang('general.view_file_select'));?></option>
                        <?php foreach($views as $view):
                            if(str_ends_with($view->getFilename(), '.php')):?>
                                <option <?=set_value('view_file', $category->getDefaultView()) == $view->getFilename() ? 'selected' : ''?>><?=$view->getFilename();?></option>
                            <?php endif;
                        endforeach; ?>
                    </select>
                </div>

                <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.create'));?></button>
                <a href="/cms/category/<?=$category->getId();?>" class="btn btn-secondary"><?=ucfirst(lang('general.discard'));?></a>
            </form>
        </div>
    </div>

<?= $this->endSection() ?>