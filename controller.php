<?php
	//DEFINE DATABASE CONNECTION
	define('DB_SERVER','localhost');
	define('DB_USER','root');
	define('DB_PASS' ,'');
	define('DB_NAME', 'rexxevent_db');

	//IF PARAMETERS PRESENT DURING CUSTOM SEARCH. SEND DATA TO FETCH DATA FUNCTION
	if(isset($_REQUEST['employee_name'],$_REQUEST['event_id'],$_REQUEST['event_date'])) {
		$fetchdata = new DB_con();
		$fetchdata->fetchdata($_REQUEST['employee_name'], $_REQUEST['event_id'], $_REQUEST['event_date']);
	}

	class DB_con
	{
		function __construct()
		{
			$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
			$this->dbh=$con;
			// CHECK CONNECTION
			if (mysqli_connect_errno()) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
		}


		//FUNCTION FOR CONVERTING THE TIMEZONE FROM EUROPE/BERLIN TO UTC
		function convertTime($event_date)
		{
			$received = $event_date;
			$tz = new DateTimeZone('UTC');
			$date = new DateTime($received);
			$date->setTimezone($tz);
			$converted = $date->format('Y-m-d H:i:s');
			return $converted;
		}

		//FUNCTION FOR VERSION COMPARE. WE PRESUME STANDARD TIMEZONE TO BE UTC.
		function versioncomparison($version, $event_date)
		{
			if (version_compare($version, '1.0.17+60', '<')) {
				return $this->convertTime($event_date); 
			}
			else {
				return $event_date;
			}
		}




		//FUNCTION FOR FETCHING ALL DATA AND SEARCHED DATA
		public function fetchdata($employee_name, $event_id, $event_date)
		{
			$query= "SELECT * FROM `records`";

			if(($employee_name !="") OR ($event_id != 0) OR ($event_date !="")) {
				$query.= " WHERE ";
			}
			if($employee_name !="") {
				$query.= "`employee_name` LIKE '%".$employee_name."%'";
			}
			if($event_id != 0) {
				if($employee_name !="") {
					$query.= " OR ";
				}
				$query.= "`event_id` = $event_id";
			}
			if($event_date !="") {
				if(($employee_name !="") OR ($event_id !=0)) {
					$query.= " OR ";
				}
				$query.= "`event_date` LIKE '%".$event_date."%'";
			}

			$result=mysqli_query($this->dbh, $query);
			$data = array();
			while($row=mysqli_fetch_array($result)) {
				//print_r ($row);
				$test = $this->versioncomparison($row['version'],$row['event_date']);
				$sub_array = array();
				$sub_array['employee_name'] = $row['employee_name'];
				$sub_array['employee_mail'] = $row['employee_mail'];
				$sub_array['event_name'] = $row['event_name'];
				$sub_array['participation_fee'] = $row['participation_fee'];
				$sub_array['event_date'] = $row['event_date'];
				$sub_array['version'] = $row['version'];
				$data[] = $sub_array;
			}

			$output = array('data' => $data);
			echo json_encode($output);
		}

		// FUNCTION FOR FETCHING EVENTS FOR DROPDOWN
		public function fetchevent()
		{
			$query= "select event_id, event_name from records GROUP BY event_id";
			$result=mysqli_query($this->dbh, $query);
			return $result;
		}









	}
?>