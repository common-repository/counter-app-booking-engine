function initBookingForm(id) {
	var holder = document.getElementById('counter-booking-form-holder-' + id);
	var checkInField = document.getElementById('check-in-' + id);
	var checkOutField = document.getElementById('check-out-' + id);
	
	var locationField = holder.querySelector('.location-holder'); 
	var locationFieldInput = locationField.querySelector('#location-' + id);
	var locationFieldValue = locationField.querySelector('#location-value-' + id);
	var locationFieldSelector = locationField.querySelector('.location-selector');
	var locationFieldSelectorOptions = locationFieldSelector.querySelectorAll('.location-selector-row');

	var guestsField = holder.querySelector('.guests-holder');
	var guestsFieldInput = guestsField.querySelector('input');
	var guestsFieldSelector = guestsField.querySelector('.guests-selector');
	var guestsFieldValue = guestsFieldSelector.querySelector('.value');
	var guestsFieldMinus = guestsFieldSelector.querySelector('.minus');
	var guestsFieldPlus = guestsFieldSelector.querySelector('.plus');

	var addBreakpointClasses = function() {
		var breakpoints = {
			width_l: 989,
			width_m: 767,
			width_s: 479,
			width_xs: 0
		};
		var classes = Object.keys(breakpoints)
		var className = holder.className;
		
		for (var i in classes) {
			className = className.replace(classes[i], '');
		}

		for (var i in breakpoints) {
			if (holder.offsetWidth > breakpoints[i]) {
				className = className + ' ' + i;
				break;
			}
		}

		holder.className = className.replace(/\s\s+/g, ' ');
	}

	window.addEventListener('load', addBreakpointClasses);
	window.addEventListener('resize', addBreakpointClasses);

	const datePicker = new DateRangePicker(document.getElementById('starting-form-' + id), {
  	inputs: [checkInField, checkOutField],
  	autohide: true,
  	orientation: 'bottom',
  	format: 'M d',
  	minDate: new Date()
	});

	document.getElementById('starting-form-button-' + id).onclick = function() {
		var dates = datePicker.getDates('yyyy-mm-dd');
		var checkIn = dates[0];
		var checkOut = dates[1];

		if (!checkIn) {
			alert('Please select the Check in date');
		} else if (!checkOut) {
			alert('Please select the Check out date');
		} else if (checkIn === checkOut) {
			alert('The Check in date and the Check out date must be different');
		} else {
			document.getElementById('booking-modal-' + id).style.left = '0px'; 
			var iframeUrl = locationFieldValue.value + '?checkIn=' + checkIn + '&checkOut=' + checkOut; 
			document.getElementById('booking-form-' + id).src = iframeUrl;
		}
	};

	document.getElementById('booking-modal-close-' + id).onclick = function() {
		document.getElementById('booking-modal-' + id).style.left = '-100%';
	};

	document.addEventListener('click', function(event) {
 		locationFieldSelector.className = locationFieldSelector.className.replace('active', '');
 		guestsFieldSelector.className = guestsFieldSelector.className.replace('active', '');
	});

	locationFieldInput.onfocus = function() {
		locationFieldSelector.className = locationFieldSelector.className + ' active';
		guestsFieldSelector.className = guestsFieldSelector.className.replace('active', '');
	};
	locationFieldInput.onclick = function(e) {
		e.stopPropagation();
	};
	for (var i in locationFieldSelectorOptions) {
		locationFieldSelectorOptions[i].onclick = function() {
			locationFieldInput.value = this.querySelector('.lbl').innerHTML;
			locationFieldValue.value = this.getAttribute('data-value');
		};
	}

	guestsFieldInput.onfocus = function() {
		guestsFieldSelector.className = guestsFieldSelector.className + ' active';
		locationFieldSelector.className = locationFieldSelector.className.replace('active', '');
	};
	guestsFieldInput.onclick = function(e) {
		e.stopPropagation();
	};
	guestsFieldSelector.onclick = function(e) {
		e.stopPropagation();
	};
	guestsFieldMinus.onclick = function() {
		var newValue = parseInt(guestsFieldInput.value) - 1;

		if (newValue >= 1) {
			guestsFieldInput.value = newValue;
			guestsFieldValue.innerHTML = newValue;
		}
	};
	guestsFieldPlus.onclick = function() {
		var newValue = parseInt(guestsFieldInput.value) + 1;

		if (newValue < 100) {
			guestsFieldInput.value = newValue;
			guestsFieldValue.innerHTML = newValue;
		}
	};
}

var bookingForms = document.querySelectorAll('.counter-booking-form-holder');

for (var i in bookingForms) {
	if (typeof bookingForms[i] === 'object') {
		var id = bookingForms[i].id.replace('counter-booking-form-holder-', '');
		initBookingForm(id);
	}
}
