<?php
//includes all the necessary files
include 'script.php';
include 'currGet.php';
include 'currPost.php';
include 'currPut.php';
include 'currDelete.php';
include 'errorHandling.php';
$currFilename = "currDataXml.xml"; //defines the rates file
$mainErrorCounter = 0;
//defines method name
$method = $_SERVER['REQUEST_METHOD'];

//gets the query string for GET when it's called
$query = $_SERVER['QUERY_STRING'];

//updates the rates if it's more than 12 hours
if (file_exists($currFilename))
{
    $currTime = time();
    $fileModifiedTime = filemtime($currFilename) + ((60*60)*12);;
    if ($currTime > $fileModifiedTime)
    {
        getCurr();
        convertXml();
    }
}
if (!file_exists($currFilename)) //if file doesn't exist, output error 1500
{
    $mainErrorCounter = $mainErrorCounter + 1;
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

//gets the query string when PUT or DELETE is called
if ($method == 'PUT')
{
    $putQuery = file_get_contents('php://input');
}
if ($method == 'DELETE')
{
    $deleteQuery = file_get_contents('php://input');
}

//switch...case loop for the method requested 
if ($mainErrorCounter == 0)
{
    switch ($method)
    {
        case 'GET': //when GET is requested
        if (getErrorChecking($query) === false) //error checking function is run first, if the query doesn't pass the test then the loop terminates
        {
            break;
        }
        else
        {
            getResults($query);
            break;
        }
    
        case 'POST': //when POST is requested
        if(postErrorChecking() === false) //error checking function is run first, if the query doesn't pass the test then the loop terminates
        {
            break;
        }
        else
        {
        postResults();
        break;
        }

        case 'PUT': //when PUT is requested
        if(putErrorChecking($putQuery) === false) //error checking function is run first, if the query doesn't pass the test then the loop terminates
        {
            break;
        }
        else
        {
        putResults($putQuery);
        break;
        }

        case 'DELETE': //when DELETE is requested
        if (delErrorChecking($deleteQuery) === false) //error checking function is run first, if the query doesn't pass the test then the loop terminates
        {
            break;
        }
        else
        {
            deleteResults($deleteQuery);
            break;
        }
    
    }

}




?>