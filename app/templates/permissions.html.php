<h2>Edit <?php $author->name ?>'s Permissions</h2>
<form action="" method="post">

    <?php foreach ($permissions as $name => $value) : ?>
        <div>
            <input type="checkbox" name="permissions[]" value="<?= $value ?>" <?php if ($author->hasPermission($value)) : echo 'checked';
                                                                                endif; ?> id="">
            <label for=""><?= $name ?></label>
        </div>
    <?php endforeach; ?>
    <input type="submit">
</form>