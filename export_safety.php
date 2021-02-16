<?php require "config/connectdb.php"; ?>

<?php
$project_id = $_GET['p'];

if ($project_id != "All") {
    $query_project_office = "SELECT * FROM project_office_tb WHERE project_id = $project_id";
    $result_project_office = mysqli_query($conn, $query_project_office);
    while ($row_project_office = mysqli_fetch_array($result_project_office)) {
        $project_office_name = $row_project_office['project_office_name'];
    }
    $filename = "Safety Inventory Summary Report for " . $project_office_name . " " . date("M-d-Y");  //your_file_name
    $file_format = ".csv";   //file_extention

    $query = "SELECT
    project_office_tb.project_office_name AS 'Project / Office',
    inventory_tb.item_name AS 'Item Name',
    inventory_tb.unit AS 'Unit',
    inventory_tb.unit_cost AS 'Cost',
    movement_tb.quantity AS 'Quantity',
    movement_tb.date_added AS 'Date Added',
    movement_tb.issued AS 'Issued',
    movement_tb.to_project_office AS 'Issued To',
    movement_tb.date_issued AS 'Date Issued',
    movement_tb.returned AS 'Returned',
    movement_tb.date_returned AS 'Date Returned',
    movement_tb.balance AS 'Balance'
    FROM movement_tb 
    INNER JOIN inventory_tb ON inventory_tb.inventory_id = movement_tb.inventory_id
    INNER JOIN project_office_tb ON project_office_tb.project_id = inventory_tb.project_id
    WHERE inventory_tb.item_type = 'Safety' AND project_office_tb.project_id = $project_id
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
}

if ($project_id == "All") {
    $filename = "Safety Inventory Summary Report for all Projects and Offices " . date("M-d-Y");  //your_file_name
    $file_format = ".csv";   //file_extention

    $query = "SELECT
    project_office_tb.project_office_name AS 'Project / Office',
    inventory_tb.item_name AS 'Item Name',
    inventory_tb.unit AS 'Unit',
    inventory_tb.unit_cost AS 'Cost',
    movement_tb.quantity AS 'Quantity',
    movement_tb.date_added AS 'Date Added',
    movement_tb.issued AS 'Issued',
    movement_tb.to_project_office AS 'Issued To',
    movement_tb.date_issued AS 'Date Issued',
    movement_tb.returned AS 'Returned',
    movement_tb.date_returned AS 'Date Returned',
    movement_tb.balance AS 'Balance'
    FROM movement_tb 
    INNER JOIN inventory_tb ON inventory_tb.inventory_id = movement_tb.inventory_id
    INNER JOIN project_office_tb ON project_office_tb.project_id = inventory_tb.project_id
    WHERE inventory_tb.item_type = 'Safety'
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
}

function mysqli_field_name($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}
?>