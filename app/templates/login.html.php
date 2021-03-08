<?php
if (isset($errors)):
 echo '<div class="errors">' . $errors . '</div>';
endif;
?>
<form method="post" action="">
 <label for="email">Your username</label>
 <input type="text" id="name" name="name">
 <label for="password">Your password</label>
 <input type="password" id="password" name="password">
 <input type="submit" name="login" value="Log in">
</form>
<p>Don't have an account? <a href="<?php echo URLROOT ?>author/register">Click here to register an account</a></p>
