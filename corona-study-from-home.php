<?php

/*
 * @author Saikat
 */

use function PHPSTORM_META\type;

$files = array(
    "School" => "collections-school.json",
    "Engineering" => "collections-engineering.json",
    "Science" => "collections-science.json",
    "Humanities & Social Sciences" => "collections-humanities.json",
    "Management & Law" => "collections-law_management.json",
    "Literature"=>"collections-literature.json"
);
// CovidCollection CSV File location
$filename = "C:\Users\SAIKAT\Downloads\StudyFromHome-links - Final Links.csv";
$csvFile = fopen($filename, "r");

// Json Output Location
$handlejson = "C:\Users\SAIKAT\Downloads\jec\\";

$titleUrlData = array();
$subject_Domain = "";
$subjectDataMarge = array();

$json_out = "";

// Read each line of the CSV file
while (! feof($csvFile)) {
    $line = fgetcsv($csvFile);
    if ($line) {
        if ($line[0]) {
            if ($json_out && $titleUrlData && $subject_Domain) {
                // echo "here we go";
                if (array_key_exists($json_out, $files)) {
                    $json_out = $files[$json_out];
                }
                $fileJson = fopen($handlejson . $json_out, "w+");
                $subjectData = array();
                $subjectData['name'] = $subject_Domain;
                $subjectData['link'] = $titleUrlData;
                array_push($subjectDataMarge, $subjectData);
                
                $JsonData = json_encode($subjectDataMarge,JSON_PRETTY_PRINT| JSON_UNESCAPED_SLASHES| JSON_UNESCAPED_UNICODE);
                
                
                fwrite($fileJson, $JsonData);
                unset($subjectDataMarge);
                $subjectDataMarge = array();
                unset($titleUrlData);
                $titleUrlData = array();
                fclose($fileJson);
            }
            $json_out = $line[0];
        }
        if ($line[1]) {

            if ($titleUrlData && $subject_Domain) {
                $subjectData = array();
                $subjectData['name'] = $subject_Domain;
                $subjectData['link'] = $titleUrlData;
                array_push($subjectDataMarge, $subjectData);
            }
            $subject_Domain = $line[1];
            // print_r($titleUrlData);
            unset($titleUrlData);
            $titleUrlData = array();
            if ($line[2] && $line[3]) {
                $obj_cls = array();
                $obj_cls['title'] = $line[2];    
                if (strpos ($line[3],"/result?q=")!==false) {
                    $obj_cls['link']="/result?q=".urlencode(urldecode(str_replace("/result?q=","",$line[3])));      
                    // print_r($obj_cls);
                    // exit();
              
                }     
                else{
                    $obj_cls['link'] =urldecode($line[3]);
                }   
                // print_r($obj_cls);
                // $TitleLink = json_encode($obj_cls, JSON_UNESCAPED_SLASHES);
                array_push($titleUrlData, $obj_cls);            }
            
        } else {
            if ($line[2] && $line[3]) {
                $obj_cls = array();            
                $obj_cls['title'] = $line[2];
                if (strpos ($line[3],"/result?q=")!==false) {
                    $obj_cls['link']="/result?q=".urlencode(urldecode(str_replace("/result?q=","",$line[3])));      
                }     
                else{
                    $obj_cls['link'] =urldecode($line[3]);
                }    
                  
                array_push($titleUrlData, $obj_cls);
                
            }          
        }
    }
}
if ($titleUrlData && $subject_Domain && $json_out) {   
    
    $subjectData = array();
    $subjectData['name'] = $subject_Domain;    
    $subjectData['link'] = $titleUrlData;
    // print_r($subjectData)
    array_push($subjectDataMarge, $subjectData);
    $JsonData = json_encode($subjectDataMarge,JSON_PRETTY_PRINT| JSON_UNESCAPED_SLASHES| JSON_UNESCAPED_UNICODE);
    
    if (array_key_exists($json_out, $files)) {
        $json_out = $files[$json_out];
        $fileJson = fopen($handlejson . $json_out, "w+");
        fwrite($fileJson, $JsonData);
        fclose($fileJson);
    }
    
    
    // print_r($subjectDataMarge);
}
fclose($csvFile);
