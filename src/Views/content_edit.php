<?php
/** @var Content $content */
/** @var array $attachedPartials */
/** @var AttachedPartial $attachedPartial */

use Webigniter\Libraries\AttachedPartial;
use Webigniter\Libraries\Content;

?>

<?= $this->extend('\Webigniter\Views\layout') ?>

<?= $this->section('content') ?>

    <div class="w-auto pb-4 pt-2 d-flex flex-row-reverse">
        <button class="btn btn-primary me-4 mb-1" type="button" data-bs-toggle="modal" data-bs-target="#partialsList"><?=ucfirst(lang('general.partial_add'));?></button>
        <a class="btn btn-primary me-4 mb-1" href="<?=base_url().'/'.$content->getFullUrl();?>" target="_blank"><?=ucfirst(lang('general.preview_content'));?></a>
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
                <div class="form-check form-switch mb-3">
                    <label class="form-label" for="published">
                        <?=ucfirst(lang('general.published'));?>
                    </label>
                    <input class="form-check-input require_slug_toggle" id="published" type="checkbox" name="published" <?=$content->isPublished() ? 'checked' : '';?>/>
                </div>

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

                <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.save'));?></button>
                <a href="/cms/category/<?=$content->getCategoryId();?>" class="btn btn-secondary"><?=ucfirst(lang('general.discard'));?></a>
            </div>
        </div>


        <?php
        if(count($attachedPartials) > 0):
            foreach($attachedPartials as $attachedPartial):
                $partialData = json_decode($attachedPartial->getData(), true);
                ?>

                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row flex-between-end">
                            <div class="col-auto align-self-center">
                                <h5 class="mb-2" data-anchor="data-anchor"><?=$attachedPartial->getPartialName();?></h5>
                            </div>
                            <div class="col text-end">
                                <?php
                                $dataArray['question'] = ucfirst(lang('general.delete_question', [lang('general.element')]));
                                $dataArray['link'] = '/cms/content/'.$content->getId().'/delete-partial/'.$attachedPartial->getId();
                                $dataArray['data'][ucfirst(lang('general.element'))] = $attachedPartial->getPartialName();

                                $jsonData = json_encode($dataArray);
                                ?>
                                <a class="mx-3 delete_button" href='#' datasrc='<?=$jsonData;?>' data-bs-toggle="modal" data-bs-target="#DeletionModal"><i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('general.delete'));?>"><i class="text-500 fas fa-trash-alt"></i></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-5">
                            <div>
                                <?php foreach($attachedPartial->getPartialElements() as $element):?>
                                    <div class="mb-3">
                                        <?=$element->getSettingsArray()['name'];?><br>
                                            <?php
                                            $data['elementData'] = json_decode($element->getSettings());
                                            $data['value'] = $partialData[$element->getSettingsArray()['name']];
                                            $data['fieldName'] = $attachedPartial->getId().':'.$data['elementData']->name;

                                            echo view($element->getElementPartial(), $data);
                                            ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        endif; ?>
    </form>
<?= $this->include('\Webigniter\Views\partials\deletion_modal') ?>
<?= $this->include('\Webigniter\Views\partials\partials_list') ?>

<?= $this->endSection() ?>