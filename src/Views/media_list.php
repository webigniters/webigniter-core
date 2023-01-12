<?php
/** @var array $media */
/** @var string $currentFolder */
?>

<?= $this->extend('\Webigniter\Views\layout') ?>

<?= $this->section('content') ?>
    <div class="w-auto pb-4 pt-2 d-flex flex-row-reverse">
        <button class="btn btn-primary mb-1" type="button" data-bs-toggle="modal" data-bs-target="#mediaFileAdd"><?=ucfirst(lang('general.media_add'));?></button>
        <button class="btn btn-primary me-4 mb-1" type="button" data-bs-toggle="modal" data-bs-target="#mediaFolderAdd"><?=ucfirst(lang('general.folder_add'));?></button>
    </div>
    <div class="card mb-3">
        <div class="card-body pt-3">
            <div class="tab-content">
                <div class="tab-pane preview-tab-pane active" role="tabpanel" aria-labelledby="tab-dom-cfcec397-a35c-4994-a54a-50bf30775d88" id="dom-cfcec397-a35c-4994-a54a-50bf30775d88">
                    <div id="tableExample2" data-list='{"valueNames":["name","size", "actions"],"page":20,"pagination":true}'>
                        <div class="table-responsive scrollbar">
                            <table class="table table-bordered table-striped fs--1 mb-0">
                                <thead class="bg-200 text-900">
                                <tr>
                                    <th class="sort fs-1" data-sort="name"><?=ucfirst(lang('general.name'));?></th>
                                    <th class="sort fs-1" data-sort="size"><?=ucfirst(lang('general.filesize'));?></th>
                                        <th class="text-end fs-1"><?=ucfirst(lang('general.actions'));?></th>
                                </tr>
                                </thead>
                                <tbody class="list">
                                <?php foreach($media as $mediaItem):?>
                                    <tr>
                                        <td class="name fs-0 text-primary"><i class="<?=$mediaItem['icon'];?> me-2  fs-2"></i><a href="<?=current_url().'/'.$mediaItem['name'];?>"><?=$mediaItem['name'];?></a></td>
                                        <td class="size fs-0 text-primary"><?=$mediaItem['size'];?></td>
                                        <td class="text-end">
                                            <div>
                                                <?php
                                                if($mediaItem['name'] !== '..'):
                                                    $dataArray['question'] = ucfirst(lang('general.delete_question', [lang('general.media')]));
                                                    $dataArray['link'] = '/cms/media/delete/'.$currentFolder.'/'.$mediaItem['name'];
                                                    $dataArray['data'][ucfirst(lang('general.media'))] = $mediaItem['name'];

                                                    $jsonData = json_encode($dataArray);
                                                    ?>
                                                    <a class="btn btn-link p-0 ms-2 delete_button" href='#' datasrc='<?=$jsonData;?>' data-bs-toggle="modal" data-bs-target="#DeletionModal"><i data-bs-toggle="tooltip" data-bs-placement="top" title="<?=ucfirst(lang('general.delete'));?>"><i class="text-500 fas fa-trash-alt"></i></i></a>
                                                <?php endif; ?>
                                            </div>
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

<?= $this->include('\Webigniter\Views\partials\media_folder_add') ?>
<?= $this->include('\Webigniter\Views\partials\media_file_add') ?>
<?= $this->include('\Webigniter\Views\partials\deletion_modal') ?>
<?= $this->endSection() ?>