<?php
/** @var string $currentFolder */
?>

<div class="modal fade" id="mediaFolderAdd" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mt-6" role="document">
        <div class="modal-content border-0">
            <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                    <h4 class="mb-1" id="staticBackdropLabel"><?=ucfirst(lang('general.folder_add_extended'));?></h4>
                </div>
                <div class="p-4">

                    <form method="post" action="/cms/media/add-folder">
                        <input type="hidden" name="current_folder" value="<?=$currentFolder;?>">
                        <div class="mb-3">
                            <label class="form-label" for="name"><?=ucfirst(lang('general.name'));?></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder="<?=ucfirst(lang('general.name'));?>" value="<?=set_value('name');?>" required/>
                        </div>

                        <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.save'));?></button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>