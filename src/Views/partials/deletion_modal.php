<div class="modal fade" id="DeletionModal" tabindex="-1" role="dialog" aria-labelledby="DeletionModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel"><?=ucfirst(lang('general.delete_confirm'));?></h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="delete_question" class="text-dark"></p>
                <table class="table table-borderless" id="delete_details"></table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal" aria-label="Close"><?=ucfirst(lang('general.discard'));?></button>
                <a href="#" class="btn btn-danger" id="delete_button"><?=ucfirst(lang('general.delete'));?></a>
            </div>
        </div>
    </div>
</div>
