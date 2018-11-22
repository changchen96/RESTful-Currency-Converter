function checkRadio()
{
    var radioButtonValue = document.getElementsByName("radioMethod");
    for (var a = 0, length = radioButtonValue.length; a < length; a++)
{
    if(radioButtonValue[a].checked)
    {
        radio = radioButtonValue[a].value;
        console.log(radio);
        if (radio == "DELETE")
        {
            document.getElementById('currRateText').disabled = true;
        }
        else
        {
            document.getElementById('currRateText').disabled = false;
        }
    }
}    
}
//adapted from https://stackoverflow.com/questions/9618504/how-to-get-the-selected-radio-button-s-value
function checkMethod()
{
var method;
var radioButtonValue = document.getElementsByName('radioMethod');
for (var a = 0, length = radioButtonValue.length; a < length; a++)
{
    if(radioButtonValue[a].checked)
    {
        method = radioButtonValue[a].value;
        console.log(method);
        break;
    }
}    

var format;
var radioFormat = document.getElementsByName('format');
for (var b = 0, length = radioFormat.length; b < length; b++)
{
    if(radioFormat[b].checked)
    {
        format = radioFormat[b].value;
        console.log(format);
        break;
    }
}    

//code references end here

switch (method)
{

    case "POST":
    var xhttp = new XMLHttpRequest();
    var currCode = document.getElementsByName('currCode')[0].value;
    var currRate = document.getElementsByName('currRate')[0].value;
    xhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            console.log(this.responseText);
            document.getElementById("resultbox").value = this.responseText;
        }
    };
    xhttp.open("POST", "index.php",true);
    xhttp.send("currCode="+currCode+"&currRate="+currRate+"&format="+format);
    break;

    case "PUT":
    var xhttp = new XMLHttpRequest();
    var putCurrCode = document.getElementsByName('currCode')[0].value;
    var putCurrRate = document.getElementsByName('currRate')[0].value;
    xhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            console.log(this.responseText);
            document.getElementById("resultbox").value = this.responseText;
        }
    };
    xhttp.open("PUT", "index.php",true);
    xhttp.send("currCode="+putCurrCode+"&currRate="+putCurrRate+"&format="+format);
    break;

    case "DELETE":
    var xhttp = new XMLHttpRequest();
    var delCurr = document.getElementsByName('currCode')[0].value;
    xhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            console.log(this.responseText);
            document.getElementById("resultbox").value = this.responseText;
        }
    };
    xhttp.open("DELETE", "index.php",true);
    xhttp.send("currCode="+delCurr+"&format="+format);
    break;
}

}

