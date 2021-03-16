<?php
    session_start()
?>
<nav id="navbar">
    <ul id="navlist">
        <div id="left">
            <li class = "<?php if ($selected == "home") echo ' active'; ?>"><a href="../">HOME</a></li>
            <li class = "<?php if ($selected == "create") echo ' active'; ?>"><a href="create.php">create</a></li>
            <li class = "<?php if ($selected == "my_posts") echo ' active'; ?>"><a href="my_posts.php">my posts</a></li>
        </div>
        <div id="right">
            <?php if(!isset($_SESSION["logedin"])):?>
                <li class = "<?php if ($selected == "login") echo ' active'; ?>"><a href="login.php">login</a></li>
            <?php else:?>
                <li class = "<?php if ($selected == "login") echo ' active'; ?>"><a href="logout.php">logout</a></li>
            <?php endif; ?>
        </div>
    </ul>
</nav>