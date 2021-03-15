<nav id="navbar">
    <ul id="navlist">
        <div id="left">
            <li class = "<?php if ($selected == "home") echo ' active'; ?>"><a href="../">HOME</a></li>
            <li class = "<?php if ($selected == "create") echo ' active'; ?>"><a href="create.php">create</a></li>
            <li class = "<?php if ($selected == "my_posts") echo ' active'; ?>"><a href="my_posts.php">my posts</a></li>
        </div>
        <div id="right">
            <li class = "<?php if ($selected == "search") echo ' active'; ?>"><a id="search_link" href="search.php"><img id="search_icon" src="images/search_icon.png" alt="search"></a></li>
            <li class = "<?php if ($selected == "profile") echo ' active'; ?>"><a href="profile.php">profile</a></li>
            <li class = "<?php if ($selected == "login") echo ' active'; ?>"><a href="login.php">login</a></li>
        </div>
    </ul>
</nav>