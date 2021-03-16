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
    PostManager::getInstance()->logout();
    ?>
    <div class="top_level">
        <h1 style="color: white">You are loged out!</h1>
    </div>
</body>
</html>