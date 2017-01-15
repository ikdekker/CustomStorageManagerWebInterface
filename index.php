<?php
$conn = mysqli_connect("127.0.0.1", "root", "digo_secret", "digo_parts_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$orderId = $_GET['orderId'];
if (!$orderId) {
    $orderId = $_GET["orderdel"];
}
$del = isset($_GET['deleted']) && !empty($_GET['deleted']);
?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="includes/css/bootstrap.min.css" rel="stylesheet">
        <script src="includes/js/jquery.js"></script>
        <link rel="shortcut icon" href="">
    </head>
    <style>
        * { font-size: 1.1em}

        .confirm-overlay {
            width:100%;
            height:100%;
            position:fixed;
            background: white;
            display:none;

        }
        .digo-overlay {
            width:100%;
            height:100%;
            position:fixed;
            background: white;

        }
        .confirm-dialog {
            /*display: none;*/
            width: 440px;
            height: 220px;
            border-radius: 8px;
            border: 1px solid black;
            background-color: #f5f5f5;

            position: absolute;
            top:0;
            bottom: 0;
            left: 0;
            right: 0;

            margin: auto;
            margin-top:120px;
        }
        .dialog-container {
            width: 100%;
            height:100%;
            overflow: hidden;
        }
        .dialog-container p {
            width: 100%;
            height:50%;
            padding:12px;
        }
        .dialog-container ul {
            width: 100%;
            height:50%;
            float:left;
            list-style-type: none;
            padding:0;
        }
        .dialog-container li {
            width: 50%;
            float:left;
            text-align: center;
            margin:0;
            padding:0;
            border-top:2px solid #ddd;
            vertical-align:middle;
        }
        .dialog-container ul li:before {
            content:'';
            height:90%;
            display:inline-block;
            margin-bottom:-90%;
            vertical-align: middle;
        }

        .dialog-container li a {
            width:100%;
            height: 100%;
            padding-top:12.5%;
            text-decoration: none;
            color:black;
            display:inline-block;
        }
        .dialog-container li:first-child {
            border-right: 2px solid #ddd;
        }
        .message {
            display:none;
        }
    </style>
    <body>
        <?php
        if (strpos($orderId, "digo") !== false) :
            $digoOrderId = substr($orderId, 4);
            ?>
            <div class="digo-overlay">
                <div>Werkorder <?php echo $digoOrderId; ?> ingescand. Kenteken: <?php
$sql = "SELECT license from order_info WHERE `werkorder`=$digoOrderId LIMIT 1";
$sqlLicense = mysqli_fetch_row($conn->query($sql));

    echo $sqlLicense[0]. "<br>";
                        $sql = "SELECT werkorder, description, amount
                    from part_allocation
            WHERE `werkorder`=$digoOrderId
            GROUP BY werkorder, description, amount
            HAVING MAX(sorted) = 1 AND MIN(sorted) = 1 AND MAX(big) = 1";
                        $resultBig = $conn->query($sql);
                    if (mysqli_num_rows($resultBig) == 0) :
                        ?>
                        Er zijn geen grote onderdelen 
                        <?php else :
                        ?>
                        Grote onderdelen aanwezig:
                        <br>
                        <?php
                        while ($row = $resultBig->fetch_assoc()) {
                            echo $row["description"] . " aantal: ";
                            echo $row["amount"];
                        }
                    endif;
                    ?>
                </div>
            </div>
            <?php
            exit;
        endif;
        ?>
        <div class="confirm-overlay">
            <?php
            if ($del && !$allBig) {
                $del = $_GET['deleted'];

                echo "<form method='post' action='undo.php'>";
                echo "<div class='alert alert-info' style='width:auto margin:20px'>";
                echo "order: $orderId product $del weggelegd<button type='submit' class='btn btn-sm btn-primary' style='margin:0;float:right'>Ongedaan maken</button><input type='hidden' name='del' value='" . $del . "'><input type='hidden' name='w_order' value='" . $orderId . "'>";
                echo "</div>";
                echo "</form>";
            }
            ?>
            <div class="confirm-dialog">
                <div class="dialog-container">
                    <p>Bak behouden</p>
                    <ul>
                        <li><a href="#1" onclick="alterIndex()">Nee</a></li>
                        <li><a href="#0" onclick="javacript:$('.confirm-overlay').hide()">Ja</a></li>
                    </ul>
                </div> <!-- cd-popup-container -->
            </div> <!-- cd-popup -->
        </div> <!-- cd-popup -->
        <div class="" style="margin:20px"><h3>
                <?php
                if (!empty($allBig = $_GET['allbig'])) {
                    if ($allBig) {
                        ?>
                        <script>
                            $('.confirm-overlay').show();
                        </script>
                        <?php
                    }
                }
                /* This sets the $time variable to the current hour in the 24 hour clock format */
                $time = date("H");
                /* Set the $timezone variable to become the current timezone */
                $timezone = date("e");
                /* If the time is less than 1200 hours, show good morning */
                if ($time < "12") {
                    echo "Goedemorgen";
                } else
                /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
                if ($time >= "12" && $time < "17") {
                    echo "Goedemiddag";
                } else
                /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
                if ($time >= "17") {
                    echo "Goedenavond";
                }
                ?>
            </h3>
            <br>
            <input class="form-control" style="width:120px;display:inline" id="search" type="hidden"/>

            <div class="message alert alert-success" style="margin:24px;margin-top:0">
            </div>
            <?php
            $orderSet = isset($_GET['orderId']) && !empty($_GET['orderId']);
            if (!$orderSet) {
                echo "Scan een werkorder of een pakbon om te beginnen.</br></br>";
            }

            $sql = "SELECT * FROM part_allocation where sorted = 0";
            $result = $conn->query($sql);
            if ($del && !$allBig) {
                $del = $_GET['deleted'];

                echo "<form method='post' action='undo.php'>";
                echo "<div class='alert alert-info'>";
                echo "order: $orderId product $del weggelegd<button type='submit' class='btn btn-xs btn-primary' style='margin:0;float:right'>Ongedaan maken</button><input type='hidden' name='del' value='" . $del . "'><input type='hidden' name='w_order' value='" . $orderId . "'>";
                echo "</div>";
                echo "</form>";
            }


            if ($orderId && $result->num_rows > 0) :
                ?>
                <table class = "table" id="order-table">
                    <tr>
                        <th>order</th>
                        <th>artikel</th>
                        <th>product</th>
                        <th>aantal</th>
                        <th>klaar</th>
                        <th>label</th>
                        <!--<th>afwezig</th>-->
                    </tr>
                    <?php
                    // output data of each row
                    while ($row = $result->fetch_assoc()) :
                        ?><tr>
                        <form method="post" action="clear.php">
                            <td class="col-sm-1" id = "order-id"><input type="hidden" name="w_order" value="<?php echo $row['werkorder']; ?>"/><?php echo $row['werkorder']; ?></td>
                            <td id = "product-id"><input type="hidden" name="part_id" value="<?php echo $row['product_id']; ?>"/><?php echo $row['product_id']; ?></td>
                            <td id = "product-name"><?php echo $row['description']; ?></td>
                            <td class="col-sm-1" id = "product-amount"><?php echo $row['amount']; ?></td>
                            <td class="col-sm-1" style="padding:4px" id = "product-clear"><button type="submit" name="clear" class="btn btn-xs"><img width=36px height=36px alt="weggelegd" src='includes/images/check.png'/></button></td>
                        </form>
                        <td class="col-sm-1" style="padding:4px" id="product-amount"><button type="submit" onclick="labelPrint('<?php echo $row['werkorder'] . "','" . $row['product_id']; ?>')" class="btn btn-xs"><img width=36px height=36px alt="sticker printen" src='includes/images/print.png'/></button></td>
                            <!--<td style="padding:4px" id="product-amount"><button type="submit" class="btn btn-xs"><img width=24px height=24px alt="niet aanwezig" src='includes/images/missing.png'/></button></td>-->
                        </tr>
    <?php endwhile;
    ?>
                </table>
                <form method="post" action="stop.php"><button type="submit" name="clear" class="btn btn-sm btn-danger">STOP</button>
                    <?php
                else :
                    if ($orderSet) {
                        echo "Geen producten gevonden<br><br>";
                    } else {
                        echo "Dit is de productpagina.</br>";

                        echo "Producten zullen hier verschijnen wanneer een order wordt ingescand.</br></br>";
                    }
                endif;
                ?>
        </div>
    </body>
</html>
<script>

<?php
$jsid = $orderId | 0;
echo "var curOrder = " . $jsid . ";"
?>


    function checkDBChanges() {
        $.ajax({
            type: "GET",
            url: "requestStatus.php",
            dataType: "json",
            success: function (data) {
                if (data.orderid != 0 && data.orderid != curOrder) {
                    window.location.replace("http://" + location.hostname + "/index.php?orderId=" + data.orderid);
                }
            }
        });
    }
    function labelPrint(id, productId) {
        $('.message').html("Bezig met printen").show();
        setTimeout(function () {
            $('.message').html("").hide()
        }, 8000);
        $.ajax({
            type: "POST",
            url: "requestLabel.php",
            data: {"label_id": id, "product_id": productId}
        });
    }
    function alterIndex() {
        $.ajax({
            type: "POST",
            url: "alterIndex.php",
            data: {"order_id": <?php
if (!$orderId)
    echo 0;
echo $orderId
?>}
        }).done(function () {
            window.location.replace('/');
        });
    }
    setInterval(checkDBChanges, 5000);
    if (curOrder !== 0) {
        $("#search").val(curOrder);
        search(curOrder);
    }
    function search(value) {
        var found = 0;
        $("#order-table tr").each(function (index) {
            $(this).find("#order-id").each(function () {
                var id = $(this).text().toLowerCase().trim();
                var not_found = (id.indexOf(value) == -1);
                $(this).closest('tr').toggle(!not_found);
                if (not_found == false) {
                    found = 1;
                }
            });
        });

        if (!found && curOrder != 0) {
            $('.message').html("Alle producten zijn weggelegd. U wordt verwezen naar de homepage over 5 seconden.").show();

            setTimeout(function () {
                window.location.replace("stop.php");
            }, 5000);
        } else {
            $('.message').html("").hide();
        }
    }
</script>
<script src="includes/js/bootstrap.min.js"></script>
