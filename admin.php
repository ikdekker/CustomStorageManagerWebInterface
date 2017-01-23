<?php
$conn = mysqli_connect("127.0.0.1", "root", "digo_secret", "digo_parts_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<html>
    <head>
        <link href="includes/css/bootstrap.min.css" rel="stylesheet">
        <script src="includes/js/jquery.js"></script>
        <script src="includes/js/bootstrap.min.js"></script>
    </head>
    <div id="content">
    <script>
        pass = prompt("password:");
        if (pass != "abc123") {
            $("#content").hide();
        }
    </script>
        <?php
        $sql = "SELECT o.*, i.license FROM order_indexing o inner join order_info i on i.werkorder = o.werkorder where o.werkorder >=  0";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) :
            ?>
            <table class = "table" id="order-table">
                <tr>
                    <th>orderid</th>
                    <th>kenteken</th>
                    <th>delete</th> 
                </tr>
                <?php
                // output data of each row
                while ($row = $result->fetch_assoc()) :
                    ?><tr>
                    <form method="post" action="delete.php">
                        <td class="col-sm-1" id = "order-id"><input type="hidden" name="w_order" value="<?php echo $row['werkorder']; ?>"/><?php echo $row['werkorder']; ?></td>
                        <td class="col-sm-1" id = "license"><?php echo $row['license']; ?></td>
                        <td class="col-sm-1" style="padding:4px" id = "product-clear"><button type="submit" name="delete" class="btn btn-xs"><img width=36px height=36px alt="weggelegd" src='includes/images/bin.png'/></button></td>
                    </form>
                <?php endwhile;
                ?>
            </table>
            <?php
        else :
            if ($orderSet) {
                echo "Er staan geen orders in het systeem.</br>";
            }
        endif;
        ?>    
    </div>
</html>