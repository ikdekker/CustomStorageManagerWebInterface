<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$extraArgs = "";
$all = true;
if (isset($_POST['clear'])) {

    $conn = mysqli_connect("127.0.0.1", "root", "digo_secret", "digo_parts_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $order = $_POST['w_order'];
    $sql = "UPDATE part_allocation SET sorted=1 where product_id=\"" . $_POST['part_id'] . "\" AND werkorder=\"" . $order . "\"";
    $result = $conn->query($sql);

    $sql = "SELECT werkorder
        from part_allocation
WHERE werkorder=$order
GROUP BY werkorder
HAVING MAX(sorted) = 1 AND MIN(sorted) = 1 AND MAX(big) = 1 AND MIN(big) = 1";
    $resultBig = $conn->query($sql);
    $sql = "SELECT werkorder
        from part_allocation
WHERE werkorder=$order
GROUP BY werkorder
HAVING MAX(sorted) = 1 AND MIN(sorted) = 1";
    $resultAll = $conn->query($sql);
    $all = mysqli_num_rows($resultAll);
    if ($all) {
        $extraArgs .= "&orderdel=" . $order;
        $sql = "UPDATE system_status set orderid=0 where placeholder=0";
        $conn->query($sql);
    }
    if (mysqli_num_rows($resultBig)) {
        $extraArgs .= "&allbig=1";
    }
}
if (!$all) {
    header('Location: index.php?orderId=' . $order . "&deleted=" . $_POST['part_id'] . $extraArgs);
} else {
    header('Location: index.php?deleted=' . $_POST['part_id'] . $extraArgs);
}
