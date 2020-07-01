<?php

//CONNECT & SELECT DB
$connect = mysqli_connect("localhost","root","") 
or die('Database Not Connected. Please Fix the Issue! ' . mysqli_error()); 
mysqli_select_db($connect,"rexxevent_db");


//CHECK IF TABLE EXIST. IF DOES NOT EXIST THEN CREATE
if(mysqli_num_rows(mysqli_query($connect, "SELECT * FROM information_schema.tables WHERE table_schema = 'rexxevent_db' AND table_name = 'records'") ) == null) 
{
	//CREATE TABLE INSIDE REXXEVENT_DB
	$create_query = "CREATE TABLE `records` (
		`participation_id` int(10) NOT NULL,
		`employee_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
		`employee_mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
		`event_id` bigint(20) NOT NULL,
		`event_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
		`participation_fee` double(6,2) NOT NULL,
		`event_date` timestamp NOT NULL DEFAULT current_timestamp(),
		`version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
		PRIMARY KEY (participation_id),
		INDEX ID_records_employee_cover (employee_name,event_id,event_name,event_date)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	mysqli_query($connect,$create_query);

	// GET CONTENT OF JSON FILE
	$jsonCont = file_get_contents('code_challenge.json'); 

	//DECODE JSON DATA TO PHP ARRAY
	$content = json_decode($jsonCont, true);

	//CONVERT JSON ARRAY TO PHP
	foreach ($content as $eachjson) {
		
		//FETCH THE DETAILSOF JSON FILE 
		$participation_id = $eachjson['participation_id'];
		$employee_name = $eachjson['employee_name'];
		$employee_mail = $eachjson['employee_mail'];
		$event_id = $eachjson['event_id'];
		$event_name = $eachjson['event_name'];
		$participation_fee = $eachjson['participation_fee'];
		$event_date = $eachjson['event_date'];
		$version = $eachjson['version'];

		//INSERT FETCHED DATA INTO records TABLE 
		$query = "INSERT INTO records(participation_id, employee_name, employee_mail, event_id, event_name, participation_fee, event_date, version) VALUES('$participation_id', '$employee_name', '$employee_mail', '$event_id', '$event_name', '$participation_fee', '$event_date', '$version')";

		mysqli_query($connect,$query);
	}
}
?>