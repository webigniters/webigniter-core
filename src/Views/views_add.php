<?= $this->extend('\Webigniter\Views\layout') ?>

<?= $this->section('content') ?>
    <div class="card mb-3 mt-5">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h3 class="mb-4" data-anchor="data-anchor"><?=ucfirst(lang('general.view_add'));?></h3>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label" for="basic-form-name"><?=ucfirst(lang('general.name'));?></label>
                    <input class="form-control" id="basic-form-name" type="text" name="name" placeholder="<?=ucfirst(lang('general.name'));?>" value="<?=set_value('name');?>" />
                </div>

                <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.create'));?></button>
                <a href="/cms/views" class="btn btn-secondary"><?=ucfirst(lang('general.discard'));?></a>
            </form>
        </div>
    </div>

<?= $this->endSection() ?>