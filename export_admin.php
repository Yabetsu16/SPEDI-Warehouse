<?php require "config/connectdb.php"; ?>

<?php
$filename = "Admin Inventory Summary Report " . date("d-M-Y");  //your_file_name
$file_ending = ".xls";   //file_extention

$query = "SELECT * FROM movement_tb 
INNER JOIN inventory_tb ON inventory_tb.inventory_id = movement_tb.inventory_id
INNER JOIN project_tb ON project_tb.project_id = inventory_tb.project_id
WHERE inventory_tb.item_type = 'Admin'
ORDER BY inventory_tb.inventory_id;";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $output = '
        <table class="table" bordered="1">  
       <tr>  
            <th >Timestamp
            </th>
            <th >Project Name
            </th>
            <th >Item Type
            </th>
            <th >Item Name
            </th>
            <th >Unit
            </th>
            <th >Quantity
            </th>
            <th >Issued
            </th>
            <th >Returned
            </th>
            <th >Balance
            </th>
       </tr>
';
    while ($row = mysqli_fetch_array($result)) {
        $project_name = $row['project_name'];
        $item_type = $row['item_type'];
        $item_name = $row['item_name'];
        $unit = $row['unit'];
        $quantity = $row['quantity'];
        $issued = $row['issued'];
        $returned = $row['returned'];
        $balance = $row['balance'];
        $date_movement = $row['date_movement'];

        $output .= '
        <tr>
                                <td> ' . $date_movement . '</td>
                                <td> ' . $project_name . '</td>
                                <td> ' . $item_type . '</td>
                                <td> ' . $item_name . '</td>
                                <td> ' . $unit . '</td>
                                <td> ' . $quantity . '</td>
                                <td> ' . $issued . '</td>
                                <td> ' . $returned . '</td>
                                <td> ' . $balance . '</td>
                            </tr>
';
    }
    $output .= '</table>';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename=' . $filename . $file_ending);
    echo $output;
}
else {
    echo "No data";
}
?>