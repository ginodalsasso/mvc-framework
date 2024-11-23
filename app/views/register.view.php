<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <main>
            <form method="POST">

                
                <?php /* if (!empty($data['errors'])): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($data['errors'] as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; */ ?>

                <input value="<?= old_value("username") ?>" name="username" type="username" placeholder="username">
                <label>username</label>
                <div><?= $user->getError('username') ?></div>

                <input value="<?= old_value("email") ?>" name="email" type="email" placeholder="name@example.com">
                <label for="floatingInput">Email address</label>
                <div><?= $user->getError('email') ?></div>
                
                <input value="<?= old_value("password") ?>" name="password" type="password" placeholder="Password">
                <label>Password</label>
                <div><?= $user->getError('password') ?></div>
                <!--
                <div>
                    <input name="password_confirm" type="password" placeholder="Password confirm">
                    <label for="password_confirm">
                        Password Confirm
                    </label>
                </div>
                <div class="form-check text-start my-3">
                    <input name="terms" type="checkbox" value="1">
                    <label>
                        Accept terms
                    </label>
                </div> 
                        -->

                <button class="btn btn-primary w-100 py-2" type="submit">Sign up</button>
                <a href="<?= ROOT ?>">Home</a>
                <a href="<?= ROOT ?>/login">Login</a>
            </form>
        </main>
    </body>

</html>