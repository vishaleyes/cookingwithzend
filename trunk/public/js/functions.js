function convertTemp()
{
	var temp = new Number($('#temperature').val());
	
	alert(isNaN(temp));
	if (isNaN(temp))
		alert('Temperature must be a number');
		
	var convert = $('#convert').val();
	
	switch(convert)
	{
		case 'F':
			converted = (9/5)*temp+32;
			from = 'Celcius';
			to = 'Fahrenheit';
			break;
		case 'C':
			converted = (5/9)*(temp-32);
			from = 'Fahrenheit';
			to = 'Celcius';
			break;
	}
	$('#tempOutputEntry').html(temp.toString());
	$('#tempOutputFrom').html(from);
	$('#tempOutputTo').html(to);
	$('#tempOutputResult').html(converted.toFixed(2));
	$('#tempOutput').toggleClass('hide');
	return true;
}