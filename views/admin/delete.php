<?php if (user_can('delete_social_links')): ?>
    <?php if (!empty($row)): ?>
        <form method="post" enctype="multipart/form-data">
            <div class="row g-3 col-md-10 mx-auto shadow p-3 rounded mt-3">
                <?= csrf() ?>
                <h4 id="deleted_record">Deleted Record</h4>

                <div class="alert alert-danger text-center" id="are_you_sure_you_want_to_delete_this_record">Are you sure you
                    want to delete this record?</div>
                <div class="d-flex justify-content-around">
                    <label class="text-center  mb-3">
                    Image: <br>
                    <img src="<?= get_image($row->image) ?>" class="img-thumbnail"
                        style="width:100%;max-width:200px;height:200px;object-fit:cover;" />
                </label>
            </div>
                <?php $errors = get_value('errors') ?? [];
                ?>

                <div class="mb-3 col-md-12">
                    <div class="form-control"><b>Title: </b><?= esc($row->title) ?></div>
                </div>

                <div class="mb-3 col-md-12">
                    <div class="form-control"><b>Slug: </b><?= esc($row->slug) ?></div>
                </div>

                <div class="mb-3 col-md-6">
                    <div class="form-control"><b>Icon: </b><?= esc(ucfirst($row->icon)) ?></div>
                </div>

                <div class="mb-3 col-md-6">
                    <div class="form-control"><b>Permission: </b><?= esc($row->permission) ?></div>
                </div>
                
                <div class="mb-3 col-md-12">
                <div class="form-control">
                    <b>Parent: </b>
                    <?php if (!empty($row->parent)): ?>
                        <?php foreach ($all_items as $item) : ?>
                            <?php if ($item->id == $row->parent): ?>
                                <?= $item->title ?>
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
            </div>

                <div class="mb-3 col-md-6">
                    <a href="<?= ROOT ?>/<?= $admin_route ?>/<?= $plugin_route ?>">
                        <button type="button" class="btn btn-secondary text-light"><i class="fa-solid fa-angles-left"></i> <span
                                id="back"> Back</span></button>
                    </a>
                </div>
                <div class="mb-3 col-md-6">
                    <button type="submit" class="btn btn-red text-light float-end"><span id="delete">Delete </span> <i
                            class="fa-solid fa-trash-can"></i></button>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-danger text-center" id="that_record_was_not_found">That record was not found!</div>

        <a href="<?= ROOT ?>/<?= $admin_route ?>/<?= $plugin_route ?>">
            <button class="btn btn-blue text-light"><i class="fa-solid fa-angles-left"></i> <span id="back">
                    Back</span></button>
        </a>
    <?php endif ?>

<?php else: ?>

    <div class="alert alert-danger text-center">
        Access denied. You dont have permission for this action
    </div>

<?php endif ?>