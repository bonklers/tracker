<?php
    
    // init log file
    file_put_contents("../logs/sqlFunctions.log","START \n");

    function getDB()
    {
        $dbPath = "../database/database.db";
        try {
            // db opening
            $db = new PDO('sqlite:'.$dbPath);
            return $db;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    
    function sqlExecute($sqlCommand, $db)
    {
        //file_put_contents("../logs/sqlFunctions.log","sqlExecute \n", FILE_APPEND);

        try {
            $db_query = $db->prepare($sqlCommand);
            return $db_query->execute();
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            die();
            return 0;
        }
    }
    
    function sqlQueryFetch($sqlCommand, $db)
    {
        //file_put_contents("../logs/sqlFunctions.log","sqlQueryFetch \n", FILE_APPEND);
        
        try {
            $db_query = $db->query($sqlCommand);
            return $db_query->fetch();
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            die();
            return 0;
        }
    }
    
    function sqlQueryCount($sqlCommand, $db)
    {
        //file_put_contents("../logs/sqlFunctions.log","sqlQueryCount \n", FILE_APPEND);

        try {
            $db_query = $db->query($sqlCommand);
            $result = $db_query->fetch();
            return $result['count(*)'];
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            die();
            return 0;
        }
    }

    function getUserDataCount($db, $username)
    {
        try {
            // QUERY for id command
            $sqlQuery = "SELECT count(*) FROM userData WHERE username = \"$username\";";
            file_put_contents("../logs/addTrackingNumber.log","getUserDataCount sqlQuery: $sqlQuery \n", FILE_APPEND);
            return sqlQueryCount($sqlQuery, $db);
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
        return 0;
    }
    
    function getAllData($db)
    {
        $allData = array();
        try {
            // QUERY for id command
            $sqlQuery = "SELECT id, username, trackingNumber, description, sentUnixtime, receivedUnixtime, imageLocation FROM userData;";
            //file_put_contents("../logs/sqlFunctions.log","sqlQuery: $sqlQuery\n", FILE_APPEND);
            
            $db_query = $db->query($sqlQuery);
            $arrayCount = 0;
            while($result = $db_query->fetch())
            {
                $allData[$arrayCount] = $result;
                $arrayCount++;
            }
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
        return $allData;
    }

    function getUserData($db, $username)
    {
        $userData = array();
        try {
            // QUERY for id command
            $sqlQuery = "SELECT id, username, trackingNumber, description, sentUnixtime, receivedUnixtime, imageLocation FROM userData WHERE username = \"$username\";";
            file_put_contents("../logs/getUserData.log","sqlQuery: $sqlQuery\n", FILE_APPEND);
            
            $db_query = $db->query($sqlQuery);
            if(!$db_query)
            {
                return $userData;
            }
            
            $arrayCount = 0;
            while($result = $db_query->fetch())
            {
                $userData[$arrayCount] = $result;
                $arrayCount++;
            }
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
        return $userData;
    }
    
    function createDatabase($db)
    {
        file_put_contents("../logs/sqlFunctions.log","createDatabase \n", FILE_APPEND);

        $sqlUserData = 'CREATE TABLE IF NOT EXISTS userData ( id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL, trackingNumber TEXT NOT NULL, ';
        $sqlUserData .= 'description TEXT NOT NULL, sentUnixtime bigint NOT NULL, receivedUnixtime bigint NOT NULL, imageLocation TEXT NOT NULL);';

        file_put_contents("../logs/sqlFunctions.log","$sqlUserData \n", FILE_APPEND);

        try {
            // create orderHistory table
            $db_query = $db->prepare($sqlUserData);
            $result = $db_query->execute();
            file_put_contents("../logs/sqlFunctions.log","success created table \n", FILE_APPEND);

        }
        catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
?>
