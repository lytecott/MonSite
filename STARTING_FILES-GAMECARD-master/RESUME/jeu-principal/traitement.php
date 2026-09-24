<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors',1);

require '../extern/dbh.ext.php';



// Vérification formulaire

if(
    !isset($_POST["theme"]) ||
    !isset($_POST["id"]) ||
    !isset($_POST["reponse"])
)
{
    header("Location: cardRep.php?error=missing");
    exit();
}



$theme = $_POST["theme"];

$idQuestion = intval($_POST["id"]);

$reponseUtilisateur = trim($_POST["reponse"]);


$userId = $_SESSION["userId"] ?? 0;



if($userId == 0)
{
    header("Location: ../login.php");
    exit();
}



// ===========================
// Récupérer bonne réponse
// ===========================


$sql="
SELECT reponse
FROM `$theme`
WHERE id=?
";


$stmt=mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $idQuestion
);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);


$data=mysqli_fetch_assoc($result);



$bonneReponse = strtolower(
    trim($data["reponse"])
);



$reponseUtilisateur = strtolower(
    trim($reponseUtilisateur)
);



// ===========================
// Vérification réponse
// ===========================


if($reponseUtilisateur == $bonneReponse)
{
    $point = 1;
}
else
{
    $point = 0;
}





// ===========================
// Récupérer partie active
// ===========================


$sql="
SELECT *
FROM jeuxActive
WHERE Id=?
AND theme=?
";


$stmt=mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(
    $stmt,
    "is",
    $userId,
    $theme
);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);


$jeu=mysqli_fetch_assoc($result);



if(!$jeu)
{
    die("Partie inexistante");
}





// ===========================
// Retirer question actuelle
// ===========================


$questions = explode(
    "/",
    $jeu["ordreActuel"]
);



$key=array_search(
    $idQuestion,
    $questions
);



if($key !== false)
{
    unset($questions[$key]);
}



$nouvelOrdre = implode(
    "/",
    $questions
);




// ===========================
// Mise à jour score
// ===========================


$nouveauScore =
$jeu["currentScore"] + $point;



$erreurs =
$jeu["nbrErreur"];



if($point == 0)
{
    $erreurs++;
}







// ===========================
// FIN DU JEU
// ===========================


if(empty($nouvelOrdre))
{


    $_SESSION["scoreFinal"] =
    $nouveauScore;


    $_SESSION["erreursFinal"] =
    $erreurs;


    $_SESSION["themeFinal"] =
    $theme;



    mysqli_query(
        $conn,
        "
        DELETE FROM jeuxActive
        WHERE Id=$userId
        AND theme='$theme'
        "
    );



    header(
        "Location: fin-jeu.php"
    );


    exit();

}






// ===========================
// Continuer le jeu
// ===========================



$sql="
UPDATE jeuxActive

SET

ordreActuel=?,

currentScore=?,

nbrErreur=?

WHERE Id=?

AND theme=?

";



$stmt=mysqli_prepare($conn,$sql);



mysqli_stmt_bind_param(

$stmt,

"siiss",

$nouvelOrdre,

$nouveauScore,

$erreurs,

$userId,

$theme

);



mysqli_stmt_execute($stmt);




header(
"Location: cardRep.php?theme=".$theme
);


exit();


?>