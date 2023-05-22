<html>
<head>
	<meta charset="utf-8">
	<title>Invoice</title>
	<link rel="stylesheet" href="style.css">
	<script src="script.js"></script>
	<style>
		/* CSS styles omitted for brevity */
	</style>
</head>
<body>
	<?php
	ob_start();	
	include('db.php');

	$pid = isset($_GET['pid']) ? $_GET['pid'] : "";
	
	$id = $title = $fname = $lname = $troom = $bed = $nroom = $cin = $cout = $meal = $ttot = $days = "";

	if (!empty($pid)) {
		$sql ="SELECT * FROM payment WHERE id = '$pid'";
		$re = mysqli_query($con, $sql);
		while ($row = mysqli_fetch_array($re)) {
			$id = $row['id'];
			$title = $row['title'];
			$fname = $row['fname'];
			$lname = $row['lname'];
			$troom = $row['troom'];
			$bed = $row['tbed'];
			$nroom = $row['nroom'];
			$cin = $row['cin'];
			$cout = $row['cout'];
			$meal = $row['meal'];
			$ttot = $row['ttot'];
			$days = $row['noofdays'];
		}
	}
	
	$type_of_room = 0;       
	if ($troom == "Superior Room") {
		$type_of_room = 320;
	} else if ($troom == "Deluxe Room") {
		$type_of_room = 220;
	} else if ($troom == "Guest House") {
		$type_of_room = 180;
	} else if ($troom == "Single Room") {
		$type_of_room = 150;
	}
									
	if ($bed == "Single") {
		$type_of_bed = $type_of_room * 1 / 100;
	} else if ($bed == "Double") {
		$type_of_bed = $type_of_room * 2 / 100;
	} else if ($bed == "Triple") {
		$type_of_bed = $type_of_room * 3 / 100;
	} else if ($bed == "Quad") {
		$type_of_bed = $type_of_room * 4 / 100;
	} else if ($bed == "None") {
		$type_of_bed = $type_of_room * 0 / 100;
	}
									
	if ($meal == "Room only") {
		$type_of_meal = $type_of_bed * 0;
	} else if ($meal == "Breakfast") {
		$type_of_meal = $type_of_bed * 2;
	} else if ($meal == "Half Board") {
		$type_of_meal = $type_of_bed * 3;
	} else if ($meal == "Full Board") {
		$type_of_meal = $type_of_bed * 4;
	}
	?>
	
	<header>
		<h1>Invoice</h1>
		<address>
			<p>Pinarik Eco Resort,</p>
			<p>New Kamuning Road,<br>Legazpi,<br>Albay.</p>
			<p>(+94) 65 222 44 55</p>
		</address>
		<span><img alt="" src="assets/img/sun.png"></span>
	</header>
	
	<article>
		<h1>Recipient</h1>
		<address>
			<p><?php echo $title . " " . $fname . " " . $lname; ?></p>
		</address>
		<table class="meta">
			<tr>
				<th><span>Invoice #</span></th>
				<td><span><?php echo $id; ?></span></td>
			</tr>
			<tr>
				<th><span>Date</span></th>
				<td><span><?php echo date("Y-m-d"); ?></span></td>
			</tr>
			<tr>
				<th><span>Amount Due</span></th>
				<td><span id="prefix"><?php echo $ttot; ?></span></td>
			</tr>
		</table>
		<table class="inventory">
			<thead>
				<tr>
					<th><span>Room Type</span></th>
					<th><span>Bed Type</span></th>
					<th><span>No. of Rooms</span></th>
					<th><span>Check-in</span></th>
					<th><span>Check-out</span></th>
					<th><span>No. of Days</span></th>
					<th><span>Meal Plan</span></th>
					<th><span>Price</span></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span><?php echo $troom; ?></span></td>
					<td><span><?php echo $bed; ?></span></td>
					<td><span><?php echo $nroom; ?></span></td>
					<td><span><?php echo $cin; ?></span></td>
					<td><span><?php echo $cout; ?></span></td>
					<td><span><?php echo $days; ?></span></td>
					<td><span><?php echo $meal; ?></span></td>
					<td><span><?php echo $ttot; ?></span></td>
				</tr>
			</tbody>
		</table>
		<table class="balance">
			<tr>
				<th><span>Total</span></th>
				<td><span><?php echo $ttot; ?></span></td>
			</tr>
		</table>
	</article>
	
	<aside>
		<h1><span>Contact us</span></h1>
		<div>
			<p>Phone: (+94) 65 222 44 55</p>
			<p>Email: example@example.com</p>
		</div>
	</aside>
</body>
</html>
