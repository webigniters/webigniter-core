<?php
/** @var object $elementData */
/** @var array|string $value */
/** @var string $fieldName */

use Webigniter\Libraries\MediaData;
use Webigniter\Models\MediaDataModel;

$media = null;
$fileId = set_value($fieldName, $value);

if($fileId)
{
    $mediaDataModel = new MediaDataModel();
    $media = $mediaDataModel->find($fileId);
}

$filename = $media ? $media->getFilename() : '';

$isImage = str_starts_with(mime_content_type(FCPATH.'/media/'.$media->getFilename()), 'image');

$fieldNameExplode = explode(':', $fieldName);
?>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mediaList<?=$fieldNameExplode[0];?>">
    <?=ucfirst(lang('general.media_select'));?>
</button>
<input type="hidden" id="<?=$fieldName;?>" name="<?=$fieldName;?>" value="<?=set_value($fieldName, $value);?>">
<span id="filename<?=$fieldNameExplode[0];?>">
    <?=$isImage ? '<img src="/media/'.$media->getFilename().'" alt="" style="width: 45px; height: 45px;" class="mx-2">' : '';?>
    <?=$filename;?></span>

<div class="modal fade modal-xl" id="mediaList<?=$fieldNameExplode[0];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><?=ucfirst(lang('general.media_select'));?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <iframe src="http://webigniter-cms.lcl/cms/media" width="100%" height="750"></iframe>

<!--                <table class="table table-bordered table-striped fs--1 mb-0">-->
<!--                    <thead class="bg-200 text-900">-->
<!--                    <tr>-->
<!--                        <th class="sort fs-1" data-sort="name">--><?//=ucfirst(lang('general.name'));?><!--</th>-->
<!--                        <th class="sort fs-1" data-sort="size">--><?//=ucfirst(lang('general.filesize'));?><!--</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody class="list" id="mediaList">-->
<!--                    --><?php //foreach(MediaData::getMediaDirectory() as $mediaItem):?>
<!--                        <tr>-->
<!--                            <td class="name fs-0 text-primary">-->
<!--                                --><?php //if(str_starts_with(mime_content_type(FCPATH.'/media/'.$mediaItem['name']), 'image')):?>
<!--                                    <img src="/media/--><?//=$mediaItem['name'];?><!--" alt="" class="me-1 rounded" style="width: 35px; height: 35px;">-->
<!--                                --><?php //else: ?>
<!--                                    <i class="--><?//=$mediaItem['icon'];?><!-- me-2  fs-5"></i>-->
<!--                                --><?php //endif; ?>
<!--                                --><?php //if($mediaItem['type'] === 'folder'):?>
<!--                                    <a href="#" id="folderClick:--><?//=$mediaItem['name'].':'.$fieldName;?><!--">--><?//=$mediaItem['name'];?><!--</a>-->
<!--                                --><?php //else: ?>
<!--                                    <a href="#" id="process:--><?//=$mediaItem['id'].':'.$fieldName;?><!--">--><?//=$mediaItem['name'];?><!--</a>-->
<!--                                --><?php //endif; ?>
<!--                            </td>-->
<!--                            <td class="size fs-0 text-primary">--><?//=$mediaItem['size'];?><!--</td>-->
<!--                        </tr>-->
<!--                    --><?php //endforeach; ?>
<!--                    </tbody>-->
<!--                </table>-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
