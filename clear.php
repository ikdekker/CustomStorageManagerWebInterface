<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


if (isset($_POST['clear'])) {
    
    
$conn = mysqli_connect("127.0.0.1", "root", "digo_secret", "digo_parts_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE part_allocation SET sorted=1 where product_id=\""  . $_POST['part_id'] ."\" AND werkorder=\"" . $_POST['w_order'] . "\"";
$result = $conn->query($sql);
}
header('Location: index.php?orderId='. $_POST['w_order'] . "&deleted=" . $_POST['part_id']);