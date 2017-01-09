<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$servername = "127.0.0.1";
$username = "root";
$password = "digo_secret";
$dbname = "digo_parts_db";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$stmt = $conn->prepare('Update system_status set orderid=:lstr where placeholder=0');
$stmt->execute(['lstr' => $lString]);

   
header('Location: index.php');