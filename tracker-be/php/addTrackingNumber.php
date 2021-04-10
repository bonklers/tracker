<?php
    include 'helperFunctions.php';
    include 'sqlFunctions.php';
    
    header("Content-Type: text/plain");
    if(!session_id()) session_start();
 
    // init log file
    file_put_contents("../logs/addTrackingNumber.log","START \n");

    //TODO: dont allow tracking number repeats
    
    $db = getDB();
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $username = $data["username"];
    $description = $data["description"];
    $trackingNumber = $data["trackingNumber"];

    //  get order history count
    $userDataCount = getUserDataCount($db, $username);
   
    $sentUnixtime = getUnixtime();
    $sqlInsertOrder = "INSERT INTO userData (username, trackingNumber, description, ";
    $sqlInsertOrder .= "sentUnixtime, receivedUnixtime, imageLocation) ";
    $sqlInsertOrder .= "VALUES(\"$username\", \"$trackingNumber\", \"$description\", \"$sentUnixtime\",  \"0\", \"\");";
    
    file_put_contents("../logs/addTrackingNumber.log","sqlInsertOrder: $sqlInsertOrder \n", FILE_APPEND);
    
    sqlExecute($sqlInsertOrder, $db);
    
    // get new order history count
    $newUserDataCount = getUserDataCount($db, $username);
    
    header('Content-Type: application/json');
    if(($userDataCount + 1) == $newUserDataCount)
    {
        echo "200";
    }
    else
    {
        echo "400";
    }
    
    $db = null;
?>
