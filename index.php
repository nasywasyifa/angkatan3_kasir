<?php
session_start();
require_once "config/koneksi.php";
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $selectLogin = mysqli_query($koneksi, "SELECT * FROM user WHERE email =  '$email'");
    if (mysqli_num_rows($selectLogin) > 0) {
        $row =  mysqli_fetch_assoc($selectLogin);

        if ($row['email'] == $email && $row['password'] == $pass) {
            $_SESSION['ID']             = $row['id'];
            $_SESSION['EMAILNYABRO']    = $row['email'];
            $_SESSION['NAMALENGKAPNYA'] =  $row['nama_lengkap'];
            header("Location: kasir.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width 
    <title>eLEARNING - eLearning HTML Template</title>
    <meta content=" width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">

</head>

<body>

    <div class="container justify-content-center">
        <div class="row">
            <div class="col-6">
                <div class="card-header text-center">
                    <h1>Login</h1>
                </div>
                <div class="card-body">

                    <form action="" method="POST">
                        <div class="mt-2">
                            <label class="form-label" for="">Email</label>
                            <input class="form-control" type="email" name="email" placeholder="Isi Email Anda" required>
                        </div>
                        <div class="mt-2">
                            <label class="form-label" for="">Password</label>
                            <input class="form-control" type="password" name="password" placeholder="Isi Password Anda" required>
                        </div>
                        <button class="btn btn-primary" type="submit" name="login">Login</button>

                </div>
            </div>
        </div>
    </div>

    </div>

    <link rel="stylesheet" href="bootstrap/dist/js/bootstrap.bundle.min.js">