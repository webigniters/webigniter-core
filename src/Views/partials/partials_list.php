<?php
/** @var array $partials */
/** @var Partial $partial */
/** @var Content $content */

use Webigniter\Libraries\Content;
use Webigniter\Libraries\Partial;

?>

<div class="modal fade" id="partialsList" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg mt-6" role="document">
        <div class="modal-content border-0">
            <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                    <h4 class="mb-1" id="staticBackdropLabel"><?=ucfirst(lang('general.partial_add_extended'));?></h4>
                </div>
                <div class="p-4">
                    <table class="table table-bordered table-striped fs--1 mb-0">
                        <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort fs-1" data-sort="name"><?=ucfirst(lang('general.name'));?></th>
                            <th class="text-end fs-1"><?=ucfirst(lang('general.actions'));?></th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        <?php foreach($partials as $partial):?>
                            <form method="post" action="/cms/content/<?=$content->getId();?>/add-partial">
                                <input type="hidden" name="partial_id" value="<?=$partial->getId();?>">
                                <tr>
                                    <td class="name fs-0"><?=$partial->getName();?></td>
                                    <td class="text-end">
                                        <div>
                                            <input type="submit" class="btn btn-primary" value="<?=ucfirst(lang('general.partial_add'));?>">
                                        </div>
                                    </td>
                                </tr>
                            </form>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>