<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

?>


<!DOCTYPE html>
<html>


<head>
<title>Notetone</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<ul class="header">
    <a href="./dashboard.php" ><img src="logo-gray.png" class="header_logo"></a>

    <li class="li_header"><a class="a_header" href="./logout.php">Log out</a></li>
</ul>


<h1 class="h1">Products</h1>
<div class="div1">
    <img src="product1.png" class="product_image">
    <img src="product2.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product2.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product2.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product2.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product2.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
    <img src="product1.png" class="product_image">
</div>

<!--<ul class="footer">
    <li class="li_footer"><a target="_blank" class="a_footer" href="https://www.instagram.com/bigcarlj/"><img href="/" class="insta_icon" src="icon_instagram.png">@bigcarlj</a></li>
    <li class="li_footer"><a target="_blank" class="a_footer" href="mailto:sigats99@gmail.com"><img href="/" class="gmail_icon" src="icon_gmail.png">sigats99@gmail.com</a></li>
</ul>-->

</body>






</html>