<?php
/** @var array $views */
/** @var View $view */

use Webigniter\Libraries\View;

?>

<?= $this->extend('\Webigniter\Views\layout') ?>

<?= $this->section('content') ?>
    <div class="w-auto pb-4 pt-2 d-flex flex-row-reverse">
        <a class="btn btn-primary me-1 mb-1" href="/cms/views/add"><?=ucfirst(lang('general.view_add'));?></a>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h3 class="mb-0" data-anchor="data-anchor"><?=ucfirst(lang('general.views'));?></h3>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="tab-content">
                <div class="tab-pane preview-tab-pane active" role="tabpanel" aria-labelledby="tab-dom-cfcec397-a35c-4994-a54a-50bf30775d88" id="dom-cfcec397-a35c-4994-a54a-50bf30775d88">
                    <div id="tableExample2" data-list='{"valueNames":["name","used", "actions"],"page":20,"pagination":true}'>
                        <div class="table-responsive scrollbar">
                            <table class="table table-bordered table-striped fs--1 mb-0">
                                <thead class="bg-200 text-900">
                                <tr>
                                    <th class="sort fs-1" data-sort="name"><?=ucfirst(lang('general.name'));?></th>
                                    <th class="sort fs-1" data-sort="used"><?=ucfirst(lang('general.used'));?></th>
                                    <th class="text-end fs-1"><?=ucfirst(lang('general.actions'));?></th>
                                </tr>
                                </thead>
                                <tbody class="list">
                                <?php foreach($views as $view):?>
                                    <tr>
                                        <td class="name fs-0"><a href="/cms/views/<?=$view->getName();?>"><?=$view->getName();?></a></td>
                                        <td class="used fs-0"><?=$view->getNumUsages();?></td>
                                        <td class="text-end">
                                            <?php if($view->getNumUsages() === 0):?>
                                                <div>
                                                    <?php
                                                    $dataArray['question'] = ucfirst(lang('general.delete_question', [lang('general.view')]));
                                                    $dataArray['link'] = '/cms/views/'.$view->getName().'/delete';
                                                    $dataArray['data'][ucfirst(lang('general.view'))] = $view->getName();

                                                    $jsonData = json_encode($dataArray);
                                                    ?>
                                                    <a class="btn btn-link p-0 ms-2 delete_button" href='#' datasrc='<?=$jsonData;?>' data-bs-toggle="modal" data-bs-target="#DeletionModal"><i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('general.delete'));?>"><i class="text-500 fas fa-trash-alt"></i></i></a>
                                                </div>
                                            <?php endif;?>
                                        </td>
                                    </tr>
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

<?= $this->include('\Webigniter\Views\partials\deletion_modal') ?>

<?= $this->endSection() ?>