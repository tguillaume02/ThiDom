	<?php	

	include_once('Security.php');

	?>

	<div id="ChartTemp" style="margin: 1% 2% 0 2%;"> 

		<?php

		$nb_device = -1;

		$nb_year = 0;

		$tb_device  ;

		$RS = execute_sql("SELECT lieux FROM Temperature where lieux not like '%_old%' GROUP BY lieux");

		while ($data_lieux = $RS->fetch_array(MYSQLI_ASSOC))

		{

			$nb_device +=1;

			$lieux = $data_lieux['lieux'];

			$tb_device[$nb_device] = $lieux;

			echo "<div id='ExtremeTemp".$lieux."' class='ui-btn ui-btn-b ui-btn-corner-all'><table style='width:100%'><tr><td><div id='ExtremeMinMax".$lieux."'></div></td><td><input id='CheckBox".$lieux."'' type='checkbox' checked></td></tr></table></div>";

			echo "<div id='Temp".$lieux."'></div><br>";

		}

		

		/*$RS_Year = execute_sql(" select * from (SELECT EXTRACT( YEAR FROM DATE ) as year  FROM  `Temperature` ) as t   GROUP BY t.year") ;	

		while ($data_year = $RS_Year->fetch_array(MYSQLI_ASSOC))

		{	

			$nb_year +=1;		

			$tb_year[$nb_year] = $data_year['year'];	

			

		}*/

		?>

	</div>


	<script>

		<?php //include ("temperaturedata.php"); ?>

		var nb_device = "<?php echo($nb_device)?>";

		var tab = new Array("<?php echo implode('","',$tb_device);?>");

		var temperatureData;

		var seriesOptions = [];



		$(function() {

			Highcharts.setOptions({

				global : {

					useUTC:false

				}

			});  



				//for(var i= 0; i < tab.length; i++)

//{ 



	<?php

	$RS = execute_sql("SELECT lieux FROM Temperature GROUP BY lieux");

	$nb =0;

	$interval ="";

	$YearNow = date("Y");

	

	while ($data_lieux = $RS->fetch_array(MYSQLI_ASSOC))

	{

		$nb_result = $RS->num_rows;

		$nb = $nb + 1;

		$z = 0;

					//foreach ($tb_year as $value) {

		$requete = execute_sql("SELECT @temp_year:=EXTRACT(YEAR FROM date) AS temp_year, temp, UNIX_TIMESTAMP(replace(date,EXTRACT(YEAR FROM date),$YearNow)) as date FROM Temperature where lieux='".$data_lieux['lieux']."'ORDER BY Temperature.date;") ;

//						$requete = $mysqli->query("SELECT UNIX_TIMESTAMP(replace(date,$value,$YearNow)) as date,temp,lieux FROM Temperature where Temperature.date like'%".$value."%' and lieux ='".$data_lieux['lieux']."' order by date")or die('Erreur SQL !<br>') ;

		

						//echo "var temperatureData = [";

		$i = 0;

		$interval ="";

		$json ="";

		while ($data = $requete->fetch_array(MYSQLI_ASSOC))

		{	
			set_time_limit(10);
			$temp_year = $data['temp_year'];



			$Bisvisible = "true";

			/*if ($temp_year <= $YearNow-2)

			{

				$Bisvisible = "false";

			}

			else

			{

				$Bisvisible = "true";

			}*/

			ob_flush();
			flush();

			if ($json != "" and $temp_year != $temp_year_old)

			{

				echo "temperatureData = [".$json."];";

				?>						

				seriesOptions[<?php echo $z?>] = {

					name: "Temperature<?php echo $data_lieux['lieux']."".$temp_year_old?>",

					data: temperatureData

					,visible: <?php echo $Bisvisible ?>

				};									

				<?php 

				$json ="";

				$z  = $z+1;

			}

			$i = $i+1;

			if ($i == mysqli_num_rows($requete))

			{

									//echo "[".$data['date']."000,".$data['temp']."]";	

				$json .= "[".$data['date']."000,".$data['temp']."]";	

				echo "temperatureData = [".$json."];";

				?>						

				seriesOptions[<?php echo $z?>] = {

					name: "Temperature<?php echo $data_lieux['lieux']."".$temp_year?>",

					data: temperatureData

					,visible: <?php echo $Bisvisible ?>

				};

				

				<?php 

			}

			else

			{

				$json .= "[".$data['date']."000,".$data['temp']."],";	

			}

			$temp_year_old = $temp_year;
		}

		$json  = "";

				//		echo '];';

		

		

		

						//$z  = $z+1;

					//}

					//$z = $z -1;

		$HeadInterval = "setInterval(function() {";

			

			$interval = "var request".$nb." = $.ajax({

				type: 'POST',

				dataType: 'json',

				url: 'action_ajax.php',

				data: {

					mode: 'temp_graph',

					lieux: '".$data_lieux['lieux']."',

					id:'',

					act:'',

					property: ''

				}

			});



	request".$nb.".done(function (data) {											

		$.each(data, function (index, item) {

			var chart = $('#Temp".$data_lieux['lieux']."').highcharts();

			date = parseInt(item.date);

			temp = parseFloat(item.temp);

			chart.series[".$z."].addPoint([date,temp],true,true,true);

		});

	

	if ($('#CheckBox".$data_lieux['lieux']."').is(':checked'))

	{

		var chart = $('#Temp".$data_lieux['lieux']."').highcharts();

		chart.xAxis[0].setExtremes(

			new Date().getTime()- 48 * 3600 * 1000,

			new Date().getTime() +1  * 3600 *1000

			);

}

});						

	

	request".$nb.".fail(function (jqXHR, textStatus, errorThrown) 

	{

		/*alert(textStatus);*/

	});";

	

	$BottomInterval = "}, 60000);";

	

	?>

	

	title = "Temperature <?php echo $data_lieux['lieux'] ?>";

	temp_lieux = "Temp<?php echo $data_lieux['lieux'] ?>";

	data_temp= "temperature<?php echo $data_lieux['lieux'] ?>";

	nb_result = "<?php echo $nb_result ?>";

	nb = "<?php echo $nb ?>";

	

	

			   // Create a timer

			   var start = + new Date();

			   

					// Create the chart	

					$('#'+temp_lieux).highcharts('StockChart', {

						chart: {

							type: 'spline',

							renderTo: temp_lieux,

							events: {

								load: function(chart) {

									$('#ExtremeMinMax<?php echo $data_lieux['lieux'] ?>').html("<span class='min'>Min: " + this.yAxis[0].getExtremes().dataMin.toFixed(2) + "</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class='max'>" + "Max: "+ this.yAxis[0].getExtremes().dataMax.toFixed(2)+"</span>");									

									this.setTitle(null, {

										text: 'Built chart at '+ (new Date() - start) +'ms'

									});

									

									

									

									<?php

										/*if ($nb == $nb_result)

										{*/

											

											echo $HeadInterval;

											echo $interval;

											echo $BottomInterval;										

											/*}*/

											?>

											

										},			

										redraw: function (event) {

											$('#ExtremeMinMax<?php echo $data_lieux['lieux'] ?>').html("<span class='min'>Min: " + this.yAxis[0].getExtremes().dataMin.toFixed(2) + "</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class='max'>" + "Max: "+ this.yAxis[0].getExtremes().dataMax.toFixed(2)+"</span>");     

											

										},					

									},

									zoomType: false

								},

								

								rangeSelector: {

									buttons : [{

										type : 'hour',

										count : 3,

										text : '3h'

									},{

										type : 'hour',

										count : 12,

										text : '12h'

									},{

										type : 'hour',

										count : 24,

										text : '24'

									},{

										type: 'day',

										count: 3,

										text: '3d'

									},{

										type : 'week',

										count : 1,

										text : '1s'

									},{

										type : 'week',

										count : 2,

										text : '2s'

									},{

										type : 'week',

										count : 3,

										text : '3s'

									},{

										type : 'month',

										count : 1,

										text : '1m'

									},{

										type : 'month',

										count : 2,

										text : '2m'

									},{

										type : 'all',

										count : 1,

										text : 'All'

									}],

									selected : 3,

									inputEnabled : false

								},	

								legend: {

									enabled: true

								},

								title: {

									text: title

								},

								xAxis: {

									min: new Date().getTime()- 48 * 3600 * 1000,

									max: new Date().getTime() +1  * 3600 *1000,

									ordinal: false

								},

						/*

						xAxis: {

							maxZoom: 31 * 24 * 3600000 // 31 days

						}, */

						yAxis: {

							title: {

								text: 'Temperature (°C )'

							}

						},

						

						series: 

						seriesOptions,

							//lineWidth : 3,

							pointInterval: 60*10,

							tooltip: {

								crosshairs: true,

								shared: true,

								valueDecimals: 1,

								valueSuffix: ' °C'

							}

							

						});

	seriesOptions = [];

	<?php } ?>

});

	

</Script>