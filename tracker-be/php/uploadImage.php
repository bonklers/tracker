<?php
    include 'helperFunctions.php';
    include 'sqlFunctions.php';

    file_put_contents("../logs/uploadImage.log","START \n");

    if( isset( $_POST["name"] ) )
    {
        $id = (isset($_GET["id"])) ? htmlentities($_GET["id"]) : NULL;
        $client_file = basename($_FILES["file"]["name"]);
        $imageFileType = strtolower(pathinfo($client_file,PATHINFO_EXTENSION));
        
        $errorMessage = "";
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
        {
            $errorMessage .= " Solo se aceptan fotos tipo JPG, JPEG y PNG. No aceptan $imageFileType";
        }
        
        $unixtime = getUnixtime();
        $server_file = "../images/"."image_".$id."_".$unixtime.".".$imageFileType;
        file_put_contents("../logs/uploadImage.log","client_file: $client_file \n", FILE_APPEND);
        file_put_contents("../logs/uploadImage.log","server_file: $server_file \n", FILE_APPEND);
        file_put_contents("../logs/uploadImage.log",print_r($_FILES, true), FILE_APPEND);
        if( $errorMessage == "" )
        {
            // Check if file already exists
            if (file_exists($server_file)) {
                $errorMessage .= " Un archivo con este nombre ya existe.";
            }
            else if(move_uploaded_file($_FILES["file"]["tmp_name"], $server_file ))
            {
                file_put_contents("../logs/uploadImage.log","Uploaded: $server_file \n", FILE_APPEND);
                
                $db = getDB();
                
                $filename = substr($server_file, 3);
                
                $sqlInsertOrder = "UPDATE userData SET imageLocation = \"$filename\",  receivedUnixtime = \"$unixtime\" ";
                $sqlInsertOrder .= "WHERE id = $id;";
                
                file_put_contents("../logs/uploadImage.log","sql: $sqlInsertOrder \n", FILE_APPEND);
                
                sqlExecute($sqlInsertOrder, $db);
                
                //TODO: verify db was updated, otherwise delete file?

                // // if menu count before == after then delete image file at $target_file
                // if($menuCountBefore == $menuCountAfter)
                // {
                //     file_put_contents("../logs/uploadImage.log","ERROR:  menuCountBefore:$menuCountBefore == menuCountAfter:$menuCountAfter\n", FILE_APPEND);
                //     unlink($filename);
                // }
                // else
                // {
                //     file_put_contents("../logs/uploadImage.log","SUCCESS:  menuCountBefore:$menuCountBefore < menuCountAfter:$menuCountAfter\n", FILE_APPEND);
                // }
                $db = null;
            }
            else
            {
                switch($_FILES['file']['error'])
                {
                    case UPLOAD_ERR_INI_SIZE:
                        $errorMessage = "FILE TOO LARGE, bigger than php.ini allows.";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMessage = "FILE TOO LARGE, bigger than html form allows.";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMessage = "PARTIAL UPLOAD.";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errorMessage = "NO FILE UPLOADED.";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errorMessage = "Upload temp directory doesn't exist.";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errorMessage = "Couldn't write to the temp directory.";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errorMessage = "Upload was blocked by a PHP extension.";
                        break;
                    default:
                        $errorMessage = "Unknown error.";
                }
            }
        }
        
        if($errorMessage != "")
        {
            file_put_contents("../logs/uploadImage.log","Error: $errorMessage \n",FILE_APPEND);
            
            echo "$errorMessage";
        }
        else
        {
            echo "200";
        }
    }
?>
