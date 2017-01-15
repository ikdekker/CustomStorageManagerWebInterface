<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    
$conn = mysqli_connect("127.0.0.1", "root", "digo_secret", "digo_parts_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT orderid FROM system_status";
$result = $conn->query($sql);
$res = [];
foreach ($result as $row) {
  $res[$row['placeholder']]['orderid'] = [$res['orderid']];
}
echo json_encode($row);