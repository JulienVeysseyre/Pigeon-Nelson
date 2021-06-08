<?php

include '../pigeon-nelson.php';




$server = new PigeonNelsonServer($_GET);

$id = $_GET["id"] ;
$resultat = json_decode(file_get_contents('fichierAudio/results' .$id .'.json')) ;
$element = $resultat->element ;
$rayon = $resultat->rayon ;

if ($element == "arbre") {
	$server->setName("Nombre d'" .$element ."s" ." dans un rayon de " .$rayon ." mètres.");
}
else if ($element == "passage piéton") {
	$server->setName("Nombre de passages piétons dans un rayon de " .$rayon ." mètres.");
}
else {
	$server->setName("Nombre de " .$element ."s" ." dans un rayon de " .$rayon ." mètres.");
}
$server->setDescription("Connaître le nombre d'élèment d'un type choisi dans un rayon donné à partir de votre position actuelle.");
$server->setEncoding("UTF-8");
$server->setDefaultPeriodBetweenUpdates(0);


if ($server->isRequestedSelfDescription()) {
    print $server->getSelfDescription();
    return;
}


if (!$server->hasCoordinatesRequest()) {
    echo "[]";
    return;
}


if ($element == "arbre") {
    $parametre = '"natural"="tree"';
}
elseif ($element == "banc") {
    $parametre = '"amenity"="bench"';
}
elseif ($element == "lampadaire") {
    $parametre = '"highway"="street_lamp"';
}
elseif ($element == "passage piéton") {
    $parametre = '"highway"="crossing"';
}
    
$server->getOSMData('[out:json][timeout:25];(node['. $parametre . ']({{box}}););out center;', $rayon);


$nb = count($server->getEntries()) ;

if ( $rayon >= 1000 ) {
	$rayon /= 1000 ;
	$rayon = str_replace('.', ',', $rayon);
	if ( $element == "arbre" && $nb == 0 ) {

			$message = PigeonNelsonMessage::makeTxtMessage("Il n'y a pas d'" .$element ."s dans un rayon de " .$rayon ." kilomètres autour de vous !", "fr");
		}
	else {
		if ( $nb == 0 ) {
			$message = PigeonNelsonMessage::makeTxtMessage("Il y a pas de " .$element ."s dans un rayon de " .$rayon ." kilomètres autour de vous !", "fr");
		}
		elseif ( $nb < 15 ) {
			$message = PigeonNelsonMessage::makeTxtMessage("Il n'y a que " .$nb ." " .$element ."s dans un rayon de " .$rayon ." kilomètres autour de vous !", "fr");
		}
		else {
			$message = PigeonNelsonMessage::makeTxtMessage("Il y a " .$nb ." " .$element ."s dans un rayon de " .$rayon ." kilomètres autour de vous !", "fr");
		}
	}
}

else {

	if ( $element == "arbre" && $nb == 0 ) {

		$message = PigeonNelsonMessage::makeTxtMessage("Il n'y a pas d'" .$element ."s dans un rayon de " .$rayon ." mètres autour de vous !", "fr");
	}
	else {
		if ( $nb == 0 ) {
			$message = PigeonNelsonMessage::makeTxtMessage("Il y a pas de " .$element ."s dans un rayon de " .$rayon ." mètres autour de vous !", "fr");
		}
		elseif ( $nb < 15 ) {
			$message = PigeonNelsonMessage::makeTxtMessage("Il n'y a que " .$nb ." " .$element ."s dans un rayon de " .$rayon ." mètres autour de vous !", "fr");
		}
		else {
			$message = PigeonNelsonMessage::makeTxtMessage("Il y a " .$nb ." " .$element ."s dans un rayon de " .$rayon ." mètres autour de vous !", "fr");
		}
	}
}
	

$message->setPriority(0);
$server->addMessage($message);
    
$server->printMessages();


?>

