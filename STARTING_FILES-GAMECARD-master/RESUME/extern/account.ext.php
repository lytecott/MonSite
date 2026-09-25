<?php
session_start();

if (!isset($_SESSION["userId"])) {
    header("location:../ACCOUNT/account.php?status=session_expired");
    exit();
}

if (isset($_POST["validate-submit"])) {
    require 'dbh.ext.php';
    $id = $_SESSION["userId"];
    $name = trim($_POST["prenom"] ?? '');
    $username = trim($_POST["username"] ?? '');

    // Vérification des champs vides
    if (empty($name) && empty($username)) {
        header("location:../ACCOUNT/account.php?status=empty_fields");
        exit();
    }

    // Validation du format du username (uniquement alphanumérique)
    if (!empty($username) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("location:../ACCOUNT/account.php?status=invalid_username");
        exit();
    }

    // Connexion à la base de données
    if (!$conn) {
        header("location:../ACCOUNT/account.php?status=db_connection_error");
        exit();
    }

    // Cas 1 : Seul le username est fourni
    if (empty($name)) {
        // Vérifier si le username existe déjà
        $sql = "SELECT username FROM gamecard WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            header("location:../ACCOUNT/account.php?status=username_taken");
            exit();
        }

        // Mettre à jour le username
        $sql = "UPDATE gamecard SET username = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $username, $id);

        if (!mysqli_stmt_execute($stmt)) {
            header("location:../ACCOUNT/account.php?status=sql_error");
            exit();
        }

        header("location:../ACCOUNT/account.php?status=success");
        exit();
    }
    // Cas 2 : Seul le nom est fourni
    else if (empty($username)) {
        $sql = "UPDATE gamecard SET name = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $name, $id);

        if (!mysqli_stmt_execute($stmt)) {
            header("location:../ACCOUNT/account.php?status=sql_error");
            exit();
        }

        header("location:../ACCOUNT/account.php?status=success");
        exit();
    }
    // Cas 3 : Les deux champs sont fournis
    else {
        // Vérifier si le username existe déjà
        $sql = "SELECT username FROM gamecard WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            header("location:../ACCOUNT/account.php?status=username_taken");
            exit();
        }

        // Mettre à jour les deux champs
        $sql = "UPDATE gamecard SET name = ?, username = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $name, $username, $id);

        if (!mysqli_stmt_execute($stmt)) {
            header("location:../ACCOUNT/account.php?status=sql_error");
            exit();
        }

        header("location:../ACCOUNT/account.php?status=success");
        exit();
    }

    mysqli_close($conn);
} else {
    header("location:../ACCOUNT/account.php");
    exit();
}