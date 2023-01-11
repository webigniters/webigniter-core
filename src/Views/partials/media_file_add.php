<?php
/** @var string $currentFolder */
?>

<div class="modal fade" id="mediaFileAdd" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mt-6" role="document">
        <div class="modal-content border-0">
            <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                    <h4 class="mb-1" id="staticBackdropLabel"><?=ucfirst(lang('general.media_add_extended'));?></h4>
                </div>
                <div class="p-4">

                    <form method="post" action="/cms/media/add" enctype="multipart/form-data">
                        <input type="hidden" name="current_folder" value="<?=$currentFolder;?>">
                        <div class="mb-3">
                            <label class="form-label" for="media"><?=ucfirst(lang('general.media'));?></label>
                            <input class="form-control" id="media" type="file" name="media[]" required multiple/>
                        </div>

                        <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.add'));?></button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>