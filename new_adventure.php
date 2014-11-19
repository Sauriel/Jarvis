<?php
    SESSION_START();
	
 	if ($_SESSION["login"] == 1) {
		# mit Datenbank verbinden
		include("conect_db.php");
		
		# Setting anzeigen
		$_query = "SELECT DISTINCT setting FROM adventures";
		$_result_s = mysql_query($_query) or die(mysql_error());
		# Kampagnen des Settings anzeigen
		$_query = "SELECT DISTINCT campaign FROM adventures";
		$_result_c = mysql_query($_query) or die(mysql_error());
?>

<!-- TinyMCE -->
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,visualblocks",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->

<form method="POST" action="index.php?menu=adventure">
<table width="100%">
	<tr>
		<td align="center" width="33%"><strong>Setting:</strong>
			<?php
				$_dropdown = '<select name="setting" size=1 style="width:90%;">';
				while($_row = mysql_fetch_assoc($_result_s)) {
					$_dropdown .= "\r\n<option value='{$_row['setting']}'>{$_row['setting']}</option>";
					}
				$_dropdown .= "\r\n</select>";
				echo $_dropdown;
			?>
			<input name="new_setting" type="text" size="30" maxlength="30" value="oder neues Setting eingeben">
		</td>
		<td align="center" width="34%"><strong>Kampagne:</strong>
			<?php
				$_dropdown = '<select name="campaign" size=1 style="width:90%;">';
				while($_row = mysql_fetch_assoc($_result_c)) {
					$_dropdown .= "\r\n<option value='{$_row['campaign']}'>{$_row['campaign']}</option>";
					}
				$_dropdown .= "\r\n</select>";
				echo $_dropdown;
			?>
			<input name="new_campaign" type="text" size="30" maxlength="30" value="oder neue Kampagne eingeben">
		</td>
		<td align="center" width="33%"><strong>Abenteuer:</strong>
			<input name="adventure" type="text" size="30" maxlength="30" value="Name des Abenteuers eingeben">
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			</br></br>
			<strong>Kurzbeschreibung:</strong></br></br>
			<textarea id="eml1" name="short_info" cols="76" rows="10">Bitte Kurzbeschreibung eingeben</textarea>
			</br></br></br>
			<strong>ausf&uuml;hrliche Beschreibung:</strong></br></br>
			<textarea id="elm2" name="long_info" cols="76" rows="20">Bitte ausf&uuml;hrliche Beschreibung eingeben</textarea>
			</br></br>
			<input type=submit name=submit value="Abenteuer erstellen">
		</td>
	</tr>
</table>
</form>
<a href="index.php">zur&uuml;ck</a>

<?php
	} else {
		echo "Du bist nicht eingelogt!";
	}
?>