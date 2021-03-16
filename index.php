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
    require_once 'controller/PostManager.php';
    if (isset($_POST["like"])){
        PostManager::getInstance()->likepost();
    }
    if (isset($_SESSION["sort"])){
        if (isset($_POST["sort"])){
            if($_POST["id"] == "new"){
                $sort = "likes";
                $_SESSION["sort"] = "new";
                $other = "Sort by likes";
            }else{
                $_SESSION["sort"] = "like";
                $sort = "new";
                $other = "Sort by new";
            }
        }else{
            if($_SESSION["sort"] == "new"){
                $sort = "likes";
                $other = "Sort by likes";
            }else{
                $sort = "new";
                $other = "Sort by new";
            }
        }
    }else{
        $_SESSION["sort"] = "new";
        $sort = "new";
        $other = "Sort by new";
    }
    $allPosts = PostManager::getInstance()->get_all_posts();
    ?>
    <div id= "main">
        <form class="top_level" enctype="multipart/form-data" method="POST">
            <input type="hidden" name="id" value="<?php echo $sort ?>">
            <input type="submit" name="sort" id="sort" value="<?php echo $other ?>">
        </form>
        <?php foreach ($allPosts as $post):?>
                <div class="post">
                    <p class="title"><span class="title_text"><?php echo $post["title"]?></span></p>
                    <img class="image" src="post/images/<?php echo $post["post_id"]?>.png" loading=lazy>
                    <div class="div_like">
                        <form class="inline" enctype="multipart/form-data" method="POST">
                            <input type="hidden" name="likes" value="<?php echo $post["likes"] ?>">
                            <input type="hidden" name="id" value="<?php echo $post["post_id"] ?>">
                            <input type="submit" name="like" id="like" value="LIKE">
                        </form>
                        <p class="inline" style="color:white"><?php echo $post["likes"] ?></p>
                        <p class="inline copyright"><?php echo $post["license"] ?></p>
                    </div>
                </div>
        <?php endforeach;?>
    </div>
</body>
</html>