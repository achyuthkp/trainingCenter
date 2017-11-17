<?php
include("header.php");
?>
<div class='content'>
</div>
<div class="content">
    <?php
    if(isset($_SESSION['isLoggedIn'])){
        print "<h2 style=\"text-align:center;\">Welcome back,</h3>
                <h1 style=\"text-align:center;\">".$person['first_name']."!</h1>";
    }
    ?>
</div>
