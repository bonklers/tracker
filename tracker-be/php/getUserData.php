<?php
    include 'helperFunctions.php';
    include 'sqlFunctions.php';
      
    if(!session_id()) session_start();
    
    // init log file
    file_put_contents("../logs/getUserData.log","START \n");
    
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data["username"];

    $db = getDB();
    createDatabase($db);
    
    // get a type array and title array, and do only 1 for loop
    $userDataArray = array();
    $status = 200;
    if($username == "admin")
    {
        $userDataArray = getAllData($db, $username);
        $status = 500;
    }
    else
    {
        $userDataArray = getUserData($db, $username);
    }
    header('Content-Type: application/json');
    echo "{\"userData\":[";
    $userDataCount = count($userDataArray);
    file_put_contents("../logs/getUserData.log","got data count: $userDataCount\n", FILE_APPEND);
    for ($i = 0; $i < $userDataCount; $i++) {
        if($i > 0)
            {
                echo ",";
            }
            
            $id = $userDataArray[$i]['id'];
            $trackingNumber = $userDataArray[$i]['trackingNumber'];
            $description = $userDataArray[$i]['description'];
            $sentUnixtime = $userDataArray[$i]['sentUnixtime'];
            $receivedUnixtime = $userDataArray[$i]['receivedUnixtime'];
            $imageLocation = $userDataArray[$i]['imageLocation'];
            $dataUsername = $userDataArray[$i]['username'];
            $sentDate = getFormattedDate($sentUnixtime);
            $receivedDate = "No Recibido";
            if($receivedUnixtime > 0)
            {
                $receivedDate = "Recibido ";
                $receivedDate .= getFormattedDate($receivedUnixtime);
            }
            echo "{";
            
            echo "\"id\": \"$id\",";
            echo "\"username\": \"$dataUsername\",";
            echo "\"trackingNumber\": \"$trackingNumber\",";
            echo "\"description\": \"$description\",";
            echo "\"sentDate\": \"$sentDate\",";
            echo "\"receivedDate\": \"$receivedDate\",";
            echo "\"imageLocation\": \"$imageLocation\"";
            
            echo "}";
    }
    
    echo "], \"status\": $status}";
 
    $db = null;
?>
