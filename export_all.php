<?php require "config/connectdb.php"; ?>

<?php
$filename = "All Inventory Summary Report " . date("M-d-Y");  //your_file_name
$file_format = ".csv";   //file_extention

$query = "SELECT
project_tb.project_name AS 'Project Name',
inventory_tb.item_type AS 'Item Type',
inventory_tb.item_name AS 'Item Name',
inventory_tb.unit AS 'Unit',
inventory_tb.unit_cost AS 'Cost',
movement_tb.quantity AS 'Quantity',
movement_tb.date_added AS 'Date Added',
movement_tb.issued AS 'Issued',
movement_tb.date_issued AS 'Date Issued',
movement_tb.returned AS 'Returned',
movement_tb.date_returned AS 'Date Returned',
movement_tb.balance AS 'Balance'
FROM movement_tb 
INNER JOIN inventory_tb ON inventory_tb.inventory_id = movement_tb.inventory_id
INNER JOIN project_tb ON project_tb.project_id = inventory_tb.project_id
ORDER BY inventory_tb.inventory_id, movement_tb.date_movement;";

$result = mysqli_query($conn, $query);
$number_of_fields = mysqli_num_fields($result);
$headers = array();
for ($i = 0; $i < $number_of_fields; $i++) {
    $headers[] = mysqli_field_name($result, $i);
}
$fp = fopen('php://output', 'w');
if ($fp && $result) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=' . $filename . $file_format);
    header('Pragma: no-cache');
    header('Expires: 0');
    fputcsv($fp, $headers);

    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        fputcsv($fp, array_values($row));
    }
    die;
}

function mysqli_field_name($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}
?>