<?php
	include "./Apps/objek.php";
	include "./Apps/ClusteringKMedoid.php";
	session_start();
	$objek = $_SESSION['objekan'];
	$CentroidCluster = $_SESSION['centroclus'];
	$objekraw = $_SESSION['objekraw'];

	// echo "<script>console.log(" . count($objek[1]->data) . ")</script>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> 
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script>
  $(function () {

    // Give the points a 3D feel by adding a radial gradient
    Highcharts.getOptions().colors = $.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.4,
                cy: 0.3,
                r: 0.5
            },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.2).get('rgb')]
            ]
        };
    });

    // Set up the chart
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            margin: 100,
            type: 'scatter',
            options3d: {
                enabled: true,
                alpha: 10,
                beta: 30,
                depth: 250,
                viewDistance: 5,
                fitToPlot: false,
                frame: {
                    bottom: { size: 1, color: 'rgba(0,0,0,0.02)' },
                    back: { size: 1, color: 'rgba(0,0,0,0.04)' },
                    side: { size: 1, color: 'rgba(0,0,0,0.06)' }
                }
            }
        },
        title: {
            text: '3D Scatter Plot'
        },
        subtitle: {
            text: 'Click and drag the plot area to rotate in space'
        },
        plotOptions: {
            scatter: {
                width: 30,
                height: 30,
                depth: 30
            }
        },
        yAxis: {
            title: {
              text: 'Y Axis'
            } 
        },
        xAxis: {
            gridLineWidth: 1
        },
        zAxis: {
            showFirstLabel: false
        },
        legend: {
            enabled: true
        },
        series: [
        <?php
          for ($j=0;$j<count($CentroidCluster);$j++){
            $asemsss = $j+1;
            echo "{name: 'Cluster " . $asemsss . "', data: [" ;
            for ($k=0; $k<count($objek); $k++) {
              if ($j == $objek[$k]->getCluster()) {
                echo "[" . $objekraw[$k]->data[7] . ", " . $objekraw[$k]->data[8] . ", " . $objekraw[$k]->data[1] .  "], ";
              }
            }
            echo "]}, ";
          }
        ?>
        ]
    });


    // Add mouse events for rotation
    $(chart.container).bind('mousedown.hc touchstart.hc', function (eStart) {
        eStart = chart.pointer.normalize(eStart);

        var posX = eStart.pageX,
            posY = eStart.pageY,
            alpha = chart.options.chart.options3d.alpha,
            beta = chart.options.chart.options3d.beta,
            newAlpha,
            newBeta,
            sensitivity = 5; // lower is more sensitive

        $(document).bind({
            'mousemove.hc touchdrag.hc': function (e) {
                // Run beta
                newBeta = beta + (posX - e.pageX) / sensitivity;
                chart.options.chart.options3d.beta = newBeta;

                // Run alpha
                newAlpha = alpha + (e.pageY - posY) / sensitivity;
                chart.options.chart.options3d.alpha = newAlpha;

                chart.redraw(false);
            },
            'mouseup touchend': function () {
                $(document).unbind('.hc');
            }
        });
    });
});
</script>



<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable
            ([
                <?php

                    echo "['Published Relative Performance',";
                    for ($j=0;$j<count($CentroidCluster)-1;$j++){
                        echo " 'Cluster " . ($j+1) . "'," ;
                    }
                    echo " 'Cluster " . count($CentroidCluster) . "'], ";


                    for ($i=0;$i<count($objek)-1;$i++) {

                        echo "[{v:" . $objekraw[$i]->data[7] . ", f:'" . $objekraw[$i]->data[0] . " (MYCT)'}, ";
                        for ($j=0;$j<count($CentroidCluster)-1;$j++){
                            if ($j == $objek[$i]->getCluster()) {
                                // echo "<td>X</td>";
                                $kampret = 8;
                                 echo "{v:" .  $objekraw[$i]->data[8] . ", f:'" . $objekraw[$i]->data[0] . " (ERP)'}, ";
                              } else {
                                echo "null, ";
                              }  
                        }
                        if (count($CentroidCluster)-1 == $objek[$i]->getCluster()) {
                            // echo "<td>X</td>";
                            echo "{v:" .  $objekraw[$i]->data[8] . ", f:'" . $objekraw[$i]->data[0] . " (ERP)'}], ";
                        } else{
                            echo "null], ";
                        }  
                        
                    }

                    for ($i=count($objek)-1;$i<count($objek);$i++) {
                        echo "[{v:" . $objekraw[$i]->data[7] . ", f:'" . $objekraw[$i]->data[0] . " (MYCT)'}, ";
                        for ($j=0;$j<count($CentroidCluster)-1;$j++){
                            if ($j == $objek[$i]->getCluster()) {
                                // echo "<td>X</td>";
                                  echo "{v:" .  $objekraw[$i]->data[8] . ", f:'" . $objekraw[$i]->data[0] . " (ERP)'}, ";
                              } else {
                                echo "null, ";
                              }  
                        }
                        if (count($CentroidCluster)-1 == $objek[$i]->getCluster()) {
                            // echo "<td>X</td>";
                            echo "{v:" .  $objekraw[$i]->data[8] . ", f:'" . $objekraw[$i]->data[0] . " (ERP)'}]";
                        } else{
                            echo "null]";
                        }  


                    }


                ?>
        ]);

        var options = {
          legend: 'none',
          pointSize: 15,
          series: {
          		<?php 
          			$kampretosa = count($CentroidCluster);
          			if($kampretosa == 2) {
          				echo "0: { pointShape: 'circle' },
                1: { pointShape: 'triangle' }";
          			} else {
          				if($kampretosa == 3) {
          					echo "0: { pointShape: 'circle' },
                1: { pointShape: 'triangle' },
                2: { pointShape: 'square' }";
          				} else {
          					if($kampretosa == 4) {
          						echo "0: { pointShape: 'circle' },
                1: { pointShape: 'triangle' },
                2: { pointShape: 'square' },
                3: { pointShape: 'diamond' }";
          					} else {
          						if($kampretosa == 5) {
          							echo "0: { pointShape: 'circle' },
                1: { pointShape: 'triangle' },
                2: { pointShape: 'square' },
                3: { pointShape: 'diamond' },
                4: { pointShape: 'star' }";
          						} else {
          							if($kampretosa == 6) {
          								echo "0: { pointShape: 'circle' },
                1: { pointShape: 'triangle' },
                2: { pointShape: 'square' },
                3: { pointShape: 'diamond' },
                4: { pointShape: 'star' },
                5: { pointShape: 'polygon' }";
          							}
          						}
          					}
          				}
          			}
          		?>
                
            }
        };

        var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>

  </head>
  <body>
    <div id="container" style="height: 720px"></div>
    <div id="chart_div" style="width: 1280px; height: 720px;"></div>
  </body>
</html>