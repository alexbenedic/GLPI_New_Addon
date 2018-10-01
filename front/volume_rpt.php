<?php
$id = $_POST["id"];

include 'report_config.php';
$sql = "SELECT name
FROM `glpi_computers`
WHERE `id` =".$id;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $name = "Volume_Details_".$row["name"];
    }
} else {
     echo "Invalid Device Id(Name)!!";

}

$filename = $name.".csv";

$sql = " SELECT `glpi_filesystems`.`name` AS fsname,
                       `glpi_items_disks`.*
                FROM `glpi_items_disks`
                LEFT JOIN `glpi_filesystems`
                          ON (`glpi_items_disks`.`filesystems_id` = `glpi_filesystems`.`id`)
                WHERE `items_id` = ".$id."
                      AND `itemtype` = 'Computer'
                      AND `is_deleted` = 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $i=0;
    while($row = $result->fetch_assoc()) {
          $names[$i] = $row["name"];
               $fs[$i] = $row['fsname'];
              $mount[$i] = $row['mountpoint'];
        $totalsize[$i]= round(( $row['totalsize'] /1024),2);
        $freesize[$i]= round(( $row['freesize'] /1024),2);
      $percent[$i] = round(100*$row['freesize']/$row['totalsize']);
//        echo $totalsize[$i]."GB Total & ".$freesize[$i]."GB Free & ".$percent[$i]."%<br>";
//        echo $names[$i]."<br>";
        $i++;
    }
    
   
  header('Content-Type: text/csv; charset=utf-8');

  header('Content-Disposition: attachment; filename='.$filename);
  $output = fopen("php://output","w");
  fputcsv($output, array('Name','Mount Point','File system','Global size  (GB)','Free size (GB)','Free percentage (%)'));
  
    for ($x=0; $x<$i; $x++)
    {
        fputcsv($output, array(($names[$x]),$mount[$x],$fs[$x],$totalsize[$x],$freesize[$x],$percent[$x]));
    }
    
} else {
    echo "Invalid Device Id(Report)!!";
}
$conn->close();
?>