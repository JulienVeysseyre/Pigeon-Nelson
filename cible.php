<!DOCTYPE html>

<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Formulaire Pigeon Nelson</title>
	<link rel="stylesheet" href="style2.css" />
	<link rel="icon" href="https://compas.limos.fr/le-pigeon-nelson/le-pigeon-nelson-logo.svg" />
</head>

<body>
	<header>
                <img src="https://compas.limos.fr/le-pigeon-nelson/le-pigeon-nelson-logo.svg" alt="image illustrative" />

    </header>

<?php
	
	$files = glob("fichierAudio/*.*") ; /* pour "lister" les fichiers ( *.* pour dire que ce dossier contient une extension (par exemple .jpg, .php, etc... ) */
	$RecentTime = filectime($files[0]) ; // filectime renvoie la date de dernière modification d'un fichier
	$n = 0 ;
	for ( $i=1; $i < count($files); $i++ ) {        // On va chercher le fichier le plus recent et stocker son indice n. 
		$FileTime = filectime($files[$i]) ;   
		if ( $FileTime > $RecentTime ) { 
			$RecentTime = $FileTime ;
			$n = $i ;
		}
	}
	$id = (int) filter_var($files[$n], FILTER_SANITIZE_NUMBER_INT); // extraire nombre de la chaine de caractaire.

	use chillerlan\QRCode\{QRCode, QROptions};
    	require_once 'vendor/autoload.php';
	
	if ( empty($_POST['souhait']) || $_POST['rayon'] == '' ){
		
    		$data = 'https://lepigeonnelson.jmfavreau.info/formulaire/compteur.php?id=' .($id);
    		echo '<img src="'.(new QRCode)->render($data).'" alt="QR Code" />'; 
		echo "<h1> Champ(s) manquant(s) ... Cependant, vous pouvez ajouter ce serveur qui est le plus récent : </h1>
			<h3> soit grâce au QRCode ci-dessus ou bien <a href='https://lepigeonnelson.jmfavreau.info/formulaire/compteur.php?id=<?php echo $id;?>'> en cliquant directement ici ! </a></h3>" ;
			echo "<footer><p>© Formulaire</p></footer>" ;
	} 
	
	else{ 
				
		$Element = $_POST['souhait'] ;		// On récupère les données
		$Rayon = $_POST['rayon'] ;
		
		
		$tableau = array (
		'element' => $Element,
		'rayon' => $Rayon) ;

		$fp = fopen('fichierAudio/results' .($id + 1) .'.json', 'w');
		fwrite($fp, json_encode($tableau));
		fclose($fp);
	
    		$data = 'https://lepigeonnelson.jmfavreau.info/formulaire/compteur.php?id=' .($id+1);
    		echo '<img src="'.(new QRCode)->render($data).'" alt="QR Code" />';
		echo "<h1> Vous pouvez maintenant ajouter le serveur dans le Pigeon Nelson : </h1> 
			<h3> soit grâce au QRCode ci-dessus ou bien <a href='https://lepigeonnelson.jmfavreau.info/formulaire/compteur.php?id=<?php echo $id+1;?>'> en cliquant directement ici ! </a></h3>" ;
			echo "<footer><p>© Formulaire</p></footer>" ;
	} 
?> 


</body>

</html>