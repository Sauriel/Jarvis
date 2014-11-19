<?php
    SESSION_START();
	
 	if ($_SESSION["login"] == 1) {
		# mit Datenbank verbinden
		include("conect_db.php");
		
		# Felder anzeigen
		$_adventure_id = $_SESSION["selectAdventure"]["adventure_id"];
		$_query = "SELECT DISTINCT number_of_rows FROM charactersheets WHERE adventure_id='$_adventure_id' AND master = 1";
		$_result = mysql_query($_query) or die(mysql_error());
		$_number_of_rows = mysql_fetch_array($_result, MYSQL_ASSOC);
		$_SESSION["temp"]["c_rows"] = $_number_of_rows[number_of_rows];
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

<strong>Charakterbogen:</strong></br>
<form method="POST" action="index.php?menu=adventure">
<table>

<?php
	echo '<tr><td colspan="3" align="center">';
	echo '<strong>Avatar:</strong><input name="avatar" type="text" size="30">';
	echo '</td></tr>';

	for($_i = 1; $_i <= $_SESSION["temp"]["c_rows"]; $_i++) {
		# Labeltext auslesen
		$_query_l = "SELECT DISTINCT label" . $_i . " FROM charactersheets WHERE adventure_id='$_adventure_id' AND master = 1";
		$_result_l = mysql_query($_query_l) or die(mysql_error());
		$_res_l = mysql_fetch_array($_result_l, MYSQL_ASSOC);
		$_res_l2 = $_res_l[label . $_i];
		# Text auslesen
		$_query_t = "SELECT DISTINCT text" . $_i . " FROM charactersheets WHERE adventure_id='$_adventure_id' AND master = 1";
		$_result_t = mysql_query($_query_t) or die(mysql_error());
		$_res_t = mysql_fetch_array($_result_t, MYSQL_ASSOC);
		$_res_t2 = $_res_t[text . $_i];
		$_rows .= '<tr><td width="174px"><strong>' . $_res_l2 . '</strong><input name="label' . $_i . '" type="hidden" size="30" maxlength="30" value="' . $_res_l2 . '"></td><td><input name="text' . $_i . '" type="text" size="70" maxlength="70" value="' . $_res_t2 . '"></td></tr>';
	}
	echo $_rows;
		# Labeltext auslesen
		$_query_l = "SELECT DISTINCT label41 FROM charactersheets WHERE adventure_id='$_adventure_id' AND master = 1";
		$_result_l = mysql_query($_query_l) or die(mysql_error());
		$_res_l = mysql_fetch_array($_result_l, MYSQL_ASSOC);
		$_res_l2 = $_res_l[label41];
		# Text auslesen
		$_query_t = "SELECT DISTINCT text41 FROM charactersheets WHERE adventure_id='$_adventure_id' AND master = 1";
		$_result_t = mysql_query($_query_t) or die(mysql_error());
		$_res_t = mysql_fetch_array($_result_t, MYSQL_ASSOC);
		$_res_t2 = $_res_t[text41];
	echo '<tr><td colspan="3">';
	echo '</br><strong>' . $_res_l2 . '</strong><input name="label41" type="hidden" size="30" maxlength="30" value="' . $_res_l2 . '"></br>';
	echo '<textarea id="eml1" name="text41" cols="76" rows="10">' . $_res_t2 . '</textarea></td></tr>';
	echo '<tr><td colspan="3">';
		# Text auslesen
		$_query_s = "SELECT DISTINCT secrets FROM charactersheets WHERE adventure_id='$_adventure_id' AND master = 1";
		$_result_s = mysql_query($_query_s) or die(mysql_error());
		$_res_s = mysql_fetch_array($_result_s, MYSQL_ASSOC);
		$_res_s2 = $_res_s[secrets];
	echo '</br></br><strong>Geheimnisse:</strong> (f&uuml;r andere Spieler nicht sichtbar)</br>';
	echo '<textarea id="eml2" name="secrets" cols="76" rows="10">' . $_res_s2 . '</textarea>';	
	echo '</td></tr></table>';
?>

<?php
	echo '<input type=submit name=submit value="Charakter erstellen">';
	echo '</form>';
	echo '<a href="index.php?menu=adventure">zur&uuml;ck</a>';
	} else {
		echo "Du bist nicht eingelogt!";
	}
?>