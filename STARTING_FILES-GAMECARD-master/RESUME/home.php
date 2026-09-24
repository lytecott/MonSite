<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="home.css">
    <title>GAME GARD</title>
<!--<body style="background-color: black;">-->

</head>


<body style="background-image:url('img-site/bg.png');">
    <header>
        <div class="logo">Game Card</div>
        <div class="inscription">
            <?php
            if(isset($_SESSION["userId"])){
                echo '<form action="extern/logout.ext.php" method="post">
                    <button type="submit" name="logout-submit" id="btn">LOG OUT</button>

                    </form>';
                echo '<p><a href="MESSAGES/login.msg.php" id="PLAY-btn">PLAY</a></p>';
                echo ' <p><a href="ACCOUNT/account.php" id="account-btn" style="margin-top:30px">ACCOUNT</a></p>';
                       
            }else{
                echo '<a href="ACCOUNT/signup.php"><li>SIGN UP</li></a>
                <a href="ACCOUNT/login.php"><li>LOG IN</li></a>';
            }
        ?>


        </div>



    </header>

    <div class="textgame">
       Venez tenter l'expérience !<br>
		Le meilleur jeu pour tester<br>
		vos connaissances.<br>
		Défiez et impressionnez <br>
		vos amis !

    </div>
    <div class="image">
        <img src="img-site/img.png">

    </div>


</body>


</html>
