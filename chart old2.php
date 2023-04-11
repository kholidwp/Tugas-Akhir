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
       IFNULL (momentum.momentum,0),
	   IFNULL (rsi.rsi,0)
  FROM csv
 LEFT JOIN bband ON bband.Date = csv.Date
 LEFT JOIN momentum ON momentum.Date = csv.Date
 LEFT JOIN rsi ON rsi.Date = csv.Date
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
  $momentum = $row['IFNULL (momentum.momentum,0)'];
  $datamomentum[]="[$timestamp, $momentum]";
  $rsi = $row['IFNULL (rsi.rsi,0)'];
  $datarsi[]="[$timestamp, $rsi]";
  $upBand = $row['IFNULL (bband.uBand,0)'];
  $lowBand = $row['IFNULL (bband.lBand,0)'];
  $rerata = $row['IFNULL (bband.rerata,0)'];
  $bband[] = "[$timestamp, $upBand, $lowBand]";
  $mband[] = "[$timestamp, $rerata]";
}
 ?>

 <script type="text/javascript">

 Highcharts.stockChart('container', {
   chart: {
           borderColor: '#404040',
           backgroundColor: '#FFFBD7',
           borderWidth: 5,
           borderRadius: 10,
           height: 500
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
         height: '60%',
         lineWidth: 2
     },
     {
         labels: {
             align: 'right',
             x: -3
         },
		 //mengganti range grafik momentum yg ditampilkan
         min: 80,
         max: 300,
         title: {
             text: 'Momentum',
             style: {
              color: '#000000',
              fontWeight: 'bold'
          }
         },
            plotLines: [{
            value: 30,
            color: 'grey',
            dashStyle: 'solid',
            width: 1,
        }, {
            value: 70,
            color: 'grey',
            dashStyle: 'solid',
            width: 1,
        }],
         top: '65%',
         height: '35%',
         offset: 0,
         lineWidth: 2
     },
	 {
         labels: {
             align: 'right',
             x: -3
         },
		 //mengganti range grafik momentum yg ditampilkan
         min: 80,
         max: 300,
         title: {
             text: 'Momentum',
             style: {
              color: '#000000',
              fontWeight: 'bold'
          }
         },
            plotLines: [{
            value: 30,
            color: 'grey',
            dashStyle: 'solid',
            width: 1,
        }, {
            value: 70,
            color: 'grey',
            dashStyle: 'solid',
            width: 1,
        }],
         top: '65%',
         height: '35%',
         offset: 0,
         lineWidth: 2
     }
	 /*{
         labels: {
             align: 'right',
             x: -3
         },
		 //mengganti range grafik RSI yg ditampilkan
         min: 80,
         max: 300,
         title: {
             text: 'RSI',
             style: {
              color: '#000000',
              fontWeight: 'bold'
          }
         },
            plotLines: [{
            value: 30,
            color: 'grey',
            dashStyle: 'solid',
            width: 1,
        }, {
            value: 70,
            color: 'grey',
            dashStyle: 'solid',
            width: 1,
        }],
         top: '65%',
         height: '35%',
         offset: 0,
         lineWidth: 2
     }*/],

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
          name: 'Momentum',
          data: [<?php echo join($datamomentum, ','); ?>],
          yAxis: 1,
        }/*,
		type: 'line',
          name: 'RSI',
          data: [<?php echo join($datarsi, ','); ?>],
          yAxis: 2,
        }*/]
 });
 </script>