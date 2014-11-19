<?php
    SESSION_START();
	
 	if ($_SESSION["login"] == 1) {
		# mit Datenbank verbinden
		include("conect_db.php");

		# Überprüfen ob ein Setting gewählt wurde
		$_setting = mysql_real_escape_string($_POST["setting"]);
		if ($_setting != NULL) {
			if ($_setting != $_SESSION["selectAdventure"]["setting"]) {
				$_SESSION["selectAdventure"]["campaign"] = NULL;
				$_SESSION["selectAdventure"]["adventure"] = NULL;
			}
			$_SESSION["selectAdventure"]["setting"] = $_setting;
		} else {
			$_setting = $_SESSION["selectAdventure"]["setting"];
		}

		# Überprüfen ob eine Kampagne gewählt wurde
		$_campaign = mysql_real_escape_string($_POST["campaign"]);
		if ($_campaign != NULL) {
			if ($_campaign != $_SESSION["selectAdventure"]["campaign"]) {
				$_SESSION["selectAdventure"]["adventure"] = NULL;
			}
			$_SESSION["selectAdventure"]["setting"] = $_setting;
			$_SESSION["selectAdventure"]["campaign"] = $_campaign;
		} else {
			$_campaign = $_SESSION["selectAdventure"]["campaign"];
		}

		# Überprüfen ob ein Abenteuer gewählt wurde
		$_adventure = mysql_real_escape_string($_POST["adventure"]);
		if ($_adventure != NULL) {
			$_SESSION["selectAdventure"]["setting"] = $_setting;
			$_SESSION["selectAdventure"]["campaign"] = $_campaign;
			$_SESSION["selectAdventure"]["adventure"] = $_adventure;
		} else {
			$_adventure = $_SESSION["selectAdventure"]["adventure"];
		}

		# Auswahl erstellen
		
		# Setting anzeigen
		$_query = "SELECT DISTINCT setting FROM adventures";
		$_result_s = mysql_query($_query) or die(mysql_error());
		# Kampagnen des Settings anzeigen
		$_query = "SELECT DISTINCT campaign FROM adventures WHERE setting='$_setting'";
		$_result_c = mysql_query($_query) or die(mysql_error());
		# Abenteuer der Kampagne anzeigen
		$_query = "SELECT adventure FROM adventures WHERE campaign='$_campaign' AND setting='$_setting'";
		$_result_a = mysql_query($_query) or die(mysql_error());
		# Kurzinfo des Abenteuers anzeigen
		$_query = "SELECT short_info FROM adventures WHERE adventure='$_adventure'";
		$_query = mysql_query($_query) or die(mysql_error());
		$_short_info = mysql_fetch_assoc($_query, MYSQL_ASSOC);
		# Langinfo des Abenteuers anzeigen
		$_query = "SELECT long_info FROM adventures WHERE adventure='$_adventure'";
		$_query = mysql_query($_query) or die(mysql_error());
		$_long_info = mysql_fetch_assoc($_query, MYSQL_ASSOC);
		# Spielleiter des Abenteuers anzeigen
		$_query = "SELECT gamemaster FROM adventures WHERE adventure='$_adventure' AND campaign='$_campaign' AND setting='$_setting'";
		$_query = mysql_query($_query) or die(mysql_error());
		$_gm_id = mysql_fetch_assoc($_query, MYSQL_ASSOC);
		$_query = "SELECT username FROM users WHERE id='$_gm_id[gamemaster]'";
		$_query = mysql_query($_query) or die(mysql_error());
		$_gamemaster = mysql_fetch_assoc($_query, MYSQL_ASSOC);
		# ID des Abenteuers anzeigen
		$_query = "SELECT id FROM adventures WHERE adventure='$_adventure' AND campaign='$_campaign' AND setting='$_setting'";
		$_query = mysql_query($_query) or die(mysql_error());
		$_id = mysql_fetch_assoc($_query, MYSQL_ASSOC);
		$_SESSION["selectAdventure"]["long_info"] = $_long_info[long_info];
		$_SESSION["selectAdventure"]["gamemaster"] = $_gamemaster[username];
		$_SESSION["selectAdventure"]["adventure_id"] = $_id[id];
	
		?>
		<table width="100%">
			<tr>
				<td align="center" width="32%">
					<strong>Setting:</strong>
					<form method="POST" action="index.php">
		
		<?php
			$_dropdown = '<select name="setting" size=10 style="width:100%;" onchange="this.form.submit();">';
			while($_row = mysql_fetch_assoc($_result_s)) {
				$_dropdown .= "\r\n<option value='{$_row['setting']}'>{$_row['setting']}</option>";
			}
			$_dropdown .= "\r\n</select>";
			echo $_dropdown;
		?>
		</td>
		<td align="center">
		<strong>Kampagne:</strong>
			
		<?php
			$_dropdown = '<select name="campaign" size=10 style="width:98%;" onchange="this.form.submit();">';
			while($_row = mysql_fetch_assoc($_result_c)) {
				$_dropdown .= "\r\n<option value='{$_row['campaign']}'>{$_row['campaign']}</option>";
			}
			$_dropdown .= "\r\n</select>";
			echo $_dropdown;
		?>
		</td>
		<td align="center" width="32%">
		<strong>Abenteuer:</strong>
			
		<?php
			$_dropdown = '<select name="adventure" size=10 style="width:100%;" onchange="this.form.submit();">';
			while($_row = mysql_fetch_assoc($_result_a)) {
				$_dropdown .= "\r\n<option value='{$_row['adventure']}'>{$_row['adventure']}</option>";
			}
			$_dropdown .= "\r\n</select>";
			echo $_dropdown;
		?>
		</td>
		</tr>
		</table>
		</form>
		
		<?php
		if ($_SESSION["selectAdventure"]["adventure"] != NULL) {
			echo '<strong>' . $_SESSION["selectAdventure"]["adventure"] . ':</strong></br>';
			echo $_short_info[short_info];
			echo '</br></br>';
			echo 'Spielleiter: ' . $_SESSION["selectAdventure"]["gamemaster"];
			echo '</br></br>';
			echo '<a href="index.php?menu=adventure">' . $_SESSION["selectAdventure"]["adventure"] . ' beitreten</a> oder <a href="index.php?menu=new_adventure">Neues Abenteuer erstellen</a>.';
		} else {
			echo 'Ein bestehendes Abenteuer ausw&auml;hlen oder <a href="index.php?menu=new_adventure">Neues Abenteuer erstellen</a>.';	
		}
	} else {
		echo "Du bist nicht eingelogt!";
	}
?>