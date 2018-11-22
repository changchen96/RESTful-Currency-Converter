<?php
function deleteResults($deleteQuery)
{
    //Set timezone and date
    date_default_timezone_set('GMT'); 
    $date = date('d M Y h:i', time());

    //parse the request string to check
    parse_str($deleteQuery, $parsedOutput);
    $currCode = $parsedOutput['currCode']; //put the currency code into the assigned variable
    $format = $parsedOutput['format']; //put the format into the assigned variable
    $filename = "currDataXml.xml"; //declare filename
    $xml = simplexml_load_file($filename) or die("Error in opening file!"); //load file into a simplexml string
    foreach($xml->rates->children() as $currency) //loops through the string and finds the currency code
    {
        if ($currCode == $currency->currencyName) //if currency code is found and it is active, set it to inactive
        {
            if ($currency->currencyStatus == 'ACTIVE')
            {
                $currency->currencyStatus = 'INACTIVE';
                break;
            }
            if ($currency->currencyStatus == 'INACTIVE') //if currency is already inactive, the loop stops
            {
                break;
            }
        }
    }
    $xml->asXML($filename);

    if ($format == 'xml') //outputs xml if the format is xml
    {
        xmlDelOutput($date, $currCode);
    }
    if ($format == 'json') //outputs json if the format is json
    {
        jsonDelOutput($date, $currCode);
    }

}

function xmlDelOutput($date, $code) //function to output in xml
{
    $xmlOutput = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $xmlOutput .= '<method type="delete">';
    $xmlOutput .= '<at>'.$date.'</at>';
    $xmlOutput .= '<code>'.$code.'</code>';
    $xmlOutput .= '</method>';
    header('Content-Type: text/xml');
    echo $xmlOutput;
}

function jsonDelOutput($date, $code) //function to output in json
{
    $jsonOutput = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $jsonOutput .= '<method type="delete">';
    $jsonOutput .= '<at>'.$date.'</at>';
    $jsonOutput .= '<code>'.$code.'</code>';
    $jsonOutput .= '</method>';
    header('Content-Type: application/json');
    $xml = simplexml_load_string($jsonOutput);
    $json = json_encode($xml, JSON_PRETTY_PRINT);
    echo $json;
}

?>