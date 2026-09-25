<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require '../extern/dbh.ext.php';

if (!isset($_SESSION["userId"])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION["userId"];
$theme = $_GET["theme"] ?? "";

$themes = [
    "histoire",
    "sport",
    "culture",
    "sciences"
];

if (!in_array($theme, $themes)) {
    die("Thème incorrect");
}

/* =========================
   Vérifier partie existante
========================= */

$sql = "
SELECT *
FROM jeuxActive
WHERE Id=?
AND theme=?
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "is", $userId, $theme);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    $sql = "
    SELECT COUNT(*) AS total
    FROM `$theme`
    ";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    $total = $data["total"];

    $ordre = [];
    for ($i = 1; $i <= $total; $i++) {
        $ordre[] = $i;
    }
    shuffle($ordre);
    $ordreActuel = implode("/", $ordre);
    $vide = "";

    $sql = "
    INSERT INTO jeuxActive
    (Id, ordreActuel, ordreSuivant, theme, nbrErreur, currentScore)
    VALUES(?, ?, ?, ?, 0, 0)
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isss", $userId, $ordreActuel, $vide, $theme);
    mysqli_stmt_execute($stmt);
}

/* =========================
   Récupération partie
========================= */

$sql = "
SELECT ordreActuel, currentScore, nbrErreur
FROM jeuxActive
WHERE Id=?
AND theme=?
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "is", $userId, $theme);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$jeu = mysqli_fetch_assoc($result);

$ordre = $jeu["ordreActuel"];
$score = $jeu["currentScore"];
$erreurs = $jeu["nbrErreur"];

/* Vérifications pour éviter les erreurs */
if (empty($ordre)) {
    die("Aucune question disponible dans l'ordre actuel.");
}
$liste = explode("/", $ordre);
if (empty($liste)) {
    die("La liste des questions est vide.");
}
$idQuestion = $liste[0];

/* =========================
   Progression
========================= */

// Récupère le total des questions depuis la base de données
$sqlTotal = "SELECT COUNT(*) AS total FROM `$theme`";
$resultTotal = mysqli_query($conn, $sqlTotal);
$dataTotal = mysqli_fetch_assoc($resultTotal);
$totalQuestions = $dataTotal["total"]; // Total des questions dans le thème

$questionNumero = $score + $erreurs + 1;
$progression = ($questionNumero / $totalQuestions) * 100;

/* =========================
   Question
========================= */

$sql = "
SELECT question, type
FROM `$theme`
WHERE id=?
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $idQuestion);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$q = mysqli_fetch_assoc($result);

if (!$q) {
    die("Aucune question trouvée pour l'ID : $idQuestion");
}

$question = $q["question"];
$type = $q["type"];

/* =========================
   AFFICHAGE QCM
========================= */

if ($type == "qcm") {
    $sql = "
    SELECT proposition1, proposition2, proposition3
    FROM propositions
    WHERE questionId=?
    AND theme=?
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $idQuestion, $theme);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $p = mysqli_fetch_assoc($result);

    if (!$p) {
        die("Aucune proposition trouvée pour la question ID : $idQuestion et thème : $theme");
    }

    afficherQCM(
        $question,
        $p["proposition1"],
        $p["proposition2"],
        $p["proposition3"],
        $theme,
        $idQuestion,
        $questionNumero,
        $totalQuestions,
        $progression,
        $score,
        $erreurs
    );
} else {
    afficherTexte(
        $question,
        $theme,
        $idQuestion,
        $questionNumero,
        $totalQuestions,
        $progression,
        $score,
        $erreurs
    );
}

function afficherQCM(
    $question,
    $p1,
    $p2,
    $p3,
    $theme,
    $id,
    $num,
    $total,
    $progression,
    $score,
    $erreurs
) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Quiz</title>
        <style>
            body {
                background: #111;
                color: white;
                font-family: Arial;
            }
            .card {
                background: #222;
                width: 500px;
                max-width: 90%;
                margin: 40px auto;
                padding: 30px;
                border-radius: 20px;
                text-align: center;
            }
            .progress {
                height: 20px;
                background: #555;
                border-radius: 20px;
                overflow: hidden;
            }
            .bar {
                height: 100%;
                background: #27ae60;
            }
            button {
                padding: 12px 30px;
                background: #3498db;
                color: white;
                border: 0;
                border-radius: 10px;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <h3>Question <?= $num ?> / <?= $total ?></h3>
            <div class="progress">
                <div class="bar" style="width: <?= $progression ?>%"></div>
            </div>
            <h4>⭐ Score : <?= $score ?></h4>
            <h4>❌ Erreurs : <?= $erreurs ?></h4>
            <h2><?= $question ?></h2>
            <form action="traitement.php" method="post">
                <input type="hidden" name="theme" value="<?= $theme ?>">
                <input type="hidden" name="id" value="<?= $id ?>">
                <p>
                    <input type="radio" name="reponse" value="<?= $p1 ?>" required>
                    <?= $p1 ?>
                </p>
                <p>
                    <input type="radio" name="reponse" value="<?= $p2 ?>">
                    <?= $p2 ?>
                </p>
                <p>
                    <input type="radio" name="reponse" value="<?= $p3 ?>">
                    <?= $p3 ?>
                </p>
                <button>Valider</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}

function afficherTexte(
    $question,
    $theme,
    $id,
    $num,
    $total,
    $progression,
    $score,
    $erreurs
) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Quiz</title>
        <style>
            body {
                background: #111;
                color: white;
                font-family: Arial;
            }
            .card {
                background: #222;
                width: 500px;
                max-width: 90%;
                margin: 40px auto;
                padding: 30px;
                border-radius: 20px;
                text-align: center;
            }
            .progress {
                height: 20px;
                background: #555;
                border-radius: 20px;
            }
            .bar {
                height: 100%;
                background: #27ae60;
            }
            textarea {
                width: 90%;
                height: 100px;
            }
            button {
                padding: 12px 30px;
                background: #3498db;
                color: white;
                border: 0;
                border-radius: 10px;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <h3>Question <?= $num ?> / <?= $total ?></h3>
            <div class="progress">
                <div class="bar" style="width: <?= $progression ?>%"></div>
            </div>
            <h4>⭐ Score : <?= $score ?></h4>
            <h4>❌ Erreurs : <?= $erreurs ?></h4>
            <h2><?= $question ?></h2>
            <form action="traitement.php" method="post">
                <input type="hidden" name="theme" value="<?= $theme ?>">
                <input type="hidden" name="id" value="<?= $id ?>">
                <textarea name="reponse" required></textarea>
                <br><br>
                <button>Valider</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>