<?php
				# Ueberpruefen ob Daten per POST gekommen sind
				if (!empty($_POST["submit"]) && $_SESSION["login"] == 0) {
			 		# MySQL Injections vorbeugen
					$_username = mysql_real_escape_string($_POST["username"]);
					$_password = mysql_real_escape_string($_POST["password"]);

					# Befehl für die MySQL Datenbank
					$_sql = "SELECT * FROM users WHERE
								username='$_username' AND
								password=md5('$_password') AND
			 					active=1
			 				LIMIT 1";

			 		$_res = mysql_query($_sql, $link);
 					$_anzahl = @mysql_num_rows($_res);

 					# Ueberpruefen ob ein passender User in der Datenbank gefunden wurde
 					if ($_anzahl > 0) {
	
				 		# In der Session merken, dass der User eingeloggt ist
				 		$_SESSION["login"] = 1;
	
				 		# Datenbank Eintrag wird in der Session gespeichert
				 		$_SESSION["user"] = mysql_fetch_array($_res, MYSQL_ASSOC);

				 		# Last login wird gesetzt
				 		$_sql = "UPDATE users SET last_login=NOW()
						WHERE id=".$_SESSION["user"]["id"];
 				 		mysql_query($_sql);
 	 		
 				 	} else {
 				 		echo "Die Logindaten sind nicht korrekt.<br>";
 				 	}
 				 }

 				# Ueberpruefe ob der User eingeloggt ist
 				if ($_SESSION["login"] == 0) {
 					# Login anzeigen
 					echo '<table>';
 					echo '<form method="POST" action="index.php">';
 					echo '<tr><td>Ihr Username:</td>';
					echo '<td><input name="username"></td></tr>';
					echo '<tr><td>Ihr Passwort:</td>';
					echo '<td><input name="password" type=password></td></tr>';
					echo '<tr><td colspan="2" align="center"><input type=submit name=submit value="Einloggen"></td></tr>';
					echo '</form>';
					echo '</table>';
 					mysql_close($link);
 					exit;
 				}
			?>