<?php

	require_once("../../config/connectdb.php");
    session_start();
    $userid = $_SESSION['uid'];
	//variable
	$row = array();

	try{
		$conn = new PDO($dsn, $user, $pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sqlReports = "SELECT * from reports where adviser_id='$userid' and report_type='Status Report' and coordinator_id = '0' order by date_created desc";
        $stmt = $conn->prepare($sqlReports);
		$stmt->execute();
		foreach ($stmt	as $report)
		{
            $reportId = $report['report_id'];
            $groupId = $report['group_id'];
            $reportFile = $report['report_file'];
            $reportType = $report['report_type'];
            $dateCreated = $report['date_created'];

            // $sqlGroupUsers = "SELECT * from group_users where user_id='$studentId'";
            // $stmt = $conn->prepare($sqlGroupUsers);
            // $stmt->execute();
            // foreach ($stmt	as $group_user)
            // {
            //     $groupId = $group_user['group_id'];
            // }

            $sqlGroups = "SELECT * from groups where group_id='$groupId'";
            $stmt = $conn->prepare($sqlGroups);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            foreach ($stmt	as $user)
            {
                $gid = $user["group_id"];
                $gname = $user["group_name"];
            }
            
            $row[0] = $gname;
            $row[1] = $reportFile;
            $row[2] = $dateCreated;	
            
            $row[3] =  "<a class='btn btn-xs btn-default btn-table' href='../student/".$reportFile."' download>View</a>".
                        "<button class='btn btn-xs btn-danger btn-table' id='btnDelete' >Delete</button>".
                        "<button class='btn btn-xs btn-success btn-table' reportId='".$reportId."' id='btnSendReport' >Send</button>";;
                            
            $output['data'][] = $row;
        }
        if($rowCount < 1) {
			$row[0] = "";
            $row[1] = "";
            $row[2] = "";
            $row[3] = "";
			$row[4] = "";
			$row[5] = "";
						
			$output['data'][] = $row;
		}

		echo json_encode($output);
	} 
	catch (PDOException $e)
	{
		echo 'PDO Exception Caught.';
		echo 'Error with the database: <br />';
		echo 'SQL Query: ', $sql;
		echo 'Error: ' . $e->getMessage();
	}
?>