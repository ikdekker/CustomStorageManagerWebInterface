<?php
$conn = mysqli_connect("127.0.0.1", "root", "digo_secret", "digo_parts_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<html style="display:none">
    <head>
        <link href="includes/css/bootstrap.min.css" rel="stylesheet">
        <script src="includes/js/jquery.js"></script>
        <script src="includes/js/bootstrap.min.js"></script>
    </head>
    <style>
        .kentekenleft {
            background: blue;
            float:left;
            width:12px;
            color:white;
            height:100%;
            font-size:8px;
            padding-top: 8px;
        }
        .kenteken {
            background:yellow;
            border: 2px solid black;
            height:26px;
            font-weight: 900;
            border-radius:3px
        }
        .kentekenright {
            height:100%;
            font-weight: 900;
            padding:2px;
        }
        .bak {
            float: left; 
            width: 80px; 
            height: 44px; 
            border: 1px solid black; 
            text-align:center;
            background: #ddd;
        }
        .bakvol{
        }
        .bakleeg{
        }
    </style>
    <div id="content" style="width: 100%;">
        <div style="float:left;height: 45px;padding-top:10px;padding-left:10px;border-bottom: 1px solid black; width:100%;">
            <button class="btn btn-primary" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0"  onclick="showDelete()">delete</button> 
            <button class="btn btn-primary" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0" onclick="showMove()">move</button>
        </div>
        <div id="delete-content" style="float:left;display:none; width: 100%;" >
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
                        <form onsubmit="return validate(this)" id="form-<?php echo $row['werkorder'] ?>" method="post" action="delete.php">
                            <td class="col-sm-1" id = "order-id"><input type="hidden" name="w_order" value="<?php echo $row['werkorder']; ?>"/><?php echo $row['werkorder']; ?></td>
                            <td class="col-sm-1" id = "license"><?php echo $row['license']; ?></td>
                            <td class="col-sm-1" style="padding:4px" id = "product-clear"><button type="submit" name="delete" class="btn btn-xs"><img width=24px height=24px alt="weggelegd" src='includes/images/bin.png'/></button></td>
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
        <div id="move-content" style="display:none;padding:24px;padding-top: 100px;text-align: center">
            <?php
            $sql = "SELECT o.werkorder,o.index, i.license FROM order_indexing o inner join order_info i on i.werkorder = o.werkorder where module_id = 0 ORDER BY o.index ASC";

            $result = $conn->query($sql);
            if ($result->num_rows > 0) :
                ?>
                <div id="order-grid" style="display: inline-block;min-width: 504px;">
                    <?php
                    // output data of each row
                    $i = 0;
                    $cols = 10;
                    $rows = 5;
                    $rowData = [];
                    while ($row = $result->fetch_assoc()) {
                        $rowData[(int) $row['index']] = $row;
                    }

                    while ($i < $rows * $cols) {
                        $iR = $i;
                        $halve = ceil($cols / 2) - 1;

                        if ($i % $cols <= $halve) { //0 = 4 - 0;
                            $iR = $halve - $i % $cols + (floor($i / $cols) * $cols);
                        }
                        if (array_key_exists((int) $iR, $rowData) === true) {
                            $rowDataRow = $rowData[$iR];
                            $data[$i]['ref'] = $rowDataRow["license"];
                            $data[$i]['id'] = $rowDataRow["werkorder"];
                            $data[$i]['index'] = $rowDataRow["index"];
                            $data[$i]['full'] = 1;
                        } else {
                            $data[$i]['ref'] = ".";
                            $data[$i]['index'] = $iR;
                            $data[$i]['id'] = 0;
                            $data[$i]['full'] = 0;
                        }
                        $i++;
                    }
                    foreach ($data as $key => $ref) :
                        ?>
                        <div unselectable="on" id="div<?php echo $key; ?>" draggable="true"
                             ondragstart="drag(event)" ondrop="drop(event)" 
                             data-w-id="<?php echo $ref['id']; ?>" 
                             data-index="<?php echo $ref['index']; ?>" 
                             ondragover="allowDrop(event)" 
                             class="bak <?php if ($ref['full'] == 1) { echo "bakvol"; } else {echo "bakleeg";    };?>">
                            <label class="kenteken"><label class="kentekenleft">nl</label><label class="kentekenright"><?php echo $ref["ref"]; ?></label></label>
                        </div>
                        <?php
                        $i++;
                        if ($i % ($cols / 2) == 0 && !($i % $cols == 0)) {
                            echo "<div style='float:left;margin-left:10px'>&emsp;</div>";
                        } elseif ($i % $cols !== 0) {
                            echo "<div style='float:left;'>-</div>";   
                        }
                        if ($i % $cols == 0) {
                            echo "<br><br>";
                            echo "<br><br>";
                        }
                    endforeach;
                    ?>
                </div>
                <?php
            else :
                if ($orderSet) {
                    echo "Er staan geen orders in het systeem.</br>";
                }
            endif;
            ?>    
        </div>

    </div>
</html>

<script>
    pass = prompt("Voer het password in:");
//pass='digo21';
    if (pass == 'abc123') {
        $("html").show();
        $("#delete-content").show();
    } else {
        $('html').html("");
    }

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function showMove() {
        $("#move-content").show();
        $("#delete-content").hide();
    }

    function showDelete() {
        $("#move-content").hide();
        $("#delete-content").show();
    }

    function drag(ev) {
        console.log(ev.dataTransfer.items.length);
        if (ev.dataTransfer.items.length > 1) return; 
        while ($(ev.target).prop("tagName") !== "DIV") 
            ev.target = $(ev.target).parent();
        ev.dataTransfer.setData("id", ev.target.id);
        ev.dataTransfer.setData("text", $(ev.target).html());
        ev.dataTransfer.setData("workid", ev.target.getAttribute("data-w-id"));
        ev.dataTransfer.setData("index", ev.target.getAttribute("data-index"));
    }

    function drop(ev) {
        if (ev.dataTransfer.items.length != 4) return; 
        ev.preventDefault();
        var id = ev.dataTransfer.getData("id");
        var data = ev.dataTransfer.getData("text");
        var wId = ev.dataTransfer.getData("workid");
        var index = ev.dataTransfer.getData("index");
        targ = ev.target;
        while ($(targ).prop("tagName") !== "DIV") { 
            targ = $(targ).parent();
        }
        prevTxt = $(targ).html();
        prevId = $(targ).attr('data-w-id');
        if (prevId == wId) return;
        prevIndex = $(targ).attr('data-index');
        if (data == "undefined" || prevTxt == "undefined")
            return;
        $("#" + id).html(prevTxt);
        $("#" + id).attr('data-w-id', prevId);

        $(targ).html(data);
        $(targ).attr('data-w-id', wId);

        console.log({order_one_id: wId, order_one_index: index, order_two_id: prevId, order_two_index: prevIndex});
        $.ajax({
            url: "switchorders.php",
            type: "POST",
            data: {switch : true, order_one_id: wId, order_one_index: index, order_two_id: prevId, order_two_index: prevIndex}
        }).done(function (data) {
            console.log(data);
            console.log("done");
        }).fail(function () {
            alert("Switch failed");
        });
    }
    function validate(form) {
        console.log($(form.id));
        orderid = $(form.id).find("#order-id").html();
        kenteken = $(form.id).find("#license").html();
        return confirm('Order: ' + orderid + 'met kenteken ' + kenteken + '?');
    }
</script>
