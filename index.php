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
    $selected = "home";
    require_once 'block/nav.php';
    require_once 'controller/post_manager.php';
    $allPosts = post_manager::getInstance()->get_all_posts();
    ?>
    <div id= "main">
    <?php
        foreach ($allPosts as $post) {
            echo "<div class=\"post\">";
            echo "<p class=\"title\"><span class=\"title_text\">" . $post["title"] . "</span></p>";
            echo "</div>";
        }
    ?>
    </div>
</body>
</html>