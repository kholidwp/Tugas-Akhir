<!DOCTYPE HTML>
<!--
	Projection by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password'])) {
$error = "Username or Password is invalid";
}
else
{
// Define $username and $password
$username=$_POST['username'];
$password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysqli_connect("127.0.0.1", "root", "");
// To protect MySQL injection for Security purpose
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysqli_real_escape_string($connection, $username);
$password = mysqli_real_escape_string($connection, $password);
$password = md5(md5($password));
// Selecting Database
$db = mysqli_select_db($connection, "saham");
// SQL query to fetch information of registerd users and finds user match.
$query = mysqli_query($connection, "select * from admin where password='$password' AND username='$username'");
$rows = mysqli_num_rows($query);
if ($rows == 1) {
$_SESSION['admin_login']=$username; // Initializing Session
header("location: admin_page.php"); // Redirecting To Other Page
} else {
$error = "Username or Password is invalid";
}
mysqli_close($connection); // Closing Connection
}
}
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
					<a class="logo"><strong>SAHAM.ID</strong></a>
					<nav id="nav">
						<a href="index.html" style="font-weight: bold;">Home</a>
						<!--a href="user.php">Login</a-->
					</nav>
					<a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
				</div>
			</header>

		<!-- Three -->
			<section id="three" class="wrapper">
				<div class="inner">
					<header class="align-center">
						<h2 style="font-weight: bold;">Selamat Datang</h2>
						<form method="post" action="#">			
							<table border=0 align="center" cellpadding=5 cellspacing=0>
								<tr>
								<td colspan=3><center><font style="font-weight: bold;" size=5>ADMIN</font></center></td>
								</tr>
								<tr>
								<td style="font-weight: bold;">Username</td>
								<td>:</td>
								<td><input type="text" name="username" id="name" required="required"  placeholder="Username"/></td>
								</tr>
								<tr>
								<td style="font-weight: bold;">Password</td>
								<td>:</td>
								<td><input type="password" name="password" id="pass" required="required"  placeholder="Password"></td>
								</tr>
								<tr>
								<td colspan=3><input style="font-weight: bold;" type="submit" name="submit" value="LOGIN"></td>
								</tr>
							</table>
						</form>
					</header>
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
			<!--footer id="footer">
				<div class="inner">

					<h3>Get in touch</h3>

					<form action="#" method="post">

						<div class="field half first">
							<label for="name">Name</label>
							<input name="name" id="name" type="text" placeholder="Name">
						</div>
						<div class="field half">
							<label for="email">Email</label>
							<input name="email" id="email" type="email" placeholder="Email">
						</div>
						<div class="field">
							<label for="message">Message</label>
							<textarea name="message" id="message" rows="6" placeholder="Message"></textarea>
						</div>
						<ul class="actions">
							<li><input value="Send Message" class="button alt" type="submit"></li>
						</ul>
					</form>

					<div class="copyright">
						&copy; Untitled. Design: <a href="https://templated.co">TEMPLATED</a>. Images: <a href="https://unsplash.com">Unsplash</a>.
					</div>

				</div>
			</footer-->

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>