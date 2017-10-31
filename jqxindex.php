<?php
# Fixe le format des données via l’entête HTTP Content-Type,
# ainsi que l’encodage des caractères de cette page en UTF-8
header('Content-type: text/html ; charset=utf-8');
# Initialisation de la configuration du site
require_once __DIR__ .'/config/config.php';
# Chargement des classes personnelles
# (impérativement AVANT session_start() sinon on aura des objets incomplets)
require_once RACINE .'/classes/autoload.php';
# Chargement de quelques fonctions de débuggage (à supprimer en production)
require_once RACINE .'/debug.php';
# Démarrage ou reprise d’une session existante
session_start();

# Si la classe de connexion à la base de données existe
if ( class_exists('Bdd') ) {
	# On instancie un objet de connexion \PDO
	$pdo = Bdd::instancier();

	if ( false!=$pdo && class_exists('Requete') ) { # Si on peut le faire
		// $demandeur = new Requete( $pdo ); # On instancie un gestionnaire de requêtes SQL
		// $tables = $demandeur->listerTables();
		// usort( $tabldem	nes , ['Utile','compareLongueurChaine'] );
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<?php include INC.'/metalink.inc.php'; ?>
	<title>Système de gestion de formation (ou Formaneed ?)</title>
	<meta name="description" content="système de gestion, de planification, de sessions de formation">
</head>
<body class='default'>

	<main class="container text-center">
		<div id="scheduler"></div>

		<?php include INC.'/scripts.inc.php'; ?>
		<script src="<?=VUE?>/js/jqx_compil.js"></script>
		<script src="<?=VUE?>/js/donnees.js"></script>

	</main>

</body>
</html>
