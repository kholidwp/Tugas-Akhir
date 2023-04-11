<?php
set_time_limit(300);
/*
-- Source Code from My Notes Code (www.mynotescode.com)
--
-- Follow Us on Social Media
-- Facebook : http://facebook.com/mynotescode
-- Twitter  : http://twitter.com/mynotescode
-- Google+  : http://plus.google.com/118319575543333993544
--
-- Terimakasih telah mengunjungi blog kami.
-- Jangan lupa untuk Like dan Share catatan-catatan yang ada di blog kami.
*/

// Load file koneksi.php
include "koneksi.php";
//truncate csv table
try {
    $conn1 = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
    // set the PDO error mode to exception
    $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql to delete a record
    $sql1 = "TRUNCATE TABLE csv";

    // use exec() because no results are returned
    $conn1->exec($sql1);
    echo "Record deleted successfully";
    }
catch(PDOException $e)
    {
    echo $sql1 . "<br>" . $e->getMessage();
    }

$nama_file_baru = 'data.csv';

// Cek apakah terdapat file data.xlsx pada folder tmp
if(is_file('tmp/'.$nama_file_baru)) // Jika file tersebut ada
  unlink('tmp/'.$nama_file_baru); // Hapus file tersebut

//Upload data from google finance to temp/

$local_file = "tmp/data.csv";//This is the file where we save the information
//$remote_file = "https://finance.google.com/finance/historical?output=csv&q=IDX%3A".$_GET['saham']; //Here is the file we are downloading
//$remote_file = "https://finance.yahoo.com/quote/".$_GET['saham'].".JK/history?p=".$_GET['saham'].".JK";
$remote_file = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=".$_GET['saham']."&outputsize=full&apikey=9SSE9EXCD16ENJVK&datatype=csv";
//$remote_file = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=".$_GET['saham']."&apikey=9SSE9EXCD16ENJVK&datatype=csv";

$ch = curl_init();
$fp = fopen ($local_file, 'w+');
$ch = curl_init($remote_file);
curl_setopt($ch, CURLOPT_TIMEOUT, 50);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
// Execute
curl_exec($ch);
// Check if any error occured
if(!curl_errno($ch))
{
 curl_close($ch);
 fclose($fp);
try {
    $conn = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password, array(PDO::MYSQL_ATTR_LOCAL_INFILE => true));
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "LOAD DATA LOCAL INFILE '".$local_file."'
		INTO TABLE saham.csv
		FIELDS TERMINATED BY ','
		LINES TERMINATED BY '\n'
		IGNORE 1 LINES
		(@tanggal, @buka, @tinggi, @rendah, @penutupan, @vol)
		SET Date = @tanggal,
		Open = @buka,
		High = @tinggi,
		Low = @rendah,
		Close = @penutupan,
		Volume = @vol";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "New record created successfully";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
	}}
	// querry hapus isi table


header("location: bband14_data.php?saham=".urlencode($_GET['saham']));
?>
