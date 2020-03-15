<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <?php require 'views/header.php'; ?>

    <div id="login-main">
        <form >
            <h2>Iniciar sesión</h2>

            <p>
                <label for="username">Username</label>
                <input type="text" name="username" id="username">
            </p>
            <p>
                <label for="password">password</label>
                <input type="text" name="password" id="password">
            </p>
            <p>
                <input type="submit" value="Iniciar sesión" />
            </p>

            <p>
                ¿No tienes cuenta? <a href="<? echo constant('URL'); ?>signup">Registrarse</a>
            </p>
        </form>
    </div>

    <?php require 'views/footer.php'; ?>
</body>
</html>