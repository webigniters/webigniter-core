<?php
/** @var View $view */

use Webigniter\Libraries\View;

?>

<?= $this->extend('\Webigniter\Views\layout') ?>

<?= $this->section('content') ?>
    <form method="post">
        <div class="card mb-3">
            <div class="card-header">
                <div class="row flex-between-end">
                    <div class="col-auto align-self-center">
                        <h3 class="mb-2" data-anchor="data-anchor"><?=ucfirst(lang('general.edit')).' '.$view->getName();?></h3>
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="mb-3">
                    <label class="form-label" for="content"><?=ucfirst(lang('general.content'));?></label>
                    <textarea class="form-control" id="content"  name="content" rows="30"><?=set_value('content', $view->getContents());?></textarea>
                </div>

                <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.save'));?></button>
                <a href="/cms/views" class="btn btn-secondary"><?=ucfirst(lang('general.discard'));?></a>
            </div>
        </div>
    </form>

<?= $this->endSection() ?>