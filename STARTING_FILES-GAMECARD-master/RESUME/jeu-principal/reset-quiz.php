<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors',1);


require '../extern/dbh.ext.php';



// Vérifier utilisateur connecté

if(!isset($_SESSION["userId"]))
{
    header("Location: ../login.php");
    exit();
}



$userId=$_SESSION["userId"];




// Supprimer toutes les parties actives de cet utilisateur


$sql="
DELETE FROM jeuxActive
WHERE Id=?
";



$stmt=mysqli_prepare($conn,$sql);



mysqli_stmt_bind_param(
    $stmt,
    "i",
    $userId
);



if(mysqli_stmt_execute($stmt))
{

    header(
        "Location: jeu-principal.php?reset=success"
    );

    exit();

}

else
{

    echo "Erreur RESET : "
    .mysqli_error($conn);

}



?>