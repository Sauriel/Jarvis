<?php
    session_start();				# Sicher gehen, dass es eine Session gibt
	session_destroy();				# Session schliessen
	header("location:index.php");	# zum Index zurueckkehren
	exit();
?>