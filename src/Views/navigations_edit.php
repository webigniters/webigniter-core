<?php
/** @var Navigation $navigation */
/** @var NavigationItem $navItem */

use Webigniter\Libraries\Navigation;
use Webigniter\Libraries\NavigationItem;
use Webigniter\Models\ContentModel;

?>

<?= $this->extend('\Webigniter\Views\layout') ?>

<?= $this->section('content') ?>


    <div id="confirmation" style="display: none;">
        <div class="alert alert-success border-2 d-flex align-items-center" role="alert">
            <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-3"></span></div>
            <p class="mb-0 flex-1">
                <?= ucfirst(lang('messages.edit_success', [lang('general.navigation')])); ?>
            </p>
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>

    <div class="w-auto pb-4 pt-2 d-flex flex-row-reverse">
        <button class="btn btn-primary me-4 mb-1" type="button" data-bs-toggle="modal" data-bs-target="#addNavItem"><?=ucfirst(lang('general.navigation_item_add'));?></button>
        <div class="btn btn-primary me-4 mb-1" id="serialize"><?=ucfirst(lang('general.navigation_order_save'));?></div>
    </div>
    <form method="post">
        <div class="card mb-3">
            <div class="card-header">
                <div class="row flex-between-end">
                    <div class="col-auto align-self-center">
                        <h3 class="mb-2" data-anchor="data-anchor"><?=ucfirst(lang('general.edit')).' '.$navigation->getName();?></h3>
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <ol class="sortable list-group" id="navItems">
                    <?= getItems($navigation);  ?>
                </ol>
            </div>
        </div>
    </form>

    <script>
        <?php /* Inline script because of PHP inserts */ ?>

        $().ready(function(){
            const ns = $('ol.sortable').nestedSortable({
                forcePlaceholderSize: true,
                handle: 'div',
                helper: 'clone',
                items: 'li',
                opacity: .6,
                placeholder: 'placeholder',
                revert: 250,
                tabSize: 25,
                tolerance: 'pointer',
                toleranceElement: '> div',
                maxLevels: 3,
                isTree: true,
                expandOnHover: 700,
                startCollapsed: false
            });


            $("#serialize").click(function(e) {
                e.preventDefault();
                var serialized = $('ol.sortable').nestedSortable('serialize');
                $.ajax({
                    url: "/cms/ajax/saveNavOrder",
                    method: "POST",
                    data: {sort: serialized, navId: <?=$navigation->getId();?>},
                    complete:function(){
                        $('#confirmation').show();
                    }
                });
            });

            $("#addNavItemForm").submit(function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.

                const form = $(this);
                const formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: "/cms/ajax/addNavItem",
                    data: formData,
                    success: function(result){

                        const resultArray = $.parseJSON(result);

                        $("#navItems").append('<li id="item_'+resultArray['itemId']+'" style="list-style-type: none;">'+
                        '<div class="list-group-item row d-flex">'+
                        '<div class="col">' + $("#name").val() +'</div>'+
                        '<div class="col">'+resultArray['target']+'</div>'+
                        '<div class="col text-end"><a onclick ="deleteNavItem($(this), '+resultArray['itemId']+')"><i class="far fa-trash-alt text-red" role="button"></i></a></div>'+
                        '</div></li>');
                    }
                 });

                $('#addNavItem').modal('hide');

            });
        });


        function deleteNavItem(row, itemId){
            row.closest('li').remove();
            $.ajax({
                url: "/cms/ajax/deleteNavItem",
                method: "POST",
                data: {item: itemId}
            });
        }

    </script>
<?= $this->include('\Webigniter\Views\partials\navigation_item_add') ?>
<?= $this->endSection() ?>

<?php

function getItems(Navigation $navigation, $parentId = null): string
{
    global $navItems;

    $contentModel = new ContentModel();

    $navItems = $navItems ?? '';

    foreach($navigation->getNavigationItems($parentId) as $item)
    {
        if($item->getContentId()){
            $content = $contentModel->find($item->getContentId());

            $target = '<span class="fst-italic">'.$content->getName().'</span>';
        }
        else{
            $target = $item->getLink();
        }

       $navItems .= '<li id="item_'.$item->getId().'" style="list-style-type: none;">';
       $navItems .= '<div class="list-group-item row d-flex">';
       $navItems .= '<div class="col">'.$item->getName().'</div>';
       $navItems .= '<div class="col">'.$target.'</div>';
       $navItems .= '<div class="col text-end"><a onclick ="deleteNavItem($(this), '.$item->getId().')"><i class="far fa-trash-alt text-red" role="button"></i></a></div>';
       $navItems .= '</div>';

       if($item->hasChildren())
       {
           $navItems .=  '<ol>';

           getItems($navigation, $item->getId());

           $navItems .=  '</ol>';
       }
       echo '</li>';
   }

    return $navItems;
}