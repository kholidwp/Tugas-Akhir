<?php
set_time_limit(300);
include 'koneksi.php';

try {
    $conn2 = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
    // set the PDO error mode to exception
    $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql to delete a record
    $sql = "TRUNCATE TABLE bband20";

    // use exec() because no results are returned
    $conn2->exec($sql);
    echo "Record deleted successfully";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

//BollingerBands-Start
// Function to calculate square of value - mean
function sd_square($x, $mean) { return pow($x - $mean,2); }

// Function to calculate standard deviation (uses sd_square)
function sd($array) {
    // square root of sum of squares devided by N-1
    return sqrt(array_sum(array_map("sd_square", $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)) );
}
$nol = '';


$sql = $pdo->prepare("SELECT id_csv, Date, Close FROM csv 
WHERE Date BETWEEN NOW() - INTERVAL 420 DAY AND NOW()
AND Close != 0
ORDER BY Date");
$sql->execute();

// prepare query insert
$conn = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql2 = $conn->prepare("INSERT INTO bband20 (id_csv,Date,rerata,sdp,uBand,lBand,bbRange) VALUES (:id_csv,:tanggal,:rerata,:sdp,:uBand,:lBand,:bbRange)");

for ($i=0; $i < 20; $i++) {
  $data = $sql->fetch();
  $id = $data['id_csv'];
  $tanggal = $data['Date'];
  $close[] = $data['Close'];
}
/*$rerata = array_sum($close)/count($close);
$sdp = sd($close);
$uBand = $rerata+$sdp*2; //Upper Band
$lBand = $rerata-$sdp*2; //Lower Band
$bbrange = $uBand-$lBand;
$tanggal = $data['Date'];

//bind data
$sql2->bindParam(':tanggal', $tanggal);
$sql2->bindParam(':rerata', $rerata);
$sql2->bindParam(':sdp', $sdp);
$sql2->bindParam(':uBand', $uBand);
$sql2->bindParam(':lBand', $lBand);
$sql2->bindParam(':bbRange', $bbrange);
$sql2->execute();*/

while ($data = $sql->fetch()) {
  array_shift($close); //keluarkan data lama
  $id = $data['id_csv'];
  $tanggal = $data['Date'];
  $close[20] = $data['Close'];
  $rerata = array_sum($close)/count($close);
  $sdp = sd($close);
  $uBand = $rerata+$sdp*2; //Upper Band
  $lBand = $rerata-$sdp*2; //Lower Band
  $bbrange = $uBand-$lBand;
  //$time = strtotime($tanggal);
  
  //bind data
  $sql2->bindParam(':id_csv', $id);
  $sql2->bindParam(':tanggal', $tanggal);
  $sql2->bindParam(':rerata', $rerata);
  $sql2->bindParam(':sdp', $sdp);
  $sql2->bindParam(':uBand', $uBand);
  $sql2->bindParam(':lBand', $lBand);
  $sql2->bindParam(':bbRange', $bbrange);
  $sql2->execute();
}
//BollingerBands-End
header("location: momentum14_data.php?saham=".urlencode($_GET['saham']));
?>

