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
        $name = "OS_Details_".$row["name"];
    }
} else {
    echo "0 results";
}

$filename = $name.".csv";

 
  header('Content-Type: text/csv; charset=utf-8');

  header('Content-Disposition: attachment; filename='.$filename);
  $output = fopen("php://output","w");
  fputcsv($output, array('OS Name','Serial','Product ID','Version','Achitecture','Service Pack'));
//  $date_today = date("Y-m-d");
//  $date_today_plus=date("Y-m-d",strtotime('+30 days',strtotime(date("Y-m-d"))));
//  $date_month = date("Y-m-d",strtotime('-1 month',strtotime(date("Y-m-d"))));
  $query = "SELECT  `glpi_operatingsystems`.`name`,`glpi_items_operatingsystems`.`license_number` AS  Serial,
`glpi_items_operatingsystems`.`license_id` AS ProductID,
                       `glpi_operatingsystemversions`.`name` AS version,
                       `glpi_operatingsystemarchitectures`.`name` AS architecture,
                       `glpi_operatingsystemservicepacks`.`name` AS servicepack
                FROM `glpi_items_operatingsystems`
                LEFT JOIN `glpi_operatingsystems`
                           ON (`glpi_items_operatingsystems`.`operatingsystems_id`=`glpi_operatingsystems`.`id`)
                LEFT JOIN `glpi_operatingsystemservicepacks`
                           ON (`glpi_items_operatingsystems`.`operatingsystemservicepacks_id`=`glpi_operatingsystemservicepacks`.`id`)
                LEFT JOIN `glpi_operatingsystemarchitectures`
                        ON (`glpi_items_operatingsystems`.`operatingsystemarchitectures_id`=`glpi_operatingsystemarchitectures`.`id`)
                LEFT JOIN `glpi_operatingsystemversions`
                        ON (`glpi_items_operatingsystems`.`operatingsystemversions_id` = `glpi_operatingsystemversions`.`id`)
                WHERE `glpi_items_operatingsystems`.`items_id` = ".$id."
                      AND `glpi_items_operatingsystems`.`itemtype` = 'Computer'  ORDER BY `glpi_items_operatingsystems`.`id` DESC";
  
  $result = mysqli_query($conn,$query);
$x=1;
  while($row = mysqli_fetch_assoc($result)){
      $x++;
    fputcsv($output,$row);
        }
  fclose($output);
$conn->close();
?>