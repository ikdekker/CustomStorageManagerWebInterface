<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$extraArgs = "";
$all = true;
if (isset($_POST['delete'])) {

    $conn = mysqli_connect("127.0.0.1", "root", "digo_secret", "digo_parts_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $order = $_POST['w_order'];
    
    $sql = "DELETE IGNORE from ghs_orders where digo_id='" . $order . "'";
    $conn->query($sql);
    $sql = "DELETE IGNORE from order_info where werkorder='" . $order . "'";
    $conn->query($sql);
    $sql = "DELETE IGNORE from part_allocation where werkorder='" . $order . "'";
    $conn->query($sql);
    $sql = "DELETE IGNORE from order_indexing where werkorder='" . $order . "'";
    $conn->query($sql);

    
    header('Location: admin.php?deleted=' . $_POST['w_order']);
}