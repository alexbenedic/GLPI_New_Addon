<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";
$glpi_com = array();
$glpi_ocs = array();
$data_cpu = array();
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else
{
//    echo "ok";
}

$sql = "SELECT `glpi`.`glpi_plugin_ocsinventoryng_ocslinks`.`computers_id`,`ocsweb`.`cpus`.`TYPE`, `ocsweb`.`cpus`.`CORES`, `ocsweb`.`cpus`.`LOGICAL_CPUS`  FROM `glpi`.`glpi_plugin_ocsinventoryng_ocslinks` 
right join  `ocsweb`.`cpus` 
on (`ocsweb`.`cpus`.`HARDWARE_ID` = `glpi`.`glpi_plugin_ocsinventoryng_ocslinks`.`ocsid`)";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
//        $glpi_com[][$row['computers_id']] = $row['ocsid'];
        $glpi_com[] = $row;
//        $glpi_ocs[] = $row['ocsid'];
    }
} else {
    echo "0 results";
}

//$all = sizeof($glpi_com);
////echo $all;
//for ($xx=0; $xx< $all; $xx++)
//{
//    
//    
//    $sql = "SELECT `ocsweb`.`cpus`.`TYPE`, `ocsweb`.`cpus`.`CORES`, `ocsweb`.`cpus`.`LOGICAL_CPUS`
//FROM `ocsweb`.`cpus`
//WHERE `ocsweb`.`cpus`.`HARDWARE_ID` = '".$glpi_ocs[$xx]."' ";
//$result = $conn->query($sql);
//
//if ($result->num_rows > 0) {
//    // output data of each row
//    while($row = $result->fetch_assoc()) {
////        $glpi_com[][$row['computers_id']] = $row['ocsid'];
//        $data_cpu[$glpi_com[$xx]] = $row ;
////        $data['cpu'][] = $row ;
//    }
//} else {
//    echo "0 results";
//}
//    
//}

foreach ($glpi_com as $xx)
{
//echo $xx['computers_id'];
       $sql = "INSERT INTO `glpi_plugin_fields_computercpus` (`items_id`, `itemtype`, `plugin_fields_containers_id`, `corefield`,`logicalcpufield`,`namefield`)
VALUES ('".$xx['computers_id']."', 'Computer', '3', '".$xx['CORES']."', '".$xx['LOGICAL_CPUS']."', '".$xx['TYPE']."');";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

}
    

$conn->close();
