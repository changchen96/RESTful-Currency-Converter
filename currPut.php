<?php
function putResults($putQuery)
{
    //define timezone and date and time
    date_default_timezone_set('GMT');
    $date = date('d M Y h:i', time());
    //parses the request string into an associative array
    parse_str($putQuery, $putOutput);
    $currCode = $putOutput['currCode'];
    $currRate = $putOutput['currRate'];
    $outputFormat = $putOutput['format'];
    $currNewFloatRate = (float) $currRate;
    //define country names and xml stuff
    $countryFilename = "countries.json";
    $filename = "currDataXml.xml";
    //sets country variable as empty first
    $country = '';
    $findCounter = 0;
    $putCounter = 0;
    //decode json file for countries
    $locFile = file_get_contents($countryFilename) or die ("Cannot load file!"); //gets the contents of the countries json file
    $jsonCountries = json_decode($locFile);
    $xmlStr = simplexml_load_file($filename) or die ("Cannot load file!"); //gets the contents of the currencies xml file
    foreach($xmlStr->rates->children() as $currency) //loops through the xml object and finds the requested currency
    {
        if ($currCode == $currency->currencyName) //if currency code is found
        {
            if ($currency->currencyStatus == 'INACTIVE') //if it's inactive, set it as active
            {
                $findCounter = $findCounter + 1;
                $currency->currencyStatus = 'ACTIVE';
                $currency->currencyRate = $currNewFloatRate;
                $country = $currency->currencyCountry;
                $currFullName = $currency->currencyFullName;
                break;
            }
            if ($currency->currencyStatus == 'ACTIVE') //if it's active, it does what POST does which is updating the rates
            {
                $findCounter = $findCounter + 1;
                $country = $currency->currencyCountry;
                $currency->currencyRate = $currNewFloatRate;
                $currFullName = $currency->currencyFullName;
                $findCounter = $findCounter + 1;
                break;
            }
        }
    }

    if ($findCounter == 0) //if the currency is not found, that means a new currency is going to be inserted into the file
    {
        $putCounter = $putCounter + 1;
        $sxe = new SimpleXMLElement(file_get_contents($filename));
        $newCurr = $sxe->rates->addChild('currency');
        $newCurr->addChild('currencyName', $currCode);
        $newCurr->addChild('currencyRate', $currRate);
    foreach($jsonCountries as $obj) //gets the country of the currency then adds it to the file
    {
    if ($currCode == $obj->currencies[0]->code)
    {
        $country .= $obj->name.",";
        $newCurr->addChild('currencyFullName', $obj->currencies[0]->name);
        $currFullName = $obj->currencies[0]->name;
        $newCurr->addChild('currencyCountry', $country);
        $newCurr->addChild('currencyStatus', 'ACTIVE');
        //$country = '';
        break;
    }
    }
    }

    if ($findCounter > 0) //saves the newly updated rates file (changing inactive currency to active)
    {
        $xmlStr->asXML($filename);
    }
    if ($putCounter > 0) //saves the newly updated rates file (added a new currency)
    {
        $sxe->asXML($filename);
    }

    if ($outputFormat == "xml") //outputs in xml if the format requested is xml
    {
        xmlPutOutput($date, $currRate, $currCode, $currFullName, $country);
    }
    if ($outputFormat == "json") //outputs in json if the format requested is json
    {
        jsonPutOutput($date, $currRate, $currCode, $currFullName, $country);
    }
}

function xmlPutOutput($date, $rate, $code, $name, $country) //function for outputting in xml
{
    $xmlOutput = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $xmlOutput .= '<method type="put">';
    $xmlOutput .= '<at>'.$date.'</at>';
    $xmlOutput .= '<rate>'.$rate.'</rate>';
    $xmlOutput .= '<curr>';
    $xmlOutput .= '<code>'.$code.'</code>';
    $xmlOutput .= '<name>'.$name.'</name>';
    $xmlOutput .= '<loc>'.$country.'</loc>';
    $xmlOutput .= '</curr>';
    $xmlOutput .= '</method>';
    header('Content-Type: text/xml');
    echo $xmlOutput;
}

function jsonPutOutput($date, $rate, $code, $name, $country) //function for outputting in json
{
    $jsonOutput = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $jsonOutput .= '<method type="put">';
    $jsonOutput .= '<at>'.$date.'</at>';
    $jsonOutput .= '<rate>'.$rate.'</rate>';
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