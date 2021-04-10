<?php

    function getUnixtime()
    {
        return time();
    }
    
    function getFormattedDate($unixtime)
    {
        $dateObject = DateTime::createFromFormat( 'U', $unixtime );
        $dateObject->setTimezone(new DateTimeZone('America/Guayaquil'));
        //ecuador: "America/Guayaquil"
        //san fran: "America/Los_Angeles"
        return $dateObject->format("F j g:i a");
    }

?>
