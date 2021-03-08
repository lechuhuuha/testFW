    <?php include 'header.html.php' ?>
    <nav>
        <ul>
            <li><a href="<?php echo URLROOT ?>">Home</a></li>
            <li><a href="<?php echo URLROOT . 'joke/list' ?>">Jokes List
                </a></li>
            <li><a href="<?php echo URLROOT . 'joke/edit' ?>">Add a new Joke
                </a></li>
            <?php if ($loggedIn) : ?>
                <li><a href="<?php echo URLROOT ?>logout">Log out</a>
                </li>
            <?php else : ?>
                <li><a href="<?php echo URLROOT ?>login">Log in</a></li>
            <?php endif; ?>

        </ul>

    </nav>
    <main>
        <?= $output ?>
    </main>
    <?php include 'footer.html.php' ?>