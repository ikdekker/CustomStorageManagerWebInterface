<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$orderId = $_POST['order_id'];

$servername = "127.0.0.1";
$username = "root";
$password = "digo_secret";
$dbname = "digo_parts_db";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$stmt = $conn->prepare('Update order_indexing set `index`=-1 where werkorder=:lstr');
$exe = $stmt->execute(['lstr' => $orderId]);