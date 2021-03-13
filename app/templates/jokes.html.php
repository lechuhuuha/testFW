<h2>CATEGORIES</h2>
<ul>
    <?php foreach ($categories as $category) : ?>
        <li><a href="<?php echo URLROOT . 'joke/list?category=' . $category->id ?>"><?= $category->name ?></a></li>
    <?php endforeach; ?>
</ul>
<h2>Joke</h2>
<p><?= $variables['totalJokes'] ?> jokes have been submitted to the Internet Joke Database.</p>
<?php foreach ($jokes as $joke) : ?>
    <blockquote>
        <p>
            <?= htmlspecialchars(
                $joke->joketext,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
            (by <a href="mailto:<?= htmlspecialchars(
                                    $joke->getEmail()[0]->email,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>">
                <?= htmlspecialchars(
                    $joke->getAuthor()->name,
                    ENT_QUOTES,
                    'UTF-8'
                ); ?></a> on <?php
                                $date = new DateTime($joke->jokedate);
                                echo $date->format('jS F Y');
                                ?>)
            <?php if ($userId == $joke->authorid) : ?>
                <a href="<?php echo URLROOT .  'joke/edit?id=' . $joke->id ?>">Edit
                </a>
        <form action="<?php echo URLROOT ?>joke/delete" method="post">
            <input type="hidden" name="id" value="<?= $joke->id ?>">
            <input type="submit" value="Delete">
        </form>
    <?php endif; ?>

    </p>
    </blockquote>
<?php endforeach; ?>