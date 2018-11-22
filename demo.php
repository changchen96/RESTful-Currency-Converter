<?php
$file = simplexml_load_file("currDataXml.xml");
$currFilename = "currDataXml.xml";
$jsonFilename = "currDataJson.json";
$currFile = file_get_contents($jsonFilename);
$countryFile = file_get_contents("countries.json");
$decodedData = json_decode($currFile);
$decodedData2 = json_decode($countryFile, true);
//var_dump($decodedData);
//var_dump($decodedData);
$currArray2 = array();
$count = $file->rates->currency->count();
for($a = 0; $a < $count; $a++)
{
$currArray2[] = (string)$file->rates->currency[$a]->currencyName;
}
//var_dump($currArray2);

foreach ($decodedData->rates as $name=>$rate)
{
    for ($b = 0; $b < count($currArray2); $b++)
{
    if ($currArray2[$b] == $name)
    {
        echo $name;
        echo "<br>";
        echo $rate;
        echo "<br>";
        foreach ($decodedData2 as $obj)
        {
            if ($name == $obj['currencies'][0]['code'])
            {
                $name =  $obj['currencies'][0]['name'];
                $country =  $obj['name'];
            }
            else if ($name != $obj['currencies'][0]['code'])
            {
               for ($c = 0; $c < count ($obj['currencies']); $c++)
               {
                   if ($name == $obj['currencies'][$c]['code'])
                   {
                       $name =  $obj['currencies'][$c]['name'];
                       $country .= $obj['name'].",";
                   }
               }
            }
        }
        echo $name;
        echo "<br>";
        echo $country;
        $country = '';
        echo "<br>";
    }
}
}

date_default_timezone_set('GMT');
$date = date('d F Y h:i:s a', time());
echo "The date and time now is: ".$date;
echo "<br>";

echo filemtime($currFilename);
echo "<br>";
echo "File last modified: ".date("F d Y H:i:s.",filemtime($currFilename));
echo "<br>";
echo "Current time in unix:".time();
echo "<br>";
$currTime = time();
$fileModifiedTime = filemtime($currFilename) + ((60*60)*12);;
echo $fileModifiedTime ;
echo "<br>";
if ($currTime > $fileModifiedTime)
{
    echo "Rates need to be updated!";
}

?>