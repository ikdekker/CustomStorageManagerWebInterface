<?php
$conn = mysqli_connect("127.0.0.1", "root", "digo_secret", "digo_parts_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="includes/css/bootstrap.min.css" rel="stylesheet">
        <script src="includes/js/jquery.js"></script>
    </head>
    <body>
        <div class="col-md-offset-3 col-md-6"><h3>
                <?php
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
            
            <div class="message" style="margin:24px">
            </div>
            <script>
//                $("#search").keyup(function () {
//                    var value = this.value.toLowerCase().trim();
//                    var found = 0
//                    $("#order-table tr").each(function (index) {
//                        $(this).find("td").each(function () {
//                            var id = $(this).text().toLowerCase().trim();
//                            var not_found = (id.indexOf(value) == -1);
//                            $(this).closest('tr').toggle(!not_found);
//                            if (not_found == false)
//                                found = 1;
//                            return not_found;
//                        });
//                    });
//                    
//                    if (!found) {
//                        $('.message').html("Geen producten gevonden.");
//                    } else {
//                        $('.message').html("");
//                    }
//                });
            </script>
            <?php
            $orderSet = isset($_GET['orderId']) && !empty($_GET['orderId']);
            if (!$orderSet) {
                echo "Scan een werkorder of een pakbon om te beginnen.</br></br>";
            }
            if (1) {
                $orderId = $_GET['orderId'];
                $sql = "SELECT * FROM part_allocation where sorted = 0";
                $result = $conn->query($sql);
                $del = isset($_GET['deleted']) && !empty($_GET['deleted']);
                if ($del) {
                    $del = $_GET['deleted'];

                    echo "<form method='post' action='undo.php'>";
                    echo "<div class='alert alert-info'>";
                    echo "order: $orderId product $del weggelegd<button type='submit' class='btn btn-xs btn-primary' style='margin:0;float:right'>Ongedaan maken</button><input type='hidden' name='del' value='" . $del . "'><input type='hidden' name='w_order' value='" . $orderId . "'>";
                    echo "</div>";
                    echo "</form>";
                }
            }

            if (1 && $result->num_rows > 0) :
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
                            <td id = "order-id"><input type="hidden" name="w_order" value="<?php echo $row['werkorder']; ?>"/><?php echo $row['werkorder']; ?></td>
                            <td id = "product-id"><input type="hidden" name="part_id" value="<?php echo $row['product_id']; ?>"/><?php echo $row['product_id']; ?></td>
                            <td id = "product-name"><?php echo $row['description']; ?></td>
                            <td id = "product-amount"><?php echo $row['amount']; ?></td>
                            <td style="padding:4px" id = "product-clear"><button type="submit" name="clear" class="btn btn-xs"><img width=24px height=24px alt="weggelegd" src='includes/images/check.png'/></button></td>
                        </form>
                        <td style="padding:4px" id="product-amount"><button type="submit" onclick="labelPrint(<?php echo $row['werkorder']; ?>)" class="btn btn-xs"><img width=24px height=24px alt="sticker printen" src='includes/images/print.png'/></button></td>
                        </form>
                            <!--<td style="padding:4px" id="product-amount"><button type="submit" class="btn btn-xs"><img width=24px height=24px alt="niet aanwezig" src='includes/images/missing.png'/></button></td>-->
                        </tr>
                    <?php endwhile;
                    ?>
                </table>
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
            <form method="post" action="stop.php"><button type="submit" name="clear" class="btn btn-sm btn-danger">STOP</button>
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
            dataType: "text",
            success: function (data) {
                if (data != 0 && data != curOrder) {
                    window.location.replace("http://" + location.hostname + "/index.php?orderId=" + data);
                }
            }
        });
    }
    function labelPrint(id) {
        $.ajax({
            type: "POST",
            url: "requestLabel.php",
            data: {"labelstring" : id}
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
                    
        if (!found) {
            $('.message').html("De ingescande order is niet gevonden.");
        } else {
            $('.message').html("");
        }
    }
</script>
<script src="includes/js/bootstrap.min.js"></script>
