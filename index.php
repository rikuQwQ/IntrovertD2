<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="glDatePicker-2.0/styles/glDatePicker.default.css" rel="stylesheet" type="text/css">
	<title>Calendar</title>
</head>

<body>
	<?php
	include('getMonthWorkload.php');
	$monthWorkload = json_encode(getMonthWorkload());
	echo "<script type='text/javascript'>var monthWorkload = $monthWorkload;</script>";
	?>

	<input type="text" id="mydate" gldp-id="mydate" />
	<div gldp-el="mydate" style="width:400px; height:300px; position:absolute; top:70px; left:100px;">
	</div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="glDatePicker-2.0/glDatePicker.min.js"></script>
	<script type="text/javascript">

		let maxLeadsPerDay = prompt("Введите максимальное количество сделок в день", 1);
		let disabledDays = [];
		let selectableDateRange = [];
		if (monthWorkload.length != 0) {
			monthWorkload.forEach(element => {
				if (element['count'] >= maxLeadsPerDay) {
					disabledDays.push(new Date(element['date']));
				}
			});
			disabledDays.sort(function (a, b) {
				return a - b;
			});
			
			selectableDateRange.push({
				from: new Date(),
				to: new Date(disabledDays[0].setDate(disabledDays[0].getDate() - 1))
			});

			for (let i = 0; i < disabledDays.length - 1; i++) {
				selectableDateRange.push({
					from: new Date(disabledDays[i].setDate(disabledDays[i].getDate() + 2)),
					to: new Date(disabledDays[i + 1].setDate(disabledDays[i + 1].getDate() - 1))
				});
			}

			selectableDateRange.push({
				from: new Date(),
				to: new Date(new Date().setDate(new Date().getDate() + 30))
			});
		}
		else{
			selectableDateRange.push({
				from: new Date(),
				to: new Date(new Date().setDate(new Date().getDate() + 30))
			});
		}

		$(window).load(function () {
			$('input').glDatePicker();
		});
		$('#mydate').glDatePicker({
			showAlways: true,
			allowMonthSelect: false,
			allowYearSelect: false,
			dowOffset: 1,
			selectedDate: new Date(),
			selectableDateRange: selectableDateRange,
		});
		
	</script>
</body>
</html>
