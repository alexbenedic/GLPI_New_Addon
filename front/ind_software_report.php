<?php
$id = $_GET["id"];
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "glpi";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT name
FROM `glpi_softwares`
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
$conn->close();
$filename = $name.".csv";

  $con = mysqli_connect('localhost','root','root','glpi') or exit("Connection Error");
  header('Content-Type: text/csv; charset=utf-8');

  header('Content-Disposition: attachment; filename='.$filename);
  $output = fopen("php://output","w");
  fputcsv($output, array('Desktop','Serial','Version','Software Name','Entity'));
//  $date_today = date("Y-m-d");
//  $date_today_plus=date("Y-m-d",strtotime('+30 days',strtotime(date("Y-m-d"))));
//  $date_month = date("Y-m-d",strtotime('-1 month',strtotime(date("Y-m-d"))));
  $query = "SELECT DISTINCT     `glpi_computers`.`name` AS compname,
                     `glpi_computers`.`serial`,
                     `glpi_softwareversions`.`name` AS version,
                      `glpi_softwares`.`name` AS softname,
                       `glpi_entities`.`completename` AS entity
                FROM `glpi_computers_softwareversions`
                INNER JOIN `glpi_softwareversions`
                     ON (`glpi_computers_softwareversions`.`softwareversions_id`
                           = `glpi_softwareversions`.`id`)
                INNER JOIN `glpi_computers`
                     ON (`glpi_computers_softwareversions`.`computers_id` = `glpi_computers`.`id`)
                LEFT JOIN `glpi_softwares` ON (`glpi_softwareversions`.`softwares_id` = `glpi_softwares`.`id`)
                LEFT JOIN `glpi_entities` ON (`glpi_computers`.`entities_id` = `glpi_entities`.`id`)
                LEFT JOIN `glpi_locations`
                     ON (`glpi_computers`.`locations_id` = `glpi_locations`.`id`)
                LEFT JOIN `glpi_states` ON (`glpi_computers`.`states_id` = `glpi_states`.`id`)
                LEFT JOIN `glpi_groups` ON (`glpi_computers`.`groups_id` = `glpi_groups`.`id`)
                LEFT JOIN `glpi_users` ON (`glpi_computers`.`users_id` = `glpi_users`.`id`)
                WHERE `glpi_softwareversions`.`softwares_id` = ".$id."
                       AND `glpi_computers`.`is_deleted` = 0
                       AND `glpi_computers`.`is_template` = 0
                       AND `glpi_computers_softwareversions`.`is_deleted` = 0
                ORDER BY `entity` ASC, `version`, `compname` ASC";
  
  $result = mysqli_query($con,$query);
$x=1;
  while($row = mysqli_fetch_assoc($result)){
      $x++;
    fputcsv($output, $row);
        }
  fclose($output);


?>