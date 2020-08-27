<?php
//This is temporal file only for add new row in announcement
if (isset($_POST['addrow'])) { 
$Is_Active = $_POST["Is_Active"]; 
$Topic = $_POST["Topic"]; 
$Message = $_POST["Message"]; 
$Date_LastUpdate = $_POST["Date_LastUpdate"]; 
$Language = $_POST["Language"]; 
$Auto_Publish = $_POST["Auto_Publish"]; 
$Date_Start = $_POST["Date_Start"]; 
$Date_End = $_POST["Date_End"]; 
$Date_Created = $_POST["Date_Created"]; 
$Created_By = $_POST["Created_By"]; 
$Translated_ID = $_POST["Translated_ID"]; 

    if (!empty($Is_Active) && !empty($Topic) && !empty($Message) && !empty($Date_LastUpdate) && !empty($Language) && !empty($Auto_Publish) && !empty($Date_Start) && !empty($Date_End) && !empty($Date_Created) && !empty($Created_By) && !empty($Translated_ID)) { 
        $query = "INSERT INTO `$tble`(`Is_Active` , `Topic` , `Message` , `Date_LastUpdate` , `Language` , `Auto_Publish` , `Date_Start` , `Date_End` , `Date_Created` , `Created_By` , `Translated_ID`) VALUES ('$Is_Active' , '$Topic' , '$Message' , '$Date_LastUpdate' , '$Language' , '$Auto_Publish' , '$Date_Start' , '$Date_End' , '$Date_Created' , '$Created_By' , '$Translated_ID')";
if ($link->query($query) == TRUE) {
               echo "Record added successfully";                                           
            } else {
               echo "Error added record: " . $link->error;
            }
            unlink("announcement.php");
            
    } 
} 
?> 
