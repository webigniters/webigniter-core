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
                    <h3 class="mb-4" data-anchor="data-anchor"><?=ucfirst(lang('general.edit')).' '.$category->getName();?></h3>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label" for="name"><?=ucfirst(lang('general.name'));?></label>
                    <input class="form-control" id="name" type="text" name="name" value="<?=set_value('name', $category->getName());?>" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="slug">
                        <?=ucfirst(lang('general.url'));?>
                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('tooltips.url'));?>"><i class="far fa-question-circle"></i></i></label>
                    <input class="form-control" id="slug" type="text" name="slug" value="<?=set_value('slug', $category->getSlug());?>" />
                </div>

                <div class="form-check form-switch mb-3">
                    <label class="form-label" for="require_slug">
                        <?=ucfirst(lang('general.url_required'));?>
                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('tooltips.url_required'));?>"><i class="far fa-question-circle"></i></i>
                    </label>
                    <input class="form-check-input" id="require_slug" type="checkbox" name="require_slug" <?= set_value('require_slug', $category->isRequireSlug()) ? 'checked' : '';?>/>
                </div>
                <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.save'));?></button>
                <a href="/cms/category/<?=$category->getParentId();?>" class="btn btn-secondary"><?=ucfirst(lang('general.discard'));?></a>
            </form>
        </div>
    </div>

<?= $this->endSection() ?>