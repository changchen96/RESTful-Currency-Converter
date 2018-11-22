
//adapted from https://stackoverflow.com/questions/9618504/how-to-get-the-selected-radio-button-s-value
function checkMethod()
{
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
    var xhttp = new XMLHttpRequest();
    var currFrom = document.getElementsByName('from')[0].value;
    var currTo = document.getElementsByName('to')[0].value;
    var amnt = document.getElementsByName('amnt')[0].value;
    xhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            console.log(this.responseText);
            document.getElementById("resultbox").value = this.responseText;
        }
    };
    xhttp.open("GET", "index.php?from="+currFrom+"&to="+currTo+"&amnt="+amnt+"&format="+format,true);
    xhttp.send();

}
