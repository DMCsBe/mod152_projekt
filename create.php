<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>create</title>
</head>
<body>
    <?php 
    $selected = "create";
    require_once 'block/nav.php';
    require_once 'controller/PostManager.php';
    $creationResult = null;
    if (isset($_POST["submitbutton"])) {
        $creationResult = PostManager::getInstance()->createPost();
    }
    $succsess = substr($creationResult,0,12) == "sucsessfully";
    ?>
    <?php if (!$succsess): ?>
        <?php if (isset($_SESSION["logedin"])): ?>
        <form class="top_level" enctype="multipart/form-data" method="POST">
            <input class="input_text" autocomplete="off" type="text" name="title" maxlength="30" placeholder="post title">
            <br>
            <select name="license">
                <option value="all-rights-reserved" selected>All rights reserved</option>
                <option value="cc-by-nc-nd">CC BY-NC-ND</option>
                <option value="cc-by-nd">CC BY-ND</option>
                <option value="cc-by-nc-sa">CC BY-NC-SA</option>
                <option value="cc-by-nc">CC BY-NC</option>
                <option value="cc-by-sa">CC BY-SA</option>
                <option value="cc-by">CC BY</option>
                <option value="cc0">CC0 / Public Domain</option>
            </select>
            <br>
            <select id="post_active" name="active">
                <option value=0 selected>not active</option>
                <option value=1 >active</option>
            </select>
            <br>
            <input type="hidden" name="MAX_FILE_SIZE" value="100000000">
            <input id="input_image" class="file" type="file" name="image">
            <label for="input_image">Choose a File</label>
            <br>
            <input class="submit" type="submit" name="submitbutton" value="Create post">
        </form>
        <?php endif; ?>
        <?php if (!isset($_SESSION["logedin"])): ?>
            <div class="top_level">
                <h1 style="color: white">You need to LogIn to be able to create a Post.</h1>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (isset($_POST["submitbutton"])): ?>
        <div id="container">
            <?php
            if ($succsess){
                $creationResult = $creationResult . "<a href=\"my_posts.php\">my posts</a>";
            }
            ?>
            <p id="message"><?php echo $creationResult ?></p>
        </div>
    <?php endif; ?>
</body>
</html>