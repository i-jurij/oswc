<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <style>
            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                background-color: Beige;
            }

            #container {
              width: 100%;
              height: 100%;
              position: absolute;
              top: 0;
              left: 0;
              overflow: auto;
            }

            .in {
              width: 250px;
              height: 250px;
              position: absolute;
              top: 0;
              right: 0;
              bottom: 0;
              left: 0;
              margin: auto;
            }

            fieldset {
              padding: 2rem;
              border-radius: 0.4rem;;
              background: var(--bgcolor-content) none repeat scroll 0% 0%;
              box-shadow: var(--boxshadow);
            }

            .form-element {
              margin-bottom: 1rem;
            }

            h1 {
              text-align: center;
            }

            button {
              width: 100%;
              margin:0 auto;
            }
        </style>
    </head>
    <body>
      <div id="container">
        <div class="in">
            <h1>Login</h1>
            <fieldset>
              <legend><?php echo $message = (isset($message)) ? $message : ""; ?></legend>
                <form method="post" action="" name="signin-form">
                  <div class="form-element">
                    <input type="text" name="login" 
                            value="<?php echo (isset($_POST["login"])) ? htmlentities($_POST["login"]) : null; // Заполняем поле по умолчанию ?>" 
                            maxlength="255"
                            placeholder="Name" 
                            required />
                  </div>
                  <div class="form-element">
                    <input type="password" name="password" placeholder="Password" required />
                  </div>
                  <button type="submit" value="submit">Log In</button>
                </form>
            </fieldset>

            <p><a href="<?php echo URLROOT; ?>">Home page</a></p>
        </div>
      </div>
    </body>
</html>
