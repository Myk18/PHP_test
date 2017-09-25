<?php

// all to array

$i = 0;

$data["input_item"] = array();
while(!is_null($_GET["ident".strval($i)])){
	
	$ident = $_GET["ident".strval($i)];
	$val = $_GET["value".strval($i)];
	$ver = $_GET["version".strval($i)];

	$item_data = array("value"=>$val, "version"=>$ver);
	$item = array("ident"=>$ident,"ver_val"=>$item_data);		
	
	array_push($data["input_item"], $item);
	
	$i++;	
	
}
echo "input(json_encode)";
echo "<br/>";
echo json_encode($data["input_item"]); 
echo "<br/>";

// db

require 'db_connect.php';
$db = new DB_CONNECT(); 
$sql =  "
		SELECT *
		FROM data d
		ORDER BY ident
		";		
$sqlresult = mysql_query($sql) or die(mysql_error()); 
$data["db_item"] = array();
    
if (mysql_num_rows($sqlresult) > 0) {
	
	while ($row = mysql_fetch_array($sqlresult)) {
		
		$ident = $row["ident"];
		$val = $row["value"];
		$ver = $row["version"];
				
		$item_data = array("value"=>$val, "version"=>$ver);
		$item = array("ident"=>$ident,"ver_val"=>$item_data);		
	
		array_push($data["db_item"], $item);				
	}		
}
echo "current_db(json_encode)";
echo "<br/>";
echo json_encode($data["db_item"]); 
echo "<br/>";
echo "";
echo "<br/>";


// php 5.3
$db_idents = array_map(function($ar){return $ar['ident'];},$data["db_item"]);

$data["delete"] = array();
$data["new"] = array();
$data["update"] = array();

$i=0;
while ($data["input_item"][$i]["ident"]){
	
if(!in_array($data["input_item"][$i]["ident"], $db_idents )){	

	array_push($data["delete"], $data["input_item"][$i]["ident"]);
	
} else { 

	$new_ident = $data["input_item"][$i][ident];
	$new_ver = $data["input_item"][$i][ver_val][version];	
	
	if(checkVer($new_ident, $new_ver, $data["db_item"])){		
		array_push($data["update"], $data["input_item"][$i]["ident"]);		
	} else {
		array_push($data["new"], $data["input_item"][$i]["ident"]);
	}
	
}
$i++;	
}

// check version bool
function checkVer($ident,$ver,$arr){
	$bool = false;
	foreach ($arr as $key=>$value) {
		if (array_values($value)[0] = $ident){
			if(array_values($value)[1][version] > $ver ){
				$bool = true;
			}			
		}		
	}

	return $bool;
}

$result = array();
array_push($result, array("delete"=>$data["delete"]));
array_push($result, array("update"=>$data["update"]));
array_push($result, array("new"=>$data["new"]));

echo "<br/>";
echo "<br/>";
echo "output(json_encode)";
echo "<br/>";
echo json_encode($result);
echo "<br/>";
echo "<br/>";
print_r($result);






	




 

