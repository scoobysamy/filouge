<?php
# Fixe le format des données via l’entête HTTP Content-Type,
# ainsi que l’encodage des caractères de cette page en UTF-8
header('Content-type: text/html ; charset=utf-8');
# Initialisation de la configuration du site
if ( file_exists( __DIR__ .'/config/config.php' ) )
	require(__DIR__ .'/config/config.php');
# Chargement des classes personnelles
# (impérativement AVANT session_start() sinon on aura des objets incomplets)
if ( file_exists( RACINE .'/classes/autoload.php' ) )
	require_once RACINE .'/classes/autoload.php';
# Chargement de quelques fonctions de débuggage (à supprimer en production)
if ( file_exists( RACINE .'/debug.php' ) )
	require_once RACINE .'/debug.php';
# Démarrage ou reprise d’une session existante
session_name('Session_de_Chat'); # Nom de ma session à la place de PHPSESSID
session_start(); # On démarre une nouvelle session ou on reprend une session existante

# http://stackoverflow.com/questions/17242346/php-session-lost-after-redirect
//$_SESSION['test'] = 'On est passé par le fichier initialisation_des_variables.php';
if ( !isset($_SESSION['ok']) )
	$_SESSION['ok'] = false; # Booléen pour indiquer si les login et mot de passe sont valides
if ( !isset($_SESSION['tentatives']) )
	$_SESSION['tentatives'] = 0; # Compteur d'essais de connexion
if ( !isset($_SESSION['f5']) )
	$_SESSION['f5'] = 0; # Compteur de rechargement de la page
else
	$_SESSION['f5']++; # On incrémente le compteur de rechargement de la page

# Si on a cliqué sur le bouton de déconnexion ou si la section choisie est la page de connexion
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['quitter']) ) {
	if ( file_exists( INC .'/detruireSession.inc.php' ) ) {
		include INC .'/detruireSession.inc.php';
		// header('Location: '.DOSSIER_PRINCIPAL);
	}
}
/*
# Si on a cliqué sur le bouton de déconnexion ou si la section choisie est la page de connexion
if ( isset($_SESSION['section_actuelle']) && $_SESSION['section_actuelle'] == 'connexion' ) {
	if ( file_exists( INC .'/detruireSession.inc.php' ) ) {
		include INC .'/detruireSession.inc.php';
		// $_SESSION['section_actuelle'] = 'connexion';
	}
}
*/

# Si une précédente session existe
if ( isset($_SESSION['adresse_ip']) ) {
	if ( $_SERVER['REMOTE_ADDR'] == $_SESSION['adresse_ip'] ) {
		$_SESSION['ok'] = true;
		# Si la classe de connexion à la base de données existe
		if ( class_exists('Bdd') ) {
			# On instancie un objet de connexion \PDO
			$pdo = Bdd::instancier();
			if ( false!==$pdo && class_exists('ManageurDynamique') ) {
				$admin = new ManageurDynamique( $pdo );
			}
		}
	}
	# Echec d'authentification : on repart sur une nouvelle session toute neuve
	elseif ( file_exists( INC .'/detruireSession.inc.php' ) ) {
		include INC .'/detruireSession.inc.php';
	}
}
// else
	// $_SESSION['section_actuelle'] = 'connexion';
?>
<!doctype html>
<html lang="fr">
<head>
	<?php include INC.'/metalink.inc.php'; ?>
	<meta name="description" content="système de gestion, de planification, de sessions de formation">
	<title>Système de gestion de formation (ou Formaneed ?)</title>
</head>
<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top" data-offset="20">

<?php
if ( file_exists( INC .'/navigation.inc.php' ) )
	include INC .'/navigation.inc.php';
?>
<main class="container-fluid">
<?php
# Si une session valide est active
if ( !empty($_SESSION['ok']) && $_SESSION['ok'] ) {

	if ( file_exists( INC .'/identifiants-de-connexion.inc.php' ) )
		include INC .'/identifiants-de-connexion.inc.php';

	if ( file_exists( INC .'/informations-personnelles.inc.php' ) )
			include INC .'/informations-personnelles.inc.php';

	if ( file_exists( INC .'/cours.inc.php' ) )
		include INC.'/cours.inc.php';

	if ( file_exists( INC .'/qcm-creation.inc.php' ) )
		include INC.'/qcm-creation.inc.php';

	if ( file_exists( INC .'/membres.inc.php' ) )
		include INC.'/membres.inc.php';
/*
	if ( file_exists( INC .'/lechat.inc.php' ) )
		include INC.'/lechat.inc.php';
*/
	vd($_SESSION, '$_SESSION');
}
else { # Sinon si on n’est pas dans une session, on affiche la porte d’entrée

	if ( file_exists( INC .'/connexion.inc.php' ) )
		include INC.'/connexion.inc.php';
	// if ( file_exists( RACINE.'/inscription.inc.php') )
		// include RACINE.'/inscription.inc.php';
}

# =========================================================================================================== #
# oxxxxx][ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ> BOUTON POUR REMONTER TOUT EN HAUT EN GLISSANT <ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ][xxxxxo #
# =========================================================================================================== #
?>
	<div class="scroll-top-wrapper ">
		<span class="scroll-top-inner"><i class="fa fa-2x fa-arrow-circle-up"></i></span>
	</div>

<?php
# ===================================================================================================== #
# oxxxxx][ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ> ROUE DE CHARGEMENT POUR FAIRE PATIENTER <ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ][xxxxxo #
# ===================================================================================================== #
?>
	<div class="hidden" id="roue">
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw" aria-hidden="true" title="Roue qui tourne pour faire patienter pendant les chargements"></i>
	</div>

<!-- <div class="summernote">summernote 1</div> -->
<!-- <div class="summernote">summernote 2</div> -->
</main>

<?php
if ( file_exists( INC .'/footer.inc.php' ) ) include INC.'/footer.inc.php';

if ( file_exists( INC .'/scripts.inc.php' ) ) include INC.'/scripts.inc.php';

$compte = [
	'ok' => !empty($_SESSION['ok']) ? (int) $_SESSION['ok'] : false,
	'prenom' => !empty($_SESSION['prenom']) ? $_SESSION['prenom'] : 'undefined' ,
	'pseudo' =>	isset($_SESSION['pseudo']) ? $_SESSION['pseudo'] : null ,
	'couleur' => isset($_SESSION['couleur']) ? $_SESSION['couleur'] : '#a0a0a0',
	'f5' => !empty($_SESSION['f5']) ? $_SESSION['f5'] : 0
];
?>
<script>
var compte = <?php echo json_encode($compte); ?>;
</script>

</body>
</html>