<?php
/**
 * Extensión para la vista metatags que adiciona el código para agregar flot
 *
 * @package Elggelgg_statistics
 * @author Diego Ramirez <dramirezaragon@gmail.com>
 * @copyright (C) Keetup 2009
 * @link http://keetup.com/
 */
?>
<!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo $vars["url"];?>mod/elgg_statistics/vendors/flot/excanvas.min.js"></script><![endif]-->
<script
	type="text/javascript"
	src="<?php echo $vars["url"];?>mod/elgg_statistics/vendors/flot/jquery.flot.js"></script>



<script type="text/javascript">
/* Flot helpers functions */
var long_labels = null;

/**
 * Show a tooltip in the indicated position
 * @param x X position
 * @param y Y position
 * @param contents Tooltip content
 */
function showTooltip(x, y, contents) {
  jQuery('<div id="tooltip">' + contents + '</div>').css( {
        position: 'absolute',
        display: 'none',
        top: y + 5,
        left: x + 5,
        border: '1px solid #fdd',
        padding: '2px',
        'background-color': '#fee',
        opacity: 0.80
    }).appendTo("body").fadeIn(200);
}

/**
 * Handle the hoover event over a flot graph
 * @param event Event triggered
 * @param pos Position object
 * @param item Selected item object
 */
function plot_hoover(event, pos, item){
  jQuery("#x").text(pos.x.toFixed(2));
  jQuery("#y").text(pos.y.toFixed(2));

  if (item) {
      if (previousPoint != item.datapoint) {
          previousPoint = item.datapoint;

          jQuery("#tooltip").remove();
          var x = item.datapoint[0],
              y = item.datapoint[1];
          showTooltip(item.pageX, item.pageY,
                      item.series.label +" " +long_labels[x] + " (" + y +")");
      }
  }
  else {
	  jQuery("#tooltip").remove();
      previousPoint = null;
  }
}

</script>
