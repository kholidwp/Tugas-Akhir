<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Chart</title>
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/stock/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
  </head>
  <body>

    <div id="container" style="min-width: 310px; height: 500px; margin: 0 auto"></div>

<?php
include 'koneksi.php';
// ambil data series stock
$conn5 = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
// set the PDO error mode to exception
$conn5->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql6 = $conn5->prepare("SELECT csv.Date,
	   csv.Open,
       csv.High,
       csv.Low,
       csv.Close,
       IFNULL (bband.lBand,0),
       IFNULL (bband.rerata,0),
       IFNULL (bband.uBand,0),
	   IFNULL (macd.Macdline,0),
	   IFNULL (macd.Signalline,0),
       IFNULL (macd.Histogram,0),
	   IFNULL (macd.Histogramup,0),
	   IFNULL (macd.Histogramdown,0)
  FROM csv
 INNER JOIN bband ON bband.Date = csv.Date
 INNER JOIN macd ON macd.Date = csv.Date
 WHERE csv.Date BETWEEN NOW() - INTERVAL 420 DAY AND NOW()
 AND csv.Close != 0
 ORDER BY csv.Date");
$sql6->execute();

foreach ($sql6 as $row) {
  $timestamp = strtotime($row['Date']);
  $timestamp *=1000;
  $timestamp +=86400000;
  $open = $row['Open'];
  $high = $row['High'];
  $low = $row['Low'];
  $close = $row['Close'];
  $data[] = "[$timestamp, $open, $high, $low, $close]";
  $upBand = $row['IFNULL (bband.uBand,0)'];
  $lowBand = $row['IFNULL (bband.lBand,0)'];
  $rerata = $row['IFNULL (bband.rerata,0)'];
  $macdline = $row['IFNULL (macd.Macdline,0)'];
  $signalline = $row['IFNULL (macd.Signalline,0)'];
  $histogram= $row['IFNULL (macd.Histogram,0)'];
  $histogramup= $row['IFNULL (macd.Histogramup,0)'];
  $histogramdown= $row['IFNULL (macd.Histogramdown,0)'];
  $bband[] = "[$timestamp, $upBand, $lowBand]";
  $mband[] = "[$timestamp, $rerata]";
  $linemacdline[] = "[$timestamp, $macdline]";
  $linesignalline[] = "[$timestamp, $signalline]";
  $linehistogram[] = "[$timestamp, $histogram]";
  $linehistogramup[] = "[$timestamp, $histogramup]";
  $linehistogramdown[] = "[$timestamp, $histogramdown]";
}
 ?>

 <script type="text/javascript">

 Highcharts.stockChart('container', {
   chart: {
           borderColor: '#404040',
           backgroundColor: '#FFFBD7',
           borderWidth: 5,
           borderRadius: 10,
           height: 600
       },
     rangeSelector: {
       inputBoxBorderColor: 'gray',
       inputBoxWidth: 120,
       inputBoxHeight: 18,
       inputStyle: {
           color: '#039',
           fontWeight: 'bold'
       },
       labelStyle: {
           color: 'black',
           fontWeight: 'bold'
       },

         selected: 1
     },
     yAxis: [{
         labels: {
             align: 'right',
             x: -3
         },
		 //min: -5000,
         //max: 5000,
         title: {
             text: 'Price',
             style: {
              color: '#000000',
              fontWeight: 'bold'
          }
         },
         height: '30%',
         lineWidth: 2
     },
     {
         labels: {
             align: 'right',
             x: -3
         },
		 //mengganti range grafik macd yg ditampilkan
         //min: -1.25,
         //max: 1.25,
         title: {
             text: 'MACD',
             style: {
              color: '#000000',
              fontWeight: 'bold'
          }
         },
            plotLines: [{
			//value: 30,
            //color: 'grey',
            //dashStyle: 'solid',
            //width: 1,
			label: 
				{
					text: 'Up Trend'
				}
        }, {
            //value: -30,
            //color: 'grey',
            //dashStyle: 'solid',
           // width: 1,
			label: 
				{
					text: 'Down Trend'
				}
        }],
         top: '35%',
         height: '50%',
         offset: 0,
         lineWidth: 2
     }],

     tooltip: {
         split: false
     },

     series: [
       {
         type: 'candlestick',
         name: 'Price',
         data: [<?php 	echo join($data, ','); ?>],
     },
      {
        type : 'arearange',
          name: 'Bollinger Bands',
          data: [<?php 	echo join($bband, ','); ?>],
          tooltip:      { valueDecimals: 2 },
          lineColor: '#000000',
          fillOpacity: 0.1,
        },
        {
        type : 'line',
          name: 'Middle Bands',
          dashStyle:'ShortDash',
          data: [<?php 	echo join($mband, ','); ?>],
        },
        {
			 type: 'line',
			 name: 'Macdline',
			 data: [<?php echo join($linemacdline, ','); ?>],
			 yAxis: 1,
			 color: '#3e00ff'  

		 },
		 {
			 type: 'line',
			 name: 'Signalline',
			 data: [<?php echo join($linesignalline, ','); ?>],
			 yAxis: 1,
			 color: '#ff0000'
		 },
		 {
			 type: 'column',
			 name: 'Histogram',
			 data: [<?php echo join($linehistogramup, ','); ?>],
			 yAxis: 1,
			 color: '#7fff7f'
		 },
		 {
			 type: 'column',
			 name: 'Histogram',
			 data: [<?php echo join($linehistogramdown, ','); ?>],
			 yAxis: 1,
			 color: '#ff7f7f'
		 }]
 });
 </script>