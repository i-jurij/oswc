<fieldset>
    <legend><?php echo $message = (isset($message)) ? $message : ""; ?></legend>
      <form method="post" action="" name="signin-form">
        <div class="form-element">
          <label>Username</label>
          <input type="text" name="login" 
                  value="<?php echo (isset($_POST["login"])) ? htmlentities($_POST["login"]) : null; // Заполняем поле по умолчанию ?>" 
                  maxlength="255"
                  required />
        </div>
        <div class="form-element">
          <label>Password</label>
          <input type="password" name="password" required />
        </div>
        <button type="submit" value="submit">Log In</button>
      </form>
</fieldset>

<p>or </p>
<p><a href="<?php echo URLROOT; ?>">Back to home page</a></p>
