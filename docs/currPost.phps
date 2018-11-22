<?php

function postResults()
{
    //sets date, time and timezone
    date_default_timezone_set('GMT');
    $date = date('d M Y h:i', time());

    //gets the request string and puts it into an associative array
    $postData = file_get_contents('php://input');
    parse_str($postData, $parsedOutput);
    $currCode = $parsedOutput['currCode'];
    $currRate = $parsedOutput['currRate'];
    $outputFormat = $parsedOutput['format'];
    $currNewFloatRate = (float) $currRate;

    //gets the filename for the rates and the currencies
    $filename = "currDataXml.xml";

    //loops through the simplexml object and finds the code that needs to be updated
    $currXml = simplexml_load_file($filename) or die("Error opening file");
    foreach ($currXml->rates->children() as $currency) //loops through the simplexml object and gets the currency code
    {
        if ($currCode == $currency->currencyName)
        {
            define('currOldRate', $currency->currencyRate); //a constant variable is used to prevent the retrieved old rate from changing
            $currName = $currency->currencyFullName; //puts the currency full name into a variable
            $currCountry = $currency->currencyCountry; //puts the currency country into a variable
            $currency->currencyRate = $currNewFloatRate; //puts the currency rate into a variable
            break;
        }
    }
    $currXml->asXML($filename); //saves the xml file

    if ($outputFormat == "xml") //outputs xml if format is xml
    {
        xmlPostOutput($date, $currNewFloatRate, currOldRate, $currCode, $currName, $currCountry);
    }
    if ($outputFormat == "json") //outputs json if format is json
    {
        jsonPostOutput($date, $currNewFloatRate, currOldRate, $currCode, $currName, $currCountry);
    }
}

function xmlPostOutput($date, $newRate, $oldRate, $code, $name, $country) //function for xml output
{
    $xmlOutput = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $xmlOutput .= '<method type="post">';
    $xmlOutput .= '<at>'.$date.'</at>';
    $xmlOutput .= '<rate>'.$newRate.'</rate>';
    $xmlOutput .= '<old_rate>'.currOldRate.'</old_rate>';
    $xmlOutput .= '<curr>';
    $xmlOutput .= '<code>'.$code.'</code>';
    $xmlOutput .= '<name>'.$name.'</name>';
    $xmlOutput .= '<loc>'.$country.'</loc>';
    $xmlOutput .= '</curr>';
    $xmlOutput .= '</method>';
    header('Content-Type: text/xml');
    echo $xmlOutput;
}

function jsonPostOutput($date, $newRate, $oldRate, $code, $name, $country) //function for json output
{
    $jsonOutput = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $jsonOutput .= '<method type="post">';
    $jsonOutput .= '<at>'.$date.'</at>';
    $jsonOutput .= '<rate>'.$newRate.'</rate>';
    $jsonOutput .= '<old_rate>'.currOldRate.'</old_rate>';
    $jsonOutput .= '<curr>';
    $jsonOutput .= '<code>'.$code.'</code>';
    $jsonOutput .= '<name>'.$name.'</name>';
    $jsonOutput .= '<loc>'.$country.'</loc>';
    $jsonOutput .= '</curr>';
    $jsonOutput .= '</method>';
    header('Content-Type: application/json');
    $xml = simplexml_load_string($jsonOutput);
    $json = json_encode($xml, JSON_PRETTY_PRINT);
    echo $json;
}


?>