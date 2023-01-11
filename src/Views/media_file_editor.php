<?php
/** @var string $file */
/** @var string $filesize */
/** @var string $mimeType */
/** @var ?MediaData $customData */

use Webigniter\Libraries\MediaData;

?>

<?= $this->extend('\Webigniter\Views\layout') ?>

<?= $this->section('content') ?>
    <div class="w-auto pb-4 pt-2 d-flex flex-row-reverse">
        <a class="btn btn-primary mb-1" href="<?=$file;?>" target="_blank"><?=ucfirst(lang('general.open_mediafile'));?></a>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h3 class="mb-0" data-anchor="data-anchor"><?=basename($file);?> <small class="text-muted">(<?=$filesize;?>)</small></h3>
                </div>
            </div>
        </div>
        <div class="card-body pt-3">
            <div class="tab-content">
                <div class="tab-pane preview-tab-pane active" role="tabpanel" aria-labelledby="tab-dom-cfcec397-a35c-4994-a54a-50bf30775d88" id="dom-cfcec397-a35c-4994-a54a-50bf30775d88">
                    <div class="row">
                        <div class="col">
                            <hr>
                            <?php if(str_starts_with($mimeType,'image')):?>
                                <img src="<?=$file;?>" class="img-fluid" alt="<?=set_value('alt', $customData ? $customData->getAlt() : '');?>">
                            <?php elseif(str_starts_with($mimeType,'application/pdf')):?>
                                <div class="ratio ratio-1x1">
                                    <iframe src="<?=$file;?>"></iframe>
                                </div>
                            <?php elseif(str_starts_with($mimeType,'text')):?>
                                <div class="ratio ratio-1x1">
                                    <iframe src="<?=$file;?>"></iframe>
                                </div>
                            <?php elseif(str_starts_with($mimeType,'video')):?>
                                <div class="ratio ratio-1x1">
                                    <iframe src="<?=$file;?>"></iframe>
                                </div>
                            <?php elseif(str_starts_with($mimeType,'audio')):?>
                                    <iframe src="<?=$file;?>"></iframe>
                            <?php endif; ?>
                        </div>
                        <div class="col-5">
                            <form method="post">
                                <input type="hidden" name="custom_data" value="<?=$customData ? $customData->getId() : '';?>">
                                <div class="mb-3">
                                    <label class="form-label" for="filename"><?=ucfirst(lang('general.filename'));?></label>
                                    <input class="form-control" id="filename" type="text" name="filename" value="<?=set_value('filename', basename($file));?>" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="alt"><?=ucfirst(lang('general.alt'));?></label>
                                    <input class="form-control" id="alt" type="text" name="alt" value="<?=set_value('alt', $customData ? $customData->getAlt() : '');?>" />
                                </div>

                                <button class="btn btn-primary" type="submit"><?=ucfirst(lang('general.save'));?></button>
                                <a href="./" class="btn btn-secondary"><?=ucfirst(lang('general.discard'));?></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>