function startTime() {
    var today = new Date();
    var d = today.getDay();
    var a = today.getDate();
    var o = today.getMonth();
    var y = today.getFullYear();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('date').innerHTML = days(d) + ", " + a + " " + months(o) + " " + y;
    document.getElementById('clock').innerHTML = h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
}

function checkTime(i) {
    if (i < 10) {i = "0" + i};
    return i;
}

function days(numday) {
	var daystring = "";
	switch (numday) {
		case 0: daystring = "Sun"; break;
		case 1: daystring = "Mon"; break;
		case 2: daystring = "Tue"; break;
		case 3: daystring = "Wed"; break;
		case 4: daystring = "Thu"; break;
		case 5: daystring = "Fri"; break;
		case 6: daystring = "Sat"; break;
		default: daystring = ""; break;
	}
	return daystring;
}

function months(nummonth) {
	var monthstring = "";
	switch (nummonth) {
		case 0: monthstring = "Jan"; break;
		case 1: monthstring = "Feb"; break;
		case 2: monthstring = "Mar"; break;
		case 3: monthstring = "Apr"; break;
		case 4: monthstring = "May"; break;
		case 5: monthstring = "Jun"; break;
		case 6: monthstring = "Jul"; break;
		case 7: monthstring = "Aug"; break;
		case 8: monthstring = "Sept"; break;
		case 9: monthstring = "Oct"; break;
		case 10: monthstring = "Nov"; break;
		case 11: monthstring = "Dec"; break;
		default: monthstring = ""; break;
	}
	return monthstring;
}