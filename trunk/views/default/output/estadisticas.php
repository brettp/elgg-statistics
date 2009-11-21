<?php
/**
 * Vista que muestra la gráfica de estadisticas de un set de datos especificado
 *
 * Esta vista puede recibir como parámetros:
 * <ul>
 * <li>internalname: Identificador de la gráfica</li>
 * <li>width: Ancho de la gráfica (default: 600)</li>
 * <li>heigh: Altura de la gráfica (default:250)</li>
 * <li>background: Color de fondo de la gráfica (default: #fff)</li>
 * <li>max: Valor máximo a mostrar. Si el valor máximo especificado es menor que el valor máximo de los datos, este es ignorado (default: 10)</li>
 * <li>data: Arreglo con los datos a ser graficados. Este arreglo tiene el siguiente formato:<br>
 * 			array("Etiqueta"=>array("TODAY"=>n,"ME"=>n,"FRIENDS"=>n))</li>
 * </ul>
 *
 * @package Elggestadisticas
 * @author Diego Ramirez <dramirezaragon@gmail.com>
 * @copyright (C) Keetup 2009
 * @link http://keetup.com/
 */




$id = (!empty($vars["internalname"]))?$vars["internalname"]:"graph";
$width = (!empty($vars["width"]))?$vars["width"]:"600";
$height = (!empty($vars["height"]))?$vars["height"]:"250";
$background = (!empty($vars["background"]))?"'${vars['background']}'":"'#fff'";

$i = 1;
$max_value = 0;
$labels = array();
$ticks = array();
$values_today = array();
$values_me = array();
$values_friends = array();

if(!empty($vars["data"])){
    foreach($vars["data"] as $label => $point_data){
        $ticks[]=($i+0.5);
        $labels[]="'$label'";
        $values_today[]="[".($i+0.2).",${point_data["TODAY"]}]";
        $max_value = max($point_data["TODAY"],$max_value);
        $values_me[]="[".($i+0.5).",${point_data["ME"]}]";
        $max_value = max($point_data["ME"],$max_value);
        $values_friends[]="[".($i+0.8).",${point_data["FRIENDS"]}]";
        $max_value = max($point_data["FRIENDS"],$max_value);
        $i++;
    }
}

$max = (!empty($vars["max"]))?$vars["max"]:10;
$max = ($max<$max_value)?"":",max:$max";

$labels = implode(",",$labels);
$ticks = implode(",",$ticks);
$values_today = implode(",",$values_today);
$values_me = implode(",",$values_me);
$values_friends = implode(",",$values_friends);



?>
<div align="center">
<div id="<?php echo $id?>"
	style="width: <?php echo $width;?>px; height: <?php echo $height;?>px; display: block">&nbsp;</div>
</div>
<script type="text/javascript">
  jQuery(document).ready(function(){
	  var labels = [<?php echo $labels?>];
	  var ticks_values = [<?php echo $ticks?>];
	  var values_today = [<?php echo $values_today?>];
	  var values_me = [<?php echo $values_me?>];
	  var values_friends = [<?php echo $values_friends?>];
	  var data = [{data:values_today,label:"<?php echo elgg_echo("estadisticas:today")?>",bars: { show: true,barWidth: 0.25, fill: 0.9,align:'center' }},
	              {data:values_me,label:"<?php echo elgg_echo("estadisticas:me")?>",bars: { show: true,barWidth: 0.25, fill: 0.9,align:'center' }},
	              {data:values_friends,label:"<?php echo elgg_echo("estadisticas:friends")?>",bars: { show: true,barWidth: 0.25, fill: 0.9,align:'center' }}
	  			 ];

	  jQuery.plot(jQuery("#<?php echo $id;?>"),
			  	  data,
			  	  {
					legend: { position: 'ne', noColumns:3, ymargin:12,xmargin:5 },
					yaxis: {autoscaleMargin:0.08,min:0,tickDecimals:0,tickSize:2<?php echo $max;?>},
		  			xaxis: { tickFormatter: function (v, axis) { return labels[Math.floor(v)-1]; },
			  				autoscaleMargin:0.1,
			  				ticks: ticks_values
			  				},
			  		grid: {backgroundColor:<?php echo $background;?>}
			  	  });
  });
</script>