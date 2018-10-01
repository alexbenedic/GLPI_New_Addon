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
        $name = "Software_List_".$row["name"];
    }
} else {
    echo "0 results";
}

$filename = $name.".csv";

  header('Content-Type: text/csv; charset=utf-8');

  header('Content-Disposition: attachment; filename='.$filename);
  $output = fopen("php://output","w");
  fputcsv($output, array('Sofware Name','Version','Installation Date'));
//  $date_today = date("Y-m-d");
//  $date_today_plus=date("Y-m-d",strtotime('+30 days',strtotime(date("Y-m-d"))));
//  $date_month = date("Y-m-d",strtotime('-1 month',strtotime(date("Y-m-d"))));
  $query = "SELECT      `glpi_softwares`.`name` AS softname,
                       `glpi_softwareversions`.`name` AS version,
                       `glpi_computers_softwareversions`.`date_install` AS dateinstall
                FROM `glpi_computers_softwareversions`
                LEFT JOIN `glpi_softwareversions`
                     ON (`glpi_computers_softwareversions`.`softwareversions_id`
                           = `glpi_softwareversions`.`id`)
                LEFT JOIN `glpi_states`
                     ON (`glpi_states`.`id` = `glpi_softwareversions`.`states_id`)
                LEFT JOIN `glpi_softwares`
                     ON (`glpi_softwareversions`.`softwares_id` = `glpi_softwares`.`id`)
                WHERE `glpi_computers_softwareversions`.`computers_id` = ".$id."
                      AND `glpi_computers_softwareversions`.`is_deleted` = 0

                ORDER BY `softname`, `version`";
  
  $result = mysqli_query($conn,$query);
$x=1;
  while($row = mysqli_fetch_assoc($result)){
      $x++;
    fputcsv($output,$row);
        }
  fclose($output);
$conn->close();
?>
