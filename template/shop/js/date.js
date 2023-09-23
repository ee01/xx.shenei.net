
<!-- 
var Selected_Month; 
var Selected_Year; 
var Current_Date = new Date(); 
var Current_Month = Current_Date.getMonth(); 

var Days_in_Month = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
var Month_Label = new Array('1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'); 

var Current_Year = Current_Date.getYear();
Current_Year = (Current_Year<1900?(1900+Current_Year):Current_Year); 
var Today = Current_Date.getDate(); 

function Header(Year, Month) { 

if (Month == 1) { 
if ((Year % 400 == 0) || ((Year % 4 == 0) && (Year % 100 != 0))) { 
Days_in_Month[1] = 29; 
} 
} 
var Header_String = Year + '年' + Month_Label[Month]; 
return Header_String; 
} 

function Make_Calendar(Year, Month) { 
var First_Date = new Date(Year, Month, 1); 
var Heading = Header(Year, Month); 
var First_Day = First_Date.getDay() + 1; 
if (((Days_in_Month[Month] == 31) && (First_Day >= 6)) || 
((Days_in_Month[Month] == 30) && (First_Day == 7))) { 
var Rows = 6; 
} 
else if ((Days_in_Month[Month] == 28) && (First_Day == 1)) { 
var Rows = 4; 
} 
else { 
var Rows = 5; 
} 

var HTML_String = '<table width="100%" border="0" cellpadding="2" cellspacing="1">'; 
HTML_String += '<tr><td colspan="7"><a href="javascript:void(0)" onClick=Skip("-") title="上月">&laquo;</a> ' + Heading + '</font> <a href="javascript:void(0)" onClick=Skip("+") title="下月">&raquo;</a></td></tr>'; 
HTML_String += '<tr><td>日</td><td>一</td><td>二</td><td>三</td>'; 
HTML_String += '<td>四</td><td>五</td><td>六</td></tr>';

var Day_Counter = 1; 
var Loop_Counter = 1; 
for (var j = 1; j <= Rows; j++) { 
HTML_String += '<tr>'; 
for (var i = 1; i < 8; i++) { 
if ((Loop_Counter >= First_Day) && (Day_Counter <= Days_in_Month[Month])) { 
if ((Day_Counter == Today) && (Year == Current_Year) && (Month == Current_Month)) { 
HTML_String += '<td class="today">' + Day_Counter + '</td>'; 
} 
else { 
HTML_String += '<td>' + Day_Counter + '</td>'; 
} 
Day_Counter++; 
} 
else { 
HTML_String += '<td> </td>'; 
} 
Loop_Counter++; 
} 
HTML_String += '</tr>'; 
} 
HTML_String += '</table>'; 
document.getElementById('Calendar').innerHTML = HTML_String; 
} 
function Check_Nums() { 
if ((event.keyCode < 48) || (event.keyCode > 57)) { 
return false; 
} 
} 
function Defaults() { 
var Mid_Screen = Math.round(document.body.clientWidth / 2); 
Selected_Month = Current_Month; 
Selected_Year = Current_Year; 
Make_Calendar(Current_Year, Current_Month); 
} 
function Skip(Direction) { 
if (Direction == '+') { 
if (Selected_Month == 11) { 
Selected_Month = 0; 
Selected_Year++; 
} 
else { 
Selected_Month++; 
} 
} 
else { 
if (Selected_Month == 0) { 
Selected_Month = 11; 
Selected_Year--; 
} 
else { 
Selected_Month--; 
} 
} 
Make_Calendar(Selected_Year, Selected_Month); 
} // JavaScript Document