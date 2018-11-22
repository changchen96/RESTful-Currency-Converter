<?php
function getResults($query)
{
    //loads the config file for error checking 
    $xmlConfig = simplexml_load_file("config.xml") or die("Error opening file!");
    
    //sets the timezone and date/time
    date_default_timezone_set('GMT');
    $date = date('d M Y h:i', time());

    //parses the request string into an associative array
     parse_str($query, $parsedOutput);
     $amount = $parsedOutput['amnt'];
     $floatAmount = (float) $amount;
     $base = $parsedOutput['from'];
     $result = $parsedOutput['to'];

     //loads the currencies xml file as a simplexml object
     $currXml = simplexml_load_file("currDataXml.xml") or die("File not found!");
     foreach ($currXml->rates->children() as $rates)
     {
         if ($base == $rates->currencyName) //if the base currency code is found, put the data from the currenies xml file into their own respective variables
         {
             $baseCurr = $rates->currencyName; //base currency name
             $baseRate = $rates->currencyRate; //base currency rate
             $baseCountry = $rates->currencyCountry; //base currency country
             $baseCurrName = $rates->currencyFullName; //base currency full name
         }
         if($result == $rates->currencyName)
         { 
             $retrievedCurr = $rates->currencyName; //future currency name
             $retrievedRate = $rates->currencyRate; //future currency rate
             $retrievedCountry = $rates->currencyCountry; //future currency country
             $retrievedCurrName = $rates->currencyFullName; //future currency fullname
         }
     }

     //convert both of the rates into float
     $floatBase = (float) $baseRate; //converts the base rate into a float value
     $floatRetrieved = (float) $retrievedRate; //converts the future rate into a float value

     //conversion of currencies occur here
     $floatConv = 1/$floatBase;
     $endResult = $floatAmount * $floatConv * $floatRetrieved;
     $finalResult = round($endResult, 2);

     if ( !array_key_exists('format', $parsedOutput) || $parsedOutput['format'] == "")  //if the format parameter doesn't exist or it is empty, output in xml
     {
         xmlOutput($date, $baseCurr, $baseCurrName, $floatAmount, $floatBase, $retrievedCurr, $retrievedCurrName, $finalResult, $baseCountry, $retrievedCountry);
     }
     else if ($parsedOutput['format'] == "json") //if the format parameter is json, output in json
     {
        jsonOutput($date, $baseCurr, $baseCurrName, $floatAmount, $floatBase, $retrievedCurr, $retrievedCurrName, $finalResult, $baseCountry, $retrievedCountry);
     }
     else if ($parsedOutput['format'] == "xml") //if the format parameter is xml, output in xml
     {
        xmlOutput($date, $baseCurr, $baseCurrName, $floatAmount, $floatBase, $retrievedCurr, $retrievedCurrName, $finalResult, $baseCountry, $retrievedCountry);
     }
     else //if format parameter exists, but the value is not json or xml
     {
            $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
            $xmlError .= "<conv>";
            $xmlError .= "<error>";
            $xmlError .= "<code>".$xmlConfig->errorDesc->error[4]->errorCode."</code>";
            $xmlError .= "<msg>".$xmlConfig->errorDesc->error[4]->errorMessage."</msg>";
            $xmlError .= "</error>";
            $xmlError .= "</conv>";  
            header('Content-Type: text/xml');
            echo $xmlError;
     }
}     

function xmlOutput($date, $curr, $name, $amount, $rate, $targetCurr, $targetCurrName, $finalAmount, $countryFrom, $countryTo) //function to output in xml
{
    $xmlCurr = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $xmlCurr .= "<conv>";
    $xmlCurr .= "<at>".$date."</at>";
    $xmlCurr .= "<rate>".$rate."</rate>";
    $xmlCurr .= "<from>";
    $xmlCurr .= "<code>".$curr."</code>";
    $xmlCurr .= "<curr>".$name."</curr>";
    $xmlCurr .= "<loc>".$countryFrom."</loc>";
    $xmlCurr .= "<amnt>".$amount."</amnt>";
    $xmlCurr .= "</from>";
    $xmlCurr .= "<to>";
    $xmlCurr .= "<code>".$targetCurr."</code>";
    $xmlCurr .= "<curr>".$targetCurrName."</curr>";
    $xmlCurr .= "<loc>".$countryTo."</loc>";
    $xmlCurr .= "<amnt>".$finalAmount."</amnt>";
    $xmlCurr .= "</to>";
    $xmlCurr .= "</conv>";
    header('Content-Type: text/xml');
    echo $xmlCurr;
}

function jsonOutput($date, $curr, $name, $amount, $rate, $targetCurr, $targetCurrName, $finalAmount, $countryFrom, $countryTo) //function to output in json
{
    $jsonCurr = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $jsonCurr .= "<conv>";
    $jsonCurr .= "<at>".$date."</at>";
    $jsonCurr .= "<rate>".$rate."</rate>";
    $jsonCurr .= "<from>";
    $jsonCurr .= "<code>".$curr."</code>";
    $jsonCurr .= "<curr>".$name."</curr>";
    $jsonCurr .= "<loc>".$countryFrom."</loc>";
    $jsonCurr .= "<amnt>".$amount."</amnt>";
    $jsonCurr .= "</from>";
    $jsonCurr .= "<to>";
    $jsonCurr .= "<code>".$targetCurr."</code>";
    $jsonCurr .= "<curr>".$targetCurrName."</curr>";
    $jsonCurr .= "<loc>".$countryTo."</loc>";
    $jsonCurr .= "<amnt>".$finalAmount."</amnt>";
    $jsonCurr .= "</to>";
    $jsonCurr .= "</conv>";
    header('Content-Type: application/json');
    $xml = simplexml_load_string($jsonCurr);
    $json = json_encode($xml, JSON_PRETTY_PRINT);
    echo $json;
}



?>