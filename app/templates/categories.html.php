<h2>Categories</h2>
<a href="<?php echo URLROOT . 'category/edit' ?>">Add new category
</a>
<?php foreach ($categories as $category) : ?>
    <blockquote>
        <p><?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?>
            <a href="<?php echo URLROOT . 'category/edit?id=' . $category->id ?>">Edit
            </a>
        <form action="<?php echo URLROOT ?>category/delete" method="post">
            <input type="hidden" name="id" value="<?= $category->id ?>">
            <input type="submit" value="Delete">
        </form>
        </p>
    </blockquote>
<?php endforeach; ?>