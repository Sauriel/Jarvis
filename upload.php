<?php
    SESSION_START();
	
 	if ($_SESSION["login"] == 1) {
		# mit Datenbank verbinden
		include("conect_db.php");
?>

<?php

    // Funktionen definieren 
    // ----------------------------------------------- 
    function checkUpload($myFILE, $file_extensions, $mime_types, $maxsize) 
    { 
        $errors = array(); 
        // Uploadfehler prüfen 
        switch ($myFILE['error']){ 
            case 1: $errors[] = "Bitte wählen Sie eine Datei aus, die <b>kleiner als ".ini_get('upload_max_filesize')."</b> ist."; 
                    break; 
            case 2: $errors[] = "Bitte wählen Sie eine Datei aus, die <b>kleiner als ".$maxsize/(1024*1024)." MB</b> ist.";
                    break; 
            case 3: $errors[] = "Die Datei wurde nur teilweise hochgeladen."; 
                    break; 
            case 4: $errors[] = "Es wurde keine Datei ausgewählt."; 
                    return $errors; 
                    break; 
            default : break; 
        } 
        // MIME-Type prüfen 
        if(count($mime_types)!=0 AND !in_array(strtolower($myFILE['type']), $mime_types)){ 
            $fehler = "Falscher MIME-Type (".$myFILE['type'].").<br />". 
                      "Erlaubte Typen sind:<br />\n"; 
            foreach($mime_types as $type) 
                $fehler .= " - ".$type."\n<br />"; 
            $errors[] = $fehler; 
        } 
        // Dateiendung prüfen 
        if($myFILE['name']=='' OR (count($file_extensions)!=0 AND !in_array(strtolower(getExtension($myFILE['name'])), $file_extensions))){ 
            $fehler = "Falsche Dateiendung (".getExtension($myFILE['name']).").<br />". 
                      "Erlaubte Endungen sind:<br />\n"; 
            foreach($file_extensions as $extension) 
                $fehler .= " - ".$extension."\n<br />"; 
            $errors[] = $fehler; 
        } 
        // Dateigröße prüfen 
        if($myFILE['size'] > $maxsize){ 
            $errors[] = "Datei zu groß (".sprintf('%.2f',$myFILE['size']/(1024*1024))." MB).<br />". 
                        "Erlaubte Größe: ".$maxsize/(1024*1024)." MB\n"; 
        } 
        return $errors; 
    } 

    // gibt die Dateiendung einer Datei zurück 
    function getExtension ($filename) 
    { 
        if(strrpos($filename, '.')) 
             return substr($filename, strrpos($filename, '.')+1); 
        return false; 
    } 

    // erzeugt einen Zufallswert 
    function getRandomValue() 
    { 
        return substr(md5(rand(1, 9999)),0,8).substr(time(),-6); 
    } 

    // erzeugt einen neuen Dateinamen aus Zufallswert und 
    // Dateiendung 
    function renameFile ($filename) 
    { 
        return  getRandomValue().".".getExtension($filename); 
    } 

    // Werte zur Dateiprüfung initialisieren 
    // ----------------------------------------------- 
    $maxsize = 2*1024*1024; 
    $file_extensions = array('jpg', 'jpeg', 'jpe', 'gif', 'png'); 
    $mime_types = array('image/pjpeg', 'image/jpeg', 'image/gif', 'image/png', 'image/x-png'); 

    // Upload-Ordner definieren 
    // ----------------------------------------------- 
    $ordner = "upload/"; 

    // Beginn des Skriptes 
    // ----------------------------------------------- 

   
    // Falls der Benutzer auf "Upload" gedrückt hat, 
    // wird die Datei überprüft 
    if(isset($_POST['submit']) AND $_POST['submit']=='Upload'){ 
        // Fehlerarray erzeugen 
        $errors = array(); 
        $myFILE = $_FILES['Datei']; 
        $errors = checkUpload($myFILE, $file_extensions, $mime_types, $maxsize);
        if(count($errors)){ 
            echo "<p>\n". 
                 "Die Datei konnte nicht gespeichert werden.<br />\n"; 
            foreach($errors as $error) 
                echo $error."<br />\n"; 
            echo "<a href=\"index.php?menu=upload\">Zurück zum Upload-Formular</a>\n". 
                 "</p>\n"; 
        } 
        else { 
            do { 
                $neuer_name = renameFile($myFILE['name']); 
            } while(file_exists($ordner.$neuer_name)); 
            if(@move_uploaded_file($myFILE['tmp_name'], $ordner.$neuer_name)){ 
            	
            	$_old = '/index.php\?menu=upload/';
				$_new = 'upload/' . $neuer_name;
				$_path = $_SERVER["REQUEST_URI"];
				$_path = preg_replace($_old, $_new, $_path);
				$_path = 'http://' . $_SERVER["HTTP_HOST"] . $_path;
				echo '<strong>Link:</strong></br>';
				echo '<input name="path" type="text" size="50" maxlength="50" value="' . $_path . '"></br>';
				echo '<a href="' . $_path . '" target="_blank">-- Klick --</a></br>';
            	
                echo "<p>\n". 
                      "Die Datei wurde erfolgreich gespeichert.<br />\n". 
                      "<a href=\"index.php?menu=upload\">Zurück zum Upload-Formular</a>\n". 
                      "</p>\n"; 
            } 
            else{ 
                echo "<p>\n". 
                      "Die Datei konnte nicht gespeichert werden.<br />\n". 
                      "Es ist ein Upload-Fehler aufgetreten.<br />\n". 
                      "Bitte versuchen Sie es später erneut.<br />\n". 
                      "<br />\n". 
                      "Sollte der Upload noch immer nicht funktionieren, informieren Sie uns bitte per Email.<br />\n". 
                      "<a href=\"index.php?menu=upload\">Zurück zum Upload-Formular</a>\n". 
                      "</p>\n"; 
            } 
        } 
    } 
    // Beim ersten Aufruf des Skriptes wird das 
    // Upload-Formular angezeigt 
    else{ 
        echo "<h1>Laden Sie ein Bild hoch!</h1>\n"; 
        echo "<h2>Erlaubte Dateiendungen sind:</h2>\n"; 
            foreach($file_extensions as $extension) 
                echo " - ".$extension."\n<br />"; 
        echo "<h2>Erlaubte Dateigröße:</h2>\n"; 
        echo " - maximal ".($maxsize/(1024*1024))." MB\n<br />"; 
        echo " <form ". 
             "action=\"index.php?menu=upload\" ". 
             "method=\"post\" ". 
             "enctype=\"multipart/form-data\">\n"; 
        echo "  <label for=\"Datei\">Datei auswählen</label>\n"; 
        echo "  <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".$maxsize."\" />\n";
        echo "  <input type=\"file\" name=\"Datei\" id=\"Datei\" />\n"; 
        echo "  <input type=\"submit\" name=\"submit\" value=\"Upload\" />\n"; 
        echo " </form>\n"; 
    } 
?>

<?php
	echo '<a href="index.php">zur&uuml;ck</a>';
	} else {
		echo "Du bist nicht eingelogt!";
	}
?>