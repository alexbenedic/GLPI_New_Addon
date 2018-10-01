<?php
$id = $_GET["id"];
 include 'report_config.php';

$sql = "SELECT name
FROM `glpi_computers`
WHERE `id` =".$id;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $name = $row["name"];
    }
} else {
    echo "0 results";
}

$filename = $name.".csv";

 
  header('Content-Type: text/csv; charset=utf-8');
   
  header('Content-Disposition: attachment; filename='.$filename);
  $output = fopen("php://output","w");
  fputcsv($output, array('Name','Serial','Username','Comment','Date Mod','UUID','Date Create','Manufacturer','Model','Type'));
//  $date_today = date("Y-m-d");
//  $date_today_plus=date("Y-m-d",strtotime('+30 days',strtotime(date("Y-m-d"))));
//  $date_month = date("Y-m-d",strtotime('-1 month',strtotime(date("Y-m-d"))));
  $query="SELECT`glpi_computers`.`name`,`glpi_computers`.`serial`,`glpi_computers`.`contact`,`glpi_computers`.`comment`,`glpi_computers`.`date_mod`,`glpi_computers`.`uuid`,`glpi_computers`.`date_creation`,`glpi_manufacturers`.`name` AS manufact,`glpi_computermodels`.`name` AS model,`glpi_computertypes`.`name` AS type
FROM `glpi_computers`
INNER JOIN `glpi_manufacturers`
ON `glpi_computers`.`manufacturers_id` = `glpi_manufacturers`.`id`
INNER JOIN `glpi_computermodels`
ON `glpi_computers`.`computermodels_id` = `glpi_computermodels`.`id`
INNER JOIN `glpi_computertypes`
ON `glpi_computers`.`computertypes_id` = `glpi_computertypes`.`id`
Where `glpi_computers`.`id`=".$id;
  //$query = "select * from employee_activity where datetime_in >= DATE('".$frm."') and  datetime_out <= DATE('".$to."') order by datetime_in asc";
  $result = mysqli_query($conn,$query);
  while($row = mysqli_fetch_assoc($result)){
    fputcsv($output,$row);
        }
  fclose($output);
$conn->close();
?>