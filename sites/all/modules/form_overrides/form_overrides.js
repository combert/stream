$(document).ready(function() {
	refreshSlide();
});

/*
 *	Function to add button element to each multigroup on the page.
 *	It also changes the text color of the button to match the aktiv state.
 *	And it refreshes itselv every three seconds.
 */
function refreshSlide()
{
	var x = 0;
	while (document.getElementById('edit-group-slidecontent-'+x+'-field-slide-start-value-wrapper'))
	{
		if (!document.getElementById('insertTime'+x))
		{
			var newButton = document.createElement('input');
			newButton.setAttribute('id', 'insertTime'+x);
			newButton.setAttribute('type','button');
			newButton.setAttribute('value','Time & Save');
			newButton.setAttribute('class','time-save-button');
			newButton.setAttribute('onClick','textClick('+x+')');
			document.getElementById('edit-group-slidecontent-'+x+'-field-slide-start-value-wrapper').parentNode.appendChild(newButton);
		}

		if (document.getElementById('edit-group-slidecontent-' + x + '-field-slide-aktiv-value').value == 1)
		{
			document.getElementById('insertTime'+x).style.color = '#0c7c04';
		}
		else
		{
			document.getElementById('insertTime'+x).style.color = '#b41414';
		}			
		x++;	
	}	
	var t = setTimeout('refreshSlide()', 3000);
}

/*
 *	Function to add clockstring to input field, set aktiv to true and activate 
 *	the save/edit button to save the form.
 */
function textClick(g)
{
	document.getElementById('edit-group-slidecontent-' + g + '-field-slide-start-value').value = getClock();
	document.getElementById('edit-group-slidecontent-' + g + '-field-slide-aktiv-value').value = 1;
	//document.getElementById('edit-group-slide-' + g + '-field-active-value-1').checked = true;
	//document.getElementById('edit-save-edit').click();
}

/*
 *	Function to generate the to clock string
 */
function getClock()
{
	var today=new Date();
	var h=today.getHours();
	var m=today.getMinutes();
	var s=today.getSeconds();
	h=checkTime(h);
	m=checkTime(m);
	s=checkTime(s);
	return (h + ':' + m + ':' + s + ':00');
}

/*
 *	Function to add 0 to h:m:s that are below 10
 */
function checkTime(i)
{
	if (i < 10)
	{
		i = '0' + i;
	}
	return i;
}