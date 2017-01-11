<?php
$conn = mysqli_connect("127.0.0.1", "root", "digo_secret", "digo_parts_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

            $orderId = $_GET['orderId'];
            $sql = "SELECT parts.werkorder, info.*
FROM part_allocation parts, order_info info
WHERE parts.werkorder = info.werkorder
GROUP BY parts.werkorder
HAVING MAX(parts.sorted) = 0 AND MIN(parts.sorted) = 0";
            $result = $conn->query($sql);
            $sql = "SELECT parts.werkorder, info.*
FROM part_allocation parts, order_info info
WHERE parts.werkorder = info.werkorder
GROUP BY parts.werkorder
HAVING MAX(parts.sorted) = 1 AND MIN(parts.sorted) = 1";
            $resultSorted = $conn->query($sql);
?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="includes/css/bootstrap.min.css" rel="stylesheet">
        <script src="includes/js/jquery.js"></script>
    </head>
    <body>
            <table class = "table" id="order-table">
                <tr>
                    <th>order</th>
                    <th>kenteken</th>
                    <th>monteur</th>
                    <th>klaar</th>
                    <!--<th>afwezig</th>-->
                </tr>
                <?php
                if (1 && $resultSorted->num_rows > 0) :
                    ?>
                    <?php
                    // output data of each row
                    while ($row = $resultSorted->fetch_assoc()) :
                        ?>
                        <tr>
                            <td id = "order-id"><?php echo $row['werkorder']; ?></td>
                            <td id = "order-license"><?php echo $row['license']; ?></td>
                            <td id = "order-license"><?php echo $row['mechanic']; ?></td>
                            <td id = "order-rdy" style="padding:4px" ><img width=24px height=24px alt="ready"  src="includes/images/ready.png" /></td>
                        </tr>
                        <?php
                    endwhile;

                endif;

                if (1 && $result->num_rows > 0) :
                    // output data of each row
                    while ($row = $result->fetch_assoc()) :
                        ?>
                        <tr>
                            <td id = "order-id"><?php echo $row['werkorder']; ?></td>
                            <td id = "order-license"><?php echo $row['license']; ?></td>
                            <td id = "order-license"><?php echo $row['mechanic']; ?></td>
                            <td id = "order-not-rdy" class="col-sm-1" style="padding:4px" ><img width=24px height=24px alt="not ready"  src="includes/images/notready.png" /></td>
                        </tr>
                        <?php
                    endwhile;
                endif;
                ?>
            </table>
    </body>
</html>
<script>

    < script src = "includes/js/bootstrap.min.js" ></script>
