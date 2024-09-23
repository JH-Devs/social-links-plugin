<?php if (user_can('add_social_link')): ?>
<form method="post" enctype="multipart/form-data">
    <div class="row g-3 col-md-10 mx-auto shadow p-3 rounded mt-3">
    <?=csrf()?>
        <h4 id="add_record">Add Record</h4>

       <div class="d-flex justify-content-around">
      
        <label class="text-center  mb-2">
            Image: <br>
                <img src="<?=get_image('')?>" class="img-thumbnail" style="cursor:pointer;width:100%;max-width:200px;height:200px;object-fit:cover;"/>
                <input onchange="display_image(event)" type="file" name="image" class="d-none">

                <?php if (!empty($errors['image'])): ?>
                    <small class="text-danger px-2"><?= $errors['image'] ?></small>
                <?php endif ?>
            </label>
            <?php $errors = get_value('errors') ?? [];
            ?>

        </div>

        <div class="mb-2 col-md-12">
            <label for="title" class="form-label" >Title</label>
            <input autofocus value="<?= old_value('title') ?>" type="text" class="form-control" name="title" >

            <?php if (!empty($errors['first_name'])): ?>
                <small class="text-danger px-2"><?= $errors['title'] ?></small>
            <?php endif ?>
        </div>

        <div class="mb-2 col-md-12">
            <label for="slug" class="form-label">Slug</label>
            <input value="<?=old_value('slug')?>" type="text" class="form-control" name="slug" >
            
            <?php if (!empty($errors['slug'])): ?>
                <small class="text-danger px-2"><?= $errors['slug'] ?></small>
            <?php endif ?>
        </div>

        <div class="mb-2 col-md-6">
            <label for="icon" class="form-label">Icon</label>
            <input value="<?=old_value('icon')?>" type="text" class="form-control" name="icon" >
            
            <?php if (!empty($errors['icon'])): ?>
        <small class="text-danger px-2"><?= $errors['icon'] ?></small>
    <?php endif ?>
        </div>

        <div class="mb-2 col-md-6">
            <label for="permission" class="form-label">Permission</label>

            <select class="form-select" name="permission">
                <option <?= old_select('not_logged_in', 'not_logged_in') ?> value="not_logged_in">
                    <?= esc(ucfirst(str_replace('_', ' ', 'not_logged_in'))) ?>
                </option>
                <option <?= old_select('logged_in', 'logged_in') ?> value="logged_in">
                    <?= esc(ucfirst(str_replace('_', ' ', 'logged_in'))) ?>
                </option>
            </select>

            <?php if (!empty($errors['permission'])): ?>
        <small class="text-danger px-2"><?= $errors['permission'] ?></small>
    <?php endif ?>
        </div>

        <div class="mb-2 col-md-6">
            <label for="active" class="form-label"><?= esc('Active') ?></label>
            <select class="form-select" name="disabled">
                <option value="0">Select</option>
                <option <?= old_select('disabled', '0') ?>  value="0"><?= esc('Yes') ?></option>
                <option <?= old_select('disabled', '1') ?> value="1"><?= esc('No') ?></option>
            </select>
        </div>
        <div class="mb-2 col-md-6"></div>

        <div class="mb-2 col-md-6">
            <a href="<?=ROOT?>/<?=$admin_route?>/<?=$plugin_route?>">
                <button type="button" class="btn btn-secondary text-light"><i class="fa-solid fa-angles-left"></i> <span id="back"> Back</span></button>
                </a>
            </div>
        <div class="mb-2 col-md-6">
                <button type="submit" class="btn btn-lime text-light float-end"><span id="save">Save </span> <i class="fa-solid fa-save"></i></button>
        </div>
    </div>
</form>

<script type="text/javascript">
    var valid_image = true;
    function display_image(e)
    {
        let allowed = ['image/jpeg', 'image/png', 'image/webp'];
        let file = e.currentTarget.files[0];

        if (!allowed.includes(file.type)) {
            alert("Only files of this type allowed: " + allowed.toString().replaceAll('image/',''));

            valid_image = false;
            return;
        }
        valid_image = true;
        e.currentTarget.parentNode.querySelector('img').src = URL.createObjectURL(file);
    }
    function submit_form(e)
    {
        if (!valid_image) {
            e.preventDefault()
            alert("Please add a valid image");
            return;
        }
       
    }
</script>

<?php else: ?>

<div class="alert alert-danger text-center">
    Access denied. You dont have permission for this action
</div>

<?php endif ?>