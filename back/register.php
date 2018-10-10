<?php
include("../config.php");
include("../classes/Db.php");
session_start();
if(empty($_SESSION['user'])) {
    header('location: login.php');
    exit();
}

Db::connect($db['host'], $db['db'], $db['user'], $db['pass']);
    if($_POST) {
        if(isset($_POST['login']) && isset($_POST['heslo']) && isset($_POST['heslo2']) && isset($_POST['email'])) {
            $login = $_POST['login'];
            $heslo = $_POST['heslo'];
            $email = $_POST['email'];
            if($_POST['heslo'] == $_POST['heslo2']) {
                $user = array(
                  'login' => $login,
                  'heslo' => sha1($heslo),
                  'email' => $email
                );
                if(Db::query('SELECT * FROM ' . $tables['users'] . ' WHERE login = ?', $login) == 0) {
                    $save = Db::insert($tables['users'], $user);
                    if ($save) {
                        $zprava = 'Uživatel byl úspěšně vytvořen';
                        unset($_POST);
                        unset($login);
                        unset($email);
                    }
                }
                else {
                    $zprava = 'Uživatel s tímto uživatelským jménem už existuje';
                }
            } else {
                $zprava = 'Hesla se neschodují';
            }
        } else {
            $zprava = 'Vyplňte prosím všechny pole';
        }
    }
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Bobřík informatiky - online dotazníky</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/apps.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
<body>
<header class="container">
    <img src="../images/header.png" alt="Hlavní logo soutěže" />
</header>
<main class="container">
    <nav class="navbar navbar-expand-lg">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Administrace</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="create.php">Vytvořit nový dotazník</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Přidat uživatele</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="jumbotron">
        <h2 class="text-center">Registrace nového uživatele</h2>
        <?php if(isset($zprava)) : ?>
        <h4 class="text-danger text-center"><?= $zprava ?></h4>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <input class="form-control" type="text" name="login" required="required" placeholder="Uživatelské jméno" value="<?php if(isset($login))  echo $login; ?>" />
            </div>

            <div class="form-group">
                <input class="form-control" type="password" name="heslo" required="required" placeholder="Heslo" />
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="heslo2" required="required" placeholder="Heslo znovu" />
            </div>
            <div class="form-group">
                <input class="form-control" type="email" name="email" required="required" placeholder="Email" value="<?php if(isset($email))  echo $email; ?>" />
            </div>
            <input type="submit" id="save" value="Přidat uživatele" class="btn btn-danger btn-lg" />

        </form>
    </div>
</main>
<footer class="container">
    <p>&copy; Petr Hanzal 2018</p>
</footer>
</body>