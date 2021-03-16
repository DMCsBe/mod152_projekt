<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>my posts</title>
</head>
<body>
    <?php 
    $selected = "my_posts";
    require_once 'block/nav.php';
    require_once 'controller/PostManager.php';
    ?>
    <?php if (isset($_POST["active"])){
        PostManager::getInstance()->changeactive();
    }
    ?>
    <?php if (isset($_SESSION["logedin"])): ?>
    <div class="top_level" style="text-align: left">
        <?php $myposts = PostManager::getInstance()->getUserPost();?>
            <?php foreach ($myposts as $post): ?>
                <?php if ($post["active"] === 1){
                    $active = "deactivate";
                }else if($post["post_id"] == "noposts"){
                    $active = "off";
                }else{
                    $active = "activate";
                }
                ?>
                <div class="my_post">
                    <div id="title_div">
                        <p class="my_title"><span class="title_text"><?php echo $post["title"] ?></span></p>
                    </div>
                    <img class="my_image" src="post/thumbnail/<?php echo $post["post_id"] ?>.<?php echo $post["image_ending"] ?>" loading=lazy>
                    <?php if($active != "off"):?>
                        <form class="my_active" enctype="multipart/form-data" method="POST">
                            <input type="hidden" name="id" value="<?php echo $post["post_id"] ?>">
                            <input type="submit" name="active" id="active" value="<?php echo $active?>">
                        </form>
                <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
    </div>
    <?php endif; ?>
    <?php if (!isset($_SESSION["logedin"])): ?>
        <div class="top_level">
            <h1 style="color: white">You need to LogIn to be able to view your Posts.</h1>
        </div>
    <?php endif; ?>
</body>
</html>