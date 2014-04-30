
setInterval(function()
{
    _  = (x=new Date()).getDate()+"."+(x.getMonth()+1)+"."+x.getFullYear();
    _ += " - "+(((h=x.getHours())<10)?"0"+h:h)+":"+(((m=x.getMinutes())<10)?"0"+m:m)+":"+(((s=x.getSeconds())<10)?"0"+s:s);
    document.getElementById("curr_time").innerHTML = _;
}, 1000);
