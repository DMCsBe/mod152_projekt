<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>home</title>
</head>
<body>
    <?php 
    $selected = "login";
    require_once 'block/nav.php';
    require_once 'controller/PostManager.php';
    $creationResult = null;
    if (isset($_POST["submitbutton"])) {
        $creationResult = PostManager::getInstance()->login();
    }
    if (!isset($_SESSION["logedin"])){
        $creationResult = "You are already Loged In";
        $_POST["submitbutton"] = True;
    }
    ?>
    <div class="top_level">
    <form enctype="multipart/form-data" method="POST">
        <input class="input_text" autocomplete="off" type="text" name="username" maxlength="20" placeholder="username">
        <br>
        <input class="input_text" autocomplete="off" type="password" name="password" maxlength="255" placeholder="password">
        <br>
        <input class="submit" type="submit" name="submitbutton" value="LogIn">
    </form>
    </div>
    <div style="text-align: center">
        <a class="atoother" href="signup.php">Dont have a account yet? Sign Up</p>
    </div>
    <?php if (isset($_POST["submitbutton"])): ?>
        <div id="container">
            <p id="message"><?php echo $creationResult ?></p>
        </div>
    <?php endif; ?>
</body>
</html>