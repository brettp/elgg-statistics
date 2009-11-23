<?php
	//Type of graphic pie
	$type_pie = $vars['type_pie'];
	if (!$type_pie) {
		$type_pie = 'p3';	
	}
	//Size for show the graphic
	$size = $vars['size'];
	if (!$size) {
		$size = '350x200';	
	}
	//Data
	$data = $vars['data'];
	if ($data && sizeof($data) > 0) {
		global $TOTAL;
		$TOTAL = array_sum($data);
		$percents = array_map(create_function(
		            '$value',
		            '
		            	global $TOTAL;
		       			return (int) $value*100/$TOTAL;
		       		'
		    ), array_values($data));
		if ($percents) {
			$values = implode(',', $percents);
			$labels = implode('|', array_keys($data));
			echo "<img src='http://chart.apis.google.com/chart?cht=$type_pie&chd=t:$values&chs=$size&chl=$labels' />";
		}    
	}
?>
	