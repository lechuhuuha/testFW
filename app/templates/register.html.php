<?php
if (!empty($errors)) :
?>
    <div class="errors">
        <p>Your account could not be created,
            please check the following:</p>
        <ul>
            <?php
            foreach ($errors as $error) :
            ?>
                <li><?= $error ?></li>
            <?php
            endforeach; ?>
        </ul>
    </div>
<?php
endif;
?>
<form action="" method="post">
    <label for="email">Your email address</label>
    <input name="author[email]" value="<?= $email['email'] ?? '' ?>" id="email" type="email">
    <input type="hidden" name="author[authorID]" value="<?php echo (int)$lastAuthor['id'] + 1 ?>" type="email">
    <label for="name">Your name</label>
    <input name="author[name]" value="<?= $author['name'] ??  '' ?>" id="name" type="text">
    <label for="password">Password</label>
    <input name="author[password]" value="<?= $author['password'] ??  '' ?>" id="password" type="password">
    <input type="submit" name="submit" value="Register account">
</form>