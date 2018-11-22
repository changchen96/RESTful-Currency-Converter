<?php
//error checking for GET requests 
function getErrorChecking($query)
{
    parse_str($query, $queryString);
    $errorCounter = 0; //counter for errors
    $queryCounter = 0; //counter for correct queries
    $foundFlag1 = FALSE; //flag to find the currency from
    $foundFlag2 = FALSE; //flag to find the currency to
    $params = array_keys($queryString);
    $paramsCount = count($params);
    //check if the query string fits the required format
        if (file_exists("config.xml"))
        {
            $xmlConfig = simplexml_load_file("config.xml") or die("Error opening file!");
        }
        else
        {
            $errorCounter = $errorCounter + 1;
            $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
            $xmlError .= "<conv>";
            $xmlError .= "<error>";
            $xmlError .= "<code>"."1500"."</code>";
            $xmlError .= "<msg>"."Error in service"."</msg>";
            $xmlError .= "</error>";
            $xmlError .= "</conv>";
            header('Content-Type: text/xml');
            echo $xmlError;
        }
        //check if the parameter exists
        if (array_key_exists('format', $queryString))
        {
            //if there's a missing parameter, output error code 1000 
                if ($paramsCount < 4)
                {
                    $queryCounter = $queryCounter + 1;
                    $errorCounter = $errorCounter + 1;
                    $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
                    $xmlError .= "<conv>"."<error>";
                    $xmlError .= "<code>".$xmlConfig->errorDesc->error[0]->errorCode."</code>";
                    $xmlError .= "<msg>".$xmlConfig->errorDesc->error[0]->errorMessage."</msg>";
                    $xmlError .= "</error>"."</conv>";
                    if ($queryString['format'] == "xml")
                    {
                    header('Content-Type: text/xml');
                    echo $xmlError;
                    }
                    else if ($queryString['format'] == "json")
                    {
                    $code = $xmlConfig->errorDesc->error[0]->errorCode;
                    $msg = $xmlConfig->errorDesc->error[0]->errorMessage;
                    $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
                    $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
                    echo $json;
                    }
                    else //if the format parameter does not exist, print it in xml
                    {
                    $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
                    $xmlError .= "<conv>";
                    $xmlError .= "<error>";
                    $xmlError .= "<code>".$xmlConfig->errorDesc->error[0]->errorCode."</code>";
                    $xmlError .= "<msg>".$xmlConfig->errorDesc->error[0]->errorMessage."</msg>";
                    $xmlError .= "</error>";
                    $xmlError .= "</conv>";  
                    header('Content-Type: text/xml');
                    echo $xmlError;
                    }
                }
                //if there's more than 4 parameters
                if ($paramsCount > 4)
                {
                    $queryCounter = $queryCounter + 1;
                    $errorCounter = $errorCounter + 1;
                    $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
                    $xmlError .= "<conv>";
                    $xmlError .= "<error>";
                    $xmlError .= "<code>".$xmlConfig->errorDesc->error[1]->errorCode."</code>";
                    $xmlError .= "<msg>".$xmlConfig->errorDesc->error[1]->errorMessage."</msg>";
                    $xmlError .= "</error>";
                    $xmlError .= "</conv>";
                    if ($queryString['format'] == "xml")
                    {
                    header('Content-Type: text/xml');
                    echo $xmlError;
                    }
                    else if ($queryString['format'] == "json")
                    {
                        $code = $xmlConfig->errorDesc->error[1]->errorCode;
                        $msg = $xmlConfig->errorDesc->error[1]->errorMessage;
                        $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
                        $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
                        echo $json;
                    }
                    else //if the format parameter does not exist, print it in xml
                    {
                    $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
                    $xmlError .= "<conv>";
                    $xmlError .= "<error>";
                    $xmlError .= "<code>".$xmlConfig->errorDesc->error[1]->errorCode."</code>";
                    $xmlError .= "<msg>".$xmlConfig->errorDesc->error[1]->errorMessage."</msg>";
                    $xmlError .= "</error>";
                    $xmlError .= "</conv>";  
                    header('Content-Type: text/xml');
                    echo $xmlError;
                    }
                }
            }
            if ($queryCounter == 0)
            {
                //check if the parameter exists, if not then output error 1000
                if (!array_key_exists('from',$queryString) || !array_key_exists('to',$queryString) || !array_key_exists('amnt',$queryString))
                {
                    $errorCounter = $errorCounter + 1;
                    $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
                    $xmlError .= "<conv>";
                    $xmlError .= "<error>";
                    $xmlError .= "<code>".$xmlConfig->errorDesc->error[0]->errorCode."</code>";
                    $xmlError .= "<msg>".$xmlConfig->errorDesc->error[0]->errorMessage."</msg>";
                    $xmlError .= "</error>";
                    $xmlError .= "</conv>";  
        
                    if (array_key_exists('format', $queryString)) //if the format parameter exists, then print according to the format requested
                    {
                    if ($queryString['format'] == "xml") //xml format
                    {
                    header('Content-Type: text/xml');
                    echo $xmlError;
                    }
                    if ($queryString['format'] == "json") //json format
                    {
                        $errorCounter = $errorCounter + 1;
                        $code = $xmlConfig->errorDesc->error[0]->errorCode;
                        $msg = $xmlConfig->errorDesc->error[0]->errorMessage;
                        $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
                        $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
                        echo $json;
                    
                    }
                    }
                    else if (!array_key_exists('format', $queryString) || $queryString['format'] == '') //if the format parameter does not exist, print it in xml
                    {
                        $errorCounter = $errorCounter + 1;
                    $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
                    $xmlError .= "<conv>";
                    $xmlError .= "<error>";
                    $xmlError .= "<code>".$xmlConfig->errorDesc->error[0]->errorCode."</code>";
                    $xmlError .= "<msg>".$xmlConfig->errorDesc->error[0]->errorMessage."</msg>";
                    $xmlError .= "</error>";
                    $xmlError .= "</conv>";  
                    header('Content-Type: text/xml');
                    echo $xmlError;
                    }
                }
                
            }

    //check if there's any mistakes for the currencies inputted
    if ($errorCounter == 0) //if no errors are present, do the checking
    {
        if (file_exists("currDataXml.xml"))
        {
            $xmlCurrency = simplexml_load_file("currDataXml.xml") or die ("Error opening file!");
        }
        if (!file_exists("currDataXml.xml"))
        {
            $errorCounter = $errorCounter + 1;
            $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
            $xmlError .= "<conv>";
            $xmlError .= "<error>";
            $xmlError .= "<code>"."1500"."</code>";
            $xmlError .= "<msg>"."Error in service"."</msg>";
            $xmlError .= "</error>";
            $xmlError .= "</conv>";
            header('Content-Type: text/xml');
            echo $xmlError;
        }
        //loop through the simplexml string to find the currency codes
    foreach ($xmlCurrency->rates->children() as $curr)
    {
       if ($queryString['from'] == $curr->currencyName) //if found, set flag to true
       {
           $foundFlag1 = TRUE;
       }
       if ($queryString['to'] == $curr->currencyName)
       {
           $foundFlag2 = TRUE;
       }
       if ($queryString['from'] == '') //if value is empty, set flag to false
       {
           $foundFlag1 = FALSE;
       }
       if ($queryString['to'] == '')
       {
           $foundFlag2 = FALSE;
       }
    }
    //generate error code if currency code is not found
    if ($foundFlag1 != TRUE || $foundFlag2 != TRUE || $foundFlag1 != TRUE && $foundFlag2 != TRUE)
       {
           if (!array_key_exists('format', $queryString)) //output xml if the format parameter is missing
           {
            $errorCounter = $errorCounter + 1;
            $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
            $xmlError .= "<conv>";
            $xmlError .= "<error>";
            $xmlError .= "<code>".$xmlConfig->errorDesc->error[2]->errorCode."</code>";
            $xmlError .= "<msg>".$xmlConfig->errorDesc->error[2]->errorMessage."</msg>";
            $xmlError .= "</error>";
            $xmlError .= "</conv>";
            header('Content-Type: text/xml');
            echo $xmlError;
           }
           if (array_key_exists('format', $queryString)) //outputs json or xml if the format parameter is present
           {
            if ($queryString['format'] == "xml")
            {
            $errorCounter = $errorCounter + 1;
            $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
            $xmlError .= "<conv>";
            $xmlError .= "<error>";
            $xmlError .= "<code>".$xmlConfig->errorDesc->error[2]->errorCode."</code>";
            $xmlError .= "<msg>".$xmlConfig->errorDesc->error[2]->errorMessage."</msg>";
            $xmlError .= "</error>";
            $xmlError .= "</conv>";
            header('Content-Type: text/xml');
            echo $xmlError;
            }
            else if ($queryString['format'] == "json")
            {
                $errorCounter = $errorCounter + 1;
                $code = $xmlConfig->errorDesc->error[2]->errorCode;
                $msg = $xmlConfig->errorDesc->error[2]->errorMessage;
                $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
                $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
                echo $json;
            }
           }
        }
    }

    //preg match checking adapted from https://stackoverflow.com/questions/6772603/check-if-number-is-decimal
    if ($errorCounter == 0)
    {
        if (!preg_match('/^\d+\.\d+$/', $queryString['amnt']))
    {
        if (!array_key_exists('format', $queryString)) //outputs xml if the format parameter is not present
        {
            $errorCounter = $errorCounter + 1;
            $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
            $xmlError .= "<conv>";
            $xmlError .= "<error>";
            $xmlError .= "<code>".$xmlConfig->errorDesc->error[3]->errorCode."</code>";
            $xmlError .= "<msg>".$xmlConfig->errorDesc->error[3]->errorMessage."</msg>";
            $xmlError .= "</error>";
            $xmlError .= "</conv>";
            header('Content-Type: text/xml');
            echo $xmlError;
        }
        if (array_key_exists('format', $queryString)) //outputs json or xml if the format parameter is present
        {
            if ($queryString['format'] == "xml") 
            {
            $errorCounter = $errorCounter + 1;
            $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
            $xmlError .= "<conv>";
            $xmlError .= "<error>";
            $xmlError .= "<code>".$xmlConfig->errorDesc->error[3]->errorCode."</code>";
            $xmlError .= "<msg>".$xmlConfig->errorDesc->error[3]->errorMessage."</msg>";
            $xmlError .= "</error>";
            $xmlError .= "</conv>";
            header('Content-Type: text/xml');
            echo $xmlError;
            }
            else if ($queryString['format'] == "json")
            {
                $errorCounter = $errorCounter + 1;
                $code = $xmlConfig->errorDesc->error[3]->errorCode;
                $msg = $xmlConfig->errorDesc->error[3]->errorMessage;
                $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
                $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
                echo $json;
            }
        }
    }
    }
    


    if ($errorCounter > 0) //if there's errors, return false to the function
    {
        return false;
    }
    else
    {
        return true;
    }

}

//error checking for POST
function postErrorChecking()
{
    $postData = file_get_contents('php://input');
    $currFilename = "currDataXml.xml";
    parse_str($postData, $parsedOutput); //parse the request string and puts it in an associative array
    $currCode = $parsedOutput['currCode']; //gets the currency code
    $currRate = $parsedOutput['currRate']; //gets the currency rate
    $errorCounterPost = 0; //sets error counter to 0
    $foundFlag = FALSE;
    if (file_exists("config.xml"))
    {
        $xmlConfig = simplexml_load_file("config.xml") or die("Error opening file!");
    }
    else
    {
        $errorCounterPost = $errorCounterPost + 1;
        $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
        $xmlError .= "<conv>";
        $xmlError .= "<error>";
        $xmlError .= "<code>"."1500"."</code>";
        $xmlError .= "<msg>"."Error in service"."</msg>";
        $xmlError .= "</error>";
        $xmlError .= "</conv>";
        header('Content-Type: text/xml');
        echo $xmlError;
    }
    if ($errorCounterPost == 0)
    {
        $currCodeLength = strlen($currCode);
        if ($currCodeLength > 3)
        {
        if ($parsedOutput['format'] == "xml")
        {
        $errorCounterPost = $errorCounterPost + 1;
        $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
        $xmlError .= '<method type="post">';
        $xmlError .= '<error>';
        $xmlError .= '<code>'.$xmlConfig->errorDesc->error[8]->errorCode.'</code>';
        $xmlError .= '<msg>'.$xmlConfig->errorDesc->error[8]->errorMessage.'</msg>';
        $xmlError .= '</error>';
        $xmlError .= '</method>';
        header('Content-Type: text/xml');
        echo $xmlError;
        }
        else if ($parsedOutput['format'] == "json")
        {
            $errorCounterPost = $errorCounterPost + 1;
            $code = $xmlConfig->errorDesc->error[8]->errorCode;
            $msg = $xmlConfig->errorDesc->error[8]->errorMessage;
            $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
            $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
            echo $json;
        }
        }
    }


    if ($errorCounterPost == 0)
    {
    //check currency, regex adapted from https://stackoverflow.com/questions/16588086/regular-expression-for-valid-2-digit-decimal-number
    if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $currRate))
    {
        if ($parsedOutput['format'] == "xml")
        {
        $errorCounterPost = $errorCounterPost + 1;
        $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
        $xmlError .= '<method type="post">';
        $xmlError .= '<error>';
        $xmlError .= '<code>'.$xmlConfig->errorDesc->error[7]->errorCode.'</code>';
        $xmlError .= '<msg>'.$xmlConfig->errorDesc->error[7]->errorMessage.'</msg>';
        $xmlError .= '</error>';
        $xmlError .= '</method>';
        header('Content-Type: text/xml');
        echo $xmlError;
        }
        else if ($parsedOutput['format'] == "json")
        {
            $errorCounterPost = $errorCounterPost + 1;
            $code = $xmlConfig->errorDesc->error[7]->errorCode;
            $msg = $xmlConfig->errorDesc->error[7]->errorMessage;
            $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
            $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
            echo $json;
        }
    }
    }

    if ($errorCounterPost == 0)
    {
        if (file_exists($currFilename))
        {
            $xmlCurr = simplexml_load_file($currFilename) or die ("Error opening file!");
        }
        if (!file_exists($currFilename))
        {
            $errorCounterPost = $errorCounterPost + 1;
            $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
            $xmlError .= "<conv>";
            $xmlError .= "<error>";
            $xmlError .= "<code>"."1500"."</code>";
            $xmlError .= "<msg>"."Error in service"."</msg>";
            $xmlError .= "</error>";
            $xmlError .= "</conv>";
            header('Content-Type: text/xml');
            echo $xmlError;
        }
        foreach($xmlCurr->rates->children() as $currency)
        {
            if ($currCode == $currency->currencyName)
            {
                $foundFlag = TRUE;
            }
        }
        if ($foundFlag != TRUE)
        {
        if ($parsedOutput['format'] == "xml")
        {
        $errorCounterPost = $errorCounterPost + 1;
        $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
        $xmlError .= '<method type="post">';
        $xmlError .= '<error>';
        $xmlError .= '<code>'.$xmlConfig->errorDesc->error[10]->errorCode.'</code>';
        $xmlError .= '<msg>'.$xmlConfig->errorDesc->error[10]->errorMessage.'</msg>';
        $xmlError .= '</error>';
        $xmlError .= '</method>';
        header('Content-Type: text/xml');
        echo $xmlError;
        }
        else if ($parsedOutput['format'] == "json")
        {
            $errorCounterPost = $errorCounterPost + 1;
            $code = $xmlConfig->errorDesc->error[10]->errorCode;
            $msg = $xmlConfig->errorDesc->error[10]->errorMessage;
            $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
            $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
            echo $json;
        }
        }
    }
    if ($errorCounterPost > 0)
    {
        return false;
    }
    else
    {
        return true;
    }
    
    
}

function putErrorChecking($putQuery)
{
    $currFilename = "currDataXml.xml";
    parse_str($putQuery, $putOutput);
    $currCode = $putOutput['currCode'];
    $currRate = $putOutput['currRate'];
    $errorCounterPut = 0;
    if (file_exists("config.xml"))
    {
        $xmlConfig = simplexml_load_file("config.xml") or die("Error opening file!");
    }
    if (!file_exists("config.xml"))
    {
        
        $errorCounterPut = $errorCounterPut + 1;
        $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
        $xmlError .= "<conv>";
        $xmlError .= "<error>";
        $xmlError .= "<code>"."1500"."</code>";
        $xmlError .= "<msg>"."Error in service"."</msg>";
        $xmlError .= "</error>";
        $xmlError .= "</conv>";
        header('Content-Type: text/xml');
        echo $xmlError;
    }
    $foundFlagPut = FALSE;

    if ($errorCounterPut == 0)
    {
        if (file_exists($currFilename))
        {
            $xmlCurr = simplexml_load_file($currFilename);
        }
        if (!file_exists($currFilename))
        {
            $errorCounterPut = $errorCounterPut + 1;
            $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
            $xmlError .= "<conv>";
            $xmlError .= "<error>";
            $xmlError .= "<code>"."1500"."</code>";
            $xmlError .= "<msg>"."Error in service"."</msg>";
            $xmlError .= "</error>";
            $xmlError .= "</conv>";
            header('Content-Type: text/xml');
            echo $xmlError;
        }
        foreach($xmlCurr->rates->children() as $currency)
        {
            if ($currCode == $currency->currencyName)
            {
                $foundFlagPut = TRUE;
            }
        }
        if ($foundFlagPut != TRUE)
        {
        if ($putOutput['format'] == "xml")
        {
            $errorCounterPut = $errorCounterPut + 1;
        $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
        $xmlError .= '<method type="put">';
        $xmlError .= '<error>';
        $xmlError .= '<code>'.$xmlConfig->errorDesc->error[10]->errorCode.'</code>';
        $xmlError .= '<msg>'.$xmlConfig->errorDesc->error[10]->errorMessage.'</msg>';
        $xmlError .= '</error>';
        $xmlError .= '</method>';
        header('Content-Type: text/xml');
        echo $xmlError;
        }
        else if ($putOutput['format'] == "json")
        {
            $errorCounterPut = $errorCounterPut + 1;
            $code = $xmlConfig->errorDesc->error[10]->errorCode;
            $msg = $xmlConfig->errorDesc->error[10]->errorMessage;
            $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
            $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
            echo $json;
        }
        }
    }

    if ($errorCounterPut == 0)
    {
    //check currency, regex adapted from https://stackoverflow.com/questions/16588086/regular-expression-for-valid-2-digit-decimal-number
    if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $currRate))
    {
        if ($putOutput['format'] == "xml")
        {
        $errorCounterPut = $errorCounterPut + 1;
        $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
        $xmlError .= '<method type="put">';
        $xmlError .= '<error>';
        $xmlError .= '<code>'.$xmlConfig->errorDesc->error[7]->errorCode.'</code>';
        $xmlError .= '<msg>'.$xmlConfig->errorDesc->error[7]->errorMessage.'</msg>';
        $xmlError .= '</error>';
        $xmlError .= '</method>';
        header('Content-Type: text/xml');
        echo $xmlError;
        }
        else if ($putOutput['format'] == "json")
        {
            $errorCounterPut = $errorCounterPut + 1;
            $code = $xmlConfig->errorDesc->error[7]->errorCode;
            $msg = $xmlConfig->errorDesc->error[7]->errorMessage;
            $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
            $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
            echo $json;
        }
    }
    }
    if ($errorCounterPut > 0)
    {
        return false;
    }
    else
    {
        return true;
    }

    
}

function delErrorChecking($deleteQuery)
{
    $currFilename = "currDataXml.xml";
    parse_str($deleteQuery, $delOutput);
    $currCode = $delOutput['currCode'];
    $format = $delOutput['format'];
    $errorCounterDel = 0;
    if (file_exists("config.xml"))
    {
        $xmlConfig = simplexml_load_file("config.xml") or die("Error opening file!");
    }
    if (!file_exists("config.xml"))
    {
        $errorCounterDel = $errorCounterDel + 1;
        $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
        $xmlError .= "<conv>";
        $xmlError .= "<error>";
        $xmlError .= "<code>"."1500"."</code>";
        $xmlError .= "<msg>"."Error in service"."</msg>";
        $xmlError .= "</error>";
        $xmlError .= "</conv>";
        header('Content-Type: text/xml');
        echo $xmlError;
    }
    $foundFlagDel = FALSE;

    if ($errorCounterDel == 0)
    {
        $currCodeLengthDel = strlen($currCode);
        if ($currCodeLengthDel > 3)
        {
            if ($format == "xml")
            {
                $errorCounterDel = $errorCounterDel + 1;
                $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
                $xmlError .= '<method type="delete">';
                $xmlError .= '<error>';
                $xmlError .= '<code>'.$xmlConfig->errorDesc->error[8]->errorCode.'</code>';
                $xmlError .= '<msg>'.$xmlConfig->errorDesc->error[8]->errorMessage.'</msg>';
                $xmlError .= '</error>';
                $xmlError .= '</method>';
                header('Content-Type: text/xml');
                echo $xmlError;
            }
            else if ($format == "json")
            {
                $errorCounterDel = $errorCounterDel + 1;
                $code = $xmlConfig->errorDesc->error[8]->errorCode;
                $msg = $xmlConfig->errorDesc->error[8]->errorMessage;
                $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
                $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
                echo $json;
            }
        }
    }

    if ($errorCounterDel == 0)
    {
        if (file_exists($currFilename))
        {
            $xmlCurr = simplexml_load_file($currFilename);
        }
        if (!file_exists($currFilename))
        {
            $errorCounterDel = $errorCounterDel + 1;
            $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
            $xmlError .= "<conv>";
            $xmlError .= "<error>";
            $xmlError .= "<code>"."1500"."</code>";
            $xmlError .= "<msg>"."Error in service"."</msg>";
            $xmlError .= "</error>";
            $xmlError .= "</conv>";
            header('Content-Type: text/xml');
            echo $xmlError;
        }
        foreach($xmlCurr->rates->children() as $currency)
        {
            if ($currCode == $currency->currencyName)
            {
                $foundFlagDel = TRUE;
            }
        }
        if ($foundFlagDel != TRUE)
        {
            if ($format == "xml")
            {
                $errorCounterDel = $errorCounterDel + 1;
                $xmlError = '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
                $xmlError .= '<method type="delete">';
                $xmlError .= '<error>';
                $xmlError .= '<code>'.$xmlConfig->errorDesc->error[10]->errorCode.'</code>';
                $xmlError .= '<msg>'.$xmlConfig->errorDesc->error[10]->errorMessage.'</msg>';
                $xmlError .= '</error>';
                $xmlError .= '</method>';
                header('Content-Type: text/xml');
                echo $xmlError;
            }
            else if ($format == "json")
            {
                $errorCounterDel = $errorCounterDel + 1;
                $code = $xmlConfig->errorDesc->error[10]->errorCode;
                    $msg = $xmlConfig->errorDesc->error[10]->errorMessage;
                    $jsonArray = array('conv'=>array('error'=>array("code"=> "$code", "msg"=> "$msg")));
                    $json = json_encode($jsonArray, JSON_PRETTY_PRINT);
                    echo $json;
            }
        }
    }

    if ($errorCounterDel > 0)
    {
        return false;
    }
    else
    {
        return true;
    }
}


?>