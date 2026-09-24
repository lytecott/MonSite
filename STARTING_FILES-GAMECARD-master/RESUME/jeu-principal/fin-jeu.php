<?php

session_start();



if(!isset($_SESSION["scoreFinal"]))
{
    header("Location: jeu-principal.php");
    exit();
}



$score=$_SESSION["scoreFinal"];

$erreurs=$_SESSION["erreursFinal"];

$theme=$_SESSION["themeFinal"];



unset($_SESSION["scoreFinal"]);

unset($_SESSION["erreursFinal"]);

unset($_SESSION["themeFinal"]);


?>


<!DOCTYPE html>

<html lang="fr">

<head>


<meta charset="UTF-8">

<title>Fin du jeu</title>



<style>


*{

box-sizing:border-box;

}



body{


margin:0;

height:100vh;

background:

linear-gradient(
135deg,
#000,
#222
);


display:flex;

justify-content:center;

align-items:center;


font-family:Arial;


color:white;


}





.card{


background:#1e1e1e;


width:500px;


max-width:90%;


padding:40px;


border-radius:30px;


text-align:center;


box-shadow:

0 0 40px black;


animation:zoom 0.5s;



}




@keyframes zoom{


from{

transform:scale(0);

}


to{

transform:scale(1);

}


}



h1{


font-size:45px;


color:#f1c40f;


}




.theme{


font-size:22px;

color:#3498db;

margin:20px;

text-transform:uppercase;


}





.score{


font-size:35px;


color:#2ecc71;


margin:20px;


}




.erreur{


font-size:28px;


color:#e74c3c;


}




a{


display:inline-block;


margin-top:30px;


padding:15px 40px;


background:#3498db;


color:white;


text-decoration:none;


border-radius:15px;


font-size:20px;


transition:.3s;


}




a:hover{


background:#2980b9;


transform:scale(1.1);


}



</style>



</head>


<body>



<div class="card">



<h1>
🎉 Bravo !
</h1>



<h2>
Partie terminée
</h2>



<div class="theme">

<?= $theme ?>

</div>




<div class="score">

⭐ <?= $score ?> points

</div>




<div class="erreur">

❌ <?= $erreurs ?> erreurs

</div>




<a href="jeu-principal.php">

Retour au menu

</a>




</div>



</body>


</html>