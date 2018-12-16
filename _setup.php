<?php
// Determine which function to run based on the passed query parameter(s)
if(isset($_GET["read"]) && isset($_GET["plot"])) {
    plotSymptomSeverities(); 
}
else if(isset($_GET["write"]) && isset($_GET["symptom"])) {
    writeSymptoms(); 
}
else if (isset($_GET["read"])) {
    readSymptoms();
}

function readSymptoms() {
    $result = array();
    
    // Open file    
    $file_name = "symptoms.txt";
    $file_handle = fopen($file_name, "r");
    
    // Read data from file into array
    while (($line = fgets($file_handle)) !== false) {
        array_push($result, $line);
    }
    
    // Close file
    fclose($file_handle);
    
    // Change the inner HTML of the div on the webpage
    echo json_encode($result);
}

function writeSymptoms() {
    $result = array();
    
    // Open file
    $file_handle = fopen("symptoms.txt", "a");
    
    // Write to file
    $txt = $_GET["symptom"] . "\n";
    fwrite($file_handle, $txt);
    
    // Close file
    fclose($file_handle);
}

function plotSymptomSeverities() {
    $result = array();
    $numSymptoms = 0;
    
    // Open file
    $symptoms_file = fopen("symptoms.txt", "r");
    $tracking_file = fopen("tracking.txt", "r");
    
    $result[0] = 5; // Hold place of first element for number of symptoms
    
    // Read data from file into array
    while (($line = fgets($symptoms_file)) !== false) {
        array_push($result, $line);
        $numSymptoms += 1;
    }
    
    // Close file
    fclose($symptoms_file);
    
    $result[0] = $numSymptoms;
    
    // Read data from file into array
    while (($line = fgets($tracking_file)) !== false) {
        array_push($result, $line);
    }
    
    fclose($tracking_file);
    
    // Change the inner HTML of the div on the webpage
    echo json_encode($result); // Result contains: symptoms, date, symptom severities for preceeding date
}
?>