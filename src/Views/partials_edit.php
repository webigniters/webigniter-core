<?php
/** @var Partial $partial */
/** @var array $attachedElements */
/** @var AttachedElement $attachedElement */
/** @var array $views */
/** @var View $view */

use Webigniter\Libraries\AttachedElement;
use Webigniter\Libraries\Partial;
use Webigniter\Libraries\View;

?>

<?= $this->extend('\Webigniter\Views\layout') ?>

<?= $this->section('content') ?>
    <div class="w-auto pb-4 pt-2 d-flex flex-row-reverse">
        <button class="btn btn-primary me-4 mb-1" type="button" data-bs-toggle="modal" data-bs-target="#elementsList"><?=ucfirst(lang('general.element_add'));?></button>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h3 class="mb-2" data-anchor="data-anchor"><?=ucfirst(lang('general.edit')).' '.$partial->getName();?></h3>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <form method="post" action="">
                <div class="mb-3">
                    <label class="form-label" for="name"><?=ucfirst(lang('general.name'));?></label>
                    <input class="form-control" id="name" type="text" name="name" value="<?=set_value('name', $partial->getName());?>" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="view_file"><?=ucfirst(lang('general.view_file'));?></label>
                    <select class="form-select js-choice" id="view_file" size="1" name="view_file" data-options='{"removeItemButton":true,"placeholder":true}'>
                        <option value=""><?=ucfirst(lang('general.view_file_select'));?></option>
                        <?php foreach($views as $view):
                            if(str_ends_with($view->getFilename(), '.php')):?>
                                <option <?=set_value('view_file', $partial->getViewFile()) == $view->getFilename() ? 'selected' : ''?>><?=$view->getFilename();?></option>
                            <?php endif;
                        endforeach; ?>
                    </select>
                </div>

                <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.save'));?></button>
                <a href="/cms/partials" class="btn btn-secondary"><?=ucfirst(lang('general.discard'));?></a>
            </form>
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
                <div class="tab-content">
                    <div class="tab-pane preview-tab-pane active" role="tabpanel" aria-labelledby="tab-dom-cfcec397-a35c-4994-a54a-50bf30775d88" id="dom-cfcec397-a35c-4994-a54a-50bf30775d88">
                        <div id="tableExample2" data-list='{"valueNames":["name","type","actions"],"page":20,"pagination":true}'>
                            <div class="table-responsive scrollbar">
                                <table class="table table-bordered table-striped fs--1 mb-0">
                                    <thead class="bg-200 text-900">
                                    <tr>
                                        <th class="sort fs-1" data-sort="name"><?=ucfirst(lang('general.name'));?></th>
                                        <th class="sort fs-1" data-sort="type"><?=ucfirst(lang('general.type'));?></th>
                                        <th class="text-end fs-1"><?=ucfirst(lang('general.actions'));?></th>
                                    </tr>
                                    </thead>
                                    <tbody class="list">
                                    <?php foreach($attachedElements as $attachedElement):
                                        $elementData = json_decode($attachedElement->getSettings());
                                        ?>
                                        <tr>
                                            <td class="name fs-0"><?=$elementData->name;?></a></td>
                                            <td class="type fs-0"><?=$attachedElement->getElementName();?></td>
                                              <td class="text-end">
                                                <div>
                                                    <a class="ms-3" data-bs-toggle="modal" data-bs-target="#config-<?=url_title($elementData->name);?>" style="cursor: pointer"><i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('general.edit'));?>"><i class="text-500 fas fa-cog"></i></i></a>
                                                    <?php
                                                    $dataArray['question'] = ucfirst(lang('general.delete_question', [lang('general.element')]));
                                                    $dataArray['link'] = '/cms/partial/'.$partial->getId().'/delete-element/'.$attachedElement->getId();
                                                    $dataArray['data'][ucfirst(lang('general.element'))] = $attachedElement->getSettingsArray()['name'];

                                                    $jsonData = json_encode($dataArray);
                                                    ?>
                                                    <a class="btn btn-link p-0 ms-2 delete_button" href='#' datasrc='<?=$jsonData;?>' data-bs-toggle="modal" data-bs-target="#DeletionModal"><i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('general.delete'));?>"><i class="text-500 fas fa-trash-alt"></i></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php include('partials\element_config.php');?>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                                <ul class="pagination mb-0"></ul>
                                <button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?= $this->include('\Webigniter\Views\partials\deletion_modal') ?>
<?= $this->include('\Webigniter\Views\partials\elements_list') ?>

<?= $this->endSection() ?>