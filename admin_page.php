<!DOCTYPE HTML>
<!--
	Projection by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->


<?php
include('session_admin.php');
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
/*$connection = @mysqli_connect("127.0.0.1", "root", "");
// Selecting Database
$db = mysqli_select_db($connection, "saham");
session_start();// Starting Session
// Storing Session
$user_check=$_SESSION['username'];
// SQL Query To Fetch Complete Information Of User
$ses_sql=mysqli_query($connection, "select username from admin where username='$user_check'");
$row = mysqli_fetch_assoc($ses_sql);
$login_session =$row['username'];
if(!isset($login_session)){
mysqli_close($connection); // Closing Connection
header('Location: admin_login.php'); // Redirecting To Home Page
}*/
?>
<html>
	<head>
		<title>Universitas Diponegoro</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="subpage">

		<!-- Header -->
			<header id="header">
				<div class="inner">
					<a href="index.html" class="logo"><strong>SAHAM.ID</strong></a>
					<nav id="nav">
						<a>Selamat Datang, <?php echo $loginadmin_session; ?></a>
						<a href="admin_pagestrat.php">Strategi</a>
						<a href="logout.php">Logout</a>
					</nav>
					<a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
				</div>
			</header>

		<!-- Three -->
			<section id="three" class="wrapper">
				<div class="col-md-6 col-sm-6 col-xs-12">
      <h3><center>User</center></h3>
      <form action="save.php" method="post">
    <table class="table table-hover vertical-align">
      <th>ID</th>
      <th>Username</th>
	  <th>Password</th>
      <th>Email</th>
       <th>Access</th>
      <?php
      include "koneksi.php";
      $conn4 = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
      $conn4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sqlv = $conn4->prepare("SELECT * from user");
      $sqlv->execute();
      while( $row = $sqlv->fetch(PDO::FETCH_ASSOC) ) {
      ?>
      <tr>
          <td> <?php echo $row['id_user']; ?></td>
          <td><input type="text" name="username[<?php echo $row['id_user']; ?>]" id="name" value="<?php echo $row['username']; ?>" > </td>
		  <td><input type="text" name="password[<?php echo $row['id_user']; ?>]" id="pass" value="<?php echo $row['password']; ?>" > </td>
          <td><input type="email" name="email[<?php echo $row['id_user']; ?>]" id="mail" value="<?php echo $row['email']; ?>"> </td>
          <td> <input type="radio" id="free_<?php echo $row['id_user']; ?>" name="access[<?php echo $row['id_user']; ?>]" value="free" checked >
				<label for="free_<?php echo $row['id_user']; ?>">Free</label>
          		<input type="radio" id="premium_<?php echo $row['id_user']; ?>" name="access[<?php echo $row['id_user']; ?>]" value="premium" >
				<label for="premium_<?php echo $row['id_user']; ?>">Premium</label>    
															<?php } ?>
		   </tr>
		    </table>
       <td colspan=3><center><input type="submit" value=" Submit " name="submit"/></center>
		</form>  
    </div>

		<header id="home" class="sections">
            <div class="container">
              <div class="row">
                <div class="homepage-style">
            </div>
            </div>
            </div>
					
				</div>
			</section>

		<!-- Footer -->
			<!--footer id="footer" class="wrapper">
				<div class="inner">

					<h3>Biodata</h3>

					<div class="row">
							<section class="6u 12u$(medium)">
								<p>Kholid Wisnu P</p>
								<p>21120114140107</p>
								<p>Teknik Komputer</p>
								<p>Universitas Diponegoro</p>
							</section>
							<section class="3u 6u(medium) 12u$(small)">
							</section>
							<section class="3u$ 6u$(medium) 12u$(small)">
								<p><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></p>
								<p><a href="https://web.facebook.com/" class="icon fa-facebook"><span class="label">Facebook</span></a></p>
								<p><a href="https://www.instagram.com/" class="icon fa-instagram"><span class="label">Instagram</span></a></p>
							</section>
						</div>

				</div>
			</footer>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>