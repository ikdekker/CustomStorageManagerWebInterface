<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


if (isset($_POST['switch'])) {

    $conn = mysqli_connect("127.0.0.1", "root", "digo_secret", "digo_parts_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $orderOneId = $_POST['order_one_id']; //1311
    $orderOneIndex = $_POST['order_one_index']; //v3
    $orderTwoId = $_POST['order_two_id']; //0
    $orderTwoIndex = $_POST['order_two_index']; //0
    
    if ($orderOneId != 0) {
            $sql = "update order_indexing set `index`=$orderTwoIndex where werkorder='" . $orderOneId . "'";
            echo $conn->query($sql);
    }

    if ($orderTwoId != 0) {
            $sql = "update order_indexing set `index`=$orderOneIndex where werkorder='" . $orderTwoId . "'";
            $conn->query($sql);
    }
}