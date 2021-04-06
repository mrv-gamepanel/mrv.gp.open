<?php  

if (isset($_GET['task']) && $_GET['task'] == "return") {
	$_SESSION['ok'] = "Uspenso ste narucili Vas server putem SMS-a, ukoliko vam administracija ne odgovori u roku od 24h javite im se na fb ili putem emaila!";
	header("Location: /home");
	die();
}

if (isset($_GET['task']) && $_GET['task'] == "cancel") {
	$_SESSION['ok'] = "Odustali ste od narudzbe putem SMS-a!";
	header("Location: /home");
	die();
}

?>