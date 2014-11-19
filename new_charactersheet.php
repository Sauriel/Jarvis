<?php
    SESSION_START();
	
 	if ($_SESSION["login"] == 1) {
		# mit Datenbank verbinden
		include("conect_db.php");
		
		if (empty($_SESSION["temp"]["c_rows"])) {
			$_SESSION["temp"]["c_rows"]= 1;
		} elseif ($_GET["rows"] == "1") {
			$_SESSION["temp"]["c_rows"] += 1;
		} elseif ($_GET["rows"] == "2") {
			$_SESSION["temp"]["c_rows"] -= 1;
		}
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

<strong>Charakterbogen</strong></br>
<form method="POST" action="index.php?menu=adventure">
<table>
<tr><td><strong>&Uuml;berschrift</strong></td><td><strong>Wert</strong></td></tr>

<?php
	$_rows .= '<tr><td><input name="label1" type="text" size="30" maxlength="30" value="Name"></td><td><input name="text1" type="text" size="70" maxlength="70" value="Charakterbogen"></td></tr>';
	for($_i = 2; $_i <= $_SESSION["temp"]["c_rows"]; $_i++) {
		$_rows .= '<tr><td><input name="label' . $_i . '" type="text" size="30" maxlength="30"></td><td><input name="text' . $_i . '" type="text" size="70" maxlength="70"></td></tr>';
	}
	echo $_rows;
	echo '<tr><td colspan="3" align="center">';
	if ($_SESSION["temp"]["c_rows"] <= 40) {
		echo '<a href="?menu=new_charactersheet&rows=1"><img src="images/plus.png" alt="mehr"></a>&nbsp;&nbsp;';
	}
	if ($_SESSION["temp"]["c_rows"] > 1) {
		echo '<a href="?menu=new_charactersheet&rows=2"><img src="images/minus.png" alt="weniger"></a></br>';
	}
	echo '</td></tr>';
	echo '<input name="master" type="hidden" value="true">';
?>

	<tr><td colspan="3">
	<input name="label41" type="text" size="30" maxlength="30" value="Geschichte"></br>
	<textarea id="eml1" name="text41" cols="76" rows="10"></textarea>
	</td></tr>
	<tr><td colspan="3">
	<strong>Geheimnisse:</strong> (f&uuml;r andere Spieler nicht sichtbar)</br>
	<textarea id="eml1" name="secrets" cols="76" rows="10"></textarea>
	</td></tr>
	</table>

<?php
	echo '<input type=submit name=submit value="Charakterbogen erstellen">';
	echo '</form>';
	echo '<a href="index.php?menu=adventure">zur&uuml;ck</a>';
	} else {
		echo "Du bist nicht eingelogt!";
	}
?>