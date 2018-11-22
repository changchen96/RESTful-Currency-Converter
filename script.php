<?php
        // put your code here
        function getCurr() //gets the newest rates and puts them into a local file
        {
            $configXml = simplexml_load_file('config.xml') or die("Cannot open the file!");
            $apiURL = $configXml->apiConfig[0]->apiLink; //gets the first part of the URL
            $apiKey = $configXml->apiConfig[0]->apiKey; //gets the API key of the service
            $filename = 'currDataJson.json';
            $currJson = file_get_contents($apiURL.$apiKey) or die("Cannot access URL!");
            file_put_contents($filename, $currJson);
			
        }
		
		function convertXml() //function to put the data into an xml file
		{
            $jsonFilename = 'currDataJson.json'; //declares the rates json filename
            $countriesJson = 'countries.json'; //declares the countries json filename
            $xmlFilename = 'currDataXml.xml'; //declares the rates and currencies xml filename
            $countryNode = '';
            $currFileJson = file_get_contents($jsonFilename) or die ("Cannot open the file!"); //gets contents of the file, if it fails then output error
            $locFileJson = file_get_contents($countriesJson) or die("Cannot open the file!"); //gets contents of the file, if it fails then output error
            $decodedData = json_decode($currFileJson); //decodes the json
            $decodedCountries = json_decode($locFileJson, true); //decodes the json
            $countriesList = simplexml_load_file($xmlFilename) or die ("Cannot open the file!"); //loads the xml file into a simplexml string, if it fails then output error
            //$currArray = ['AUD','BRL','CAD','CHF','CNY','DKK','EUR','GBP','HKD','HUF','INR','JPY','MXN','MYR','NOK','NZD','PHP','RUB','SEK','SGD','THB','TRY','USD','ZAR']; //creating an array to get selected currencies
            $currArray = array(); //initializes a new array for the currencies
            $count = $countriesList->rates->currency->count(); //count the number of currencies in the file
            for($a = 0; $a < $count; $a++) //adds the currencies into an array so it can be checked with the rates file later
            {
                $currArray[] = (string)$countriesList->rates->currency[$a]->currencyName; //appends the codes to the array
            }
            $xmlCurrFile = '<?xml version="1.0" encoding="ISO-8859-1"?>';
            $xmlCurrFile .= '<currencyData>';
            $xmlCurrFile .= '<currencyInfo>';
            $xmlCurrFile .= '<timestamp>'.$decodedData->timestamp.'</timestamp>';
            $xmlCurrFile .= '<currencyBase>'.$decodedData->base.'</currencyBase>';
            $xmlCurrFile .= '</currencyInfo>';
            $xmlCurrFile .= '<rates>';
			foreach($decodedData->rates as $name=>$rate) //loops through the json file for rates which are retrieved from the API
			{
                for($i = 0; $i<count($currArray); $i++) //updates the rates in the xml file
                {
                    if ($currArray[$i] == $name) 
                    {
                        $xmlCurrFile .= '<currency>';
                        $xmlCurrFile .= '<currencyName>'.$name.'</currencyName>';
                        $xmlCurrFile .= '<currencyRate>'.$rate.'</currencyRate>';
                        foreach($decodedCountries as $obj) //gets the currency full name and the countries used
                        {
                            if ($name ==$obj['currencies'][0]['code'])
                            {
                                $currFullName = $obj['currencies'][0]['name']; //gets the currrency full name
                                $countryNode .= $obj['name'].","; //gets the countries
                            }
                            else if ($name != $obj['currencies'][0]['code']) //if the currency code does not equal to the first index
                            {
                            for ($c = 0; $c < count ($obj['currencies']); $c++) //loop through the array of the json file for countries
                            {
                                if ($name == $obj['currencies'][$c]['code']) //if code is found
                                {
                                    $currFullName =  $obj['currencies'][$c]['name']; //gets the currency full name
                                    $countryNode .= $obj['name'].","; //gets the countries
                                    break;
                                }
                            }
                            }
                        }
                        $xmlCurrFile .= '<currencyFullName>'.$currFullName.'</currencyFullName>';
                        $xmlCurrFile .= '<currencyCountry>'.$countryNode.'</currencyCountry>';
                        $countryNode = '';
                        $xmlCurrFile .= '<currencyStatus>'."ACTIVE".'</currencyStatus>';
                        $xmlCurrFile .= '</currency>';
                    }
                }
            }
            $xmlCurrFile .= '</rates>';
            $xmlCurrFile .= '</currencyData>';
            $myFile = fopen("currDataXml.xml","w+");//opens the file, it creates a new file if it doesn't exist
            if (is_resource($myFile))
            {
                fwrite($myFile, $xmlCurrFile); //writes data to a file
                fclose($myFile);  //closes the file 
            }
        }
        
?>
