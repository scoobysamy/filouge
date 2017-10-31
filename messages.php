<?php
# Fixe le format des données via l’entête HTTP Content-Type,
# ainsi que l’encodage des caractères de cette page en UTF-8
// header('Content-type: text/html ; charset=utf-8');
# À ne pas oublier sinon ça renvoie une chaîne de caractères
header("Content-type: application/json");
# Initialisation de la configuration du site
require(__DIR__ .'/config/config.php');
# Chargement des classes personnelles
# (impérativement AVANT session_start() sinon on aura des objets incomplets)
require_once RACINE .'/classes/autoload.php';
# Chargement de quelques fonctions de débuggage (à supprimer en production)
require_once RACINE .'/debug.php';
# Démarrage ou reprise d’une session existante
session_name('Session_de_Chat'); # Nom de ma session à la place de PHPSESSID
session_start(); # On démarre une nouvelle session ou on reprend une session existante

# Si la classe de connexion à la base de données existe
if ( class_exists('Bdd') ) {
	# On instancie un objet de connexion \PDO
	$pdo = Bdd::instancier();
	if ( false!=$pdo && class_exists('ManageurDynamique') ) { # Si on peut le faire
		// header('Content-type: text/html ; charset=utf-8');
		$admin = new ManageurDynamique( $pdo ); # On instancie un gestionnaire dynamique
		// $membre = $admin->selectionner( 'compte', 'idPersonne', 1 );
		// vd($membre);
		// $membre = $admin->chercher( 'compte', array( 'pseudo'=>'mdo', 'mdp'=>sha1('31415926') ), array( 'personne'=>'idPersonne' ) );
		// vd($membre);
		$messager = new ManageurDeMessage( $pdo ); # On instancie un gestionnaire de messages
		// $res = $messager->listerMessages( '2017-10-22' );
		// $res = array_map( function($val) { return (array) $val(); } , $res );
		// vd($res);
		// echo json_encode( $res );
		// $tables = $admin->lister('compte');
		// usort( $tables , ['Utile','compareLongueurChaine'] );
	}
}

# Si on a pu se connecter à la base de données et s’il y a une requête HTTP par méthode POST
if ( false!=$admin && $_SERVER['REQUEST_METHOD'] === "POST" ) {

	if ( isset($_SESSION['notification']) ) {
		# On supprime le couple clef => valeur lié à une notification
		unset( $_SESSION['notification'] );
	}

	extract($_POST);

	// require __DIR__ .'/inc/utilisateur.classe.php'; # Chargement de la classe de gestion des utilisateurs

	# =================================================================================================== #
	# oxxxxx][ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ> S'il y a une demande de lecture des messages <ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ][xxxxxo #
	# =================================================================================================== #
//	if ( isset($_POST['renifleur']) && ( $_POST['renifleur'] == 'untruc' ) && isset($_POST['periode']) && !empty($_POST['periode']) ) { # Si la clef 'renifleur' est valide
	if ( isset($renifleur) && ( $renifleur == 'untruc' ) ) { # Si la clef 'renifleur' est valide
		// require __DIR__ .'/inc/message.classe.php'; # Chargement de la classe de gestion des messages
		// verif($renifleur.'__'.$periode);
		// $listing = array();
		# On retourne les messages récupérés de la base de données
		/*
		if ( !empty( $periode ) && Utile::dateValide( $periode , 'Y-m-d' ) ) {
			$listing = $messager->listerMessages( $periode );
			echo json_encode( $messager->listerMessages( $periode ) );
		}
		else {
			echo json_encode( $messager->listerMessages('_') );
		}
		*/
		/*
		if ( isset( $periode ) ) {
			$listing = array_map( function($val) { return (array) $val(); } , $messager->listerMessages( $periode ) );
		}
		echo json_encode( $listing );
		*/
		# http://stackoverflow.com/questions/8993971/php-strftime-french-characters
		// echo date('Y-m-d H:i:s');
		// echo utf8_encode(strftime('%A %d %B %Y', strtotime(date('Y-m-d H:i:s')))); # Pour afficher le û du Août
		echo json_encode( $messager->listerMessages( $periode ) );
	}
	# ============================================================================================================== #
	# oxxxxx][ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ> Si les login et mot de passe existent en base <ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ][xxxxxo #
	# ============================================================================================================== #
	elseif ( !empty( $pseudo ) && isset( $mdp ) && preg_match( '~^([\w\s\@\!\$\£\µ\.\,\+-]{2,})$~iu' , $pseudo ) ) {
		# On regarde s’ils se trouvent dans la base de données
		$membre = $admin->chercher( 'compte', array( 'pseudo'=>$pseudo, 'mdp'=>sha1($mdp) ), array( 'personne'=>'idPersonne' ) );
		// $membre = $membre[0];
		// verif( $membre );
		# Si on a une identification positive
		if ( isset($membre[0]) && is_object( $membre[0] ) && get_class( $membre[0] )=='Compte' ) {
			// utilisateur::maj( array('etat'=>1) , $membre['id'] ); # on déclare sa présence dans la base
			// $_SESSION['id'] = $membre[0]; # On valide l’instance de classe Compte
			$_SESSION['id'] = (int) $membre[0]->idPersonne(); # On valide le pseudo
			$_SESSION['pseudo'] = $membre[0]->pseudo(); # On valide le pseudo
			$_SESSION['nom'] = $membre[0]->nom(); # On valide le nom
			$_SESSION['prenom'] = $membre[0]->prenom(); # On valide le prénom
			$_SESSION['couleur'] = $membre[0]->couleur(); # On valide la couleur associée
			# Toute première fois, tout-toute première fois qu'on se connecte
			// verif( $_SESSION['id'] );
			if( !isset($_SESSION['adresse_ip']) ) { # Si aucune adresse IP n'a été stockée
				# On stocke l'adresse IP
				$_SESSION['adresse_ip'] = $_SERVER['REMOTE_ADDR']; 
				# On instancie un nouvel objet DateTime avec clés : date , timezone_type , timezone
				$_SESSION['date_de_debut'] = new DateTime; 
			}
			// file_put_contents( 'datemaj.texto' , date( 'Y-m-d H:i:s' , time() ) ); # On note un changement ? Pas encore
			$_SESSION['ok'] = true; # et on autorise l'accès
			$_SESSION['f5'] = 0; # On annule au passage les multiples rechargements de la page
			if (isset($_SESSION['nouveau'])) # Si on vient juste de valider son inscription
				unset($_SESSION['nouveau']); # On supprime le couple clef => valeur lié à une nouvelle inscription, en session
		} elseif (isset($_SESSION['tentatives'])) { # Si le couple ( pseudo / mot de passe ) n'a pas été trouvé
			$_SESSION['tentatives']++; # on incrémente le compte de tentatives de connexion
		}
		echo json_encode( $_SESSION ); # Il faut renvoyer quelque chose car l'ajax attend un retour, sinon parsererror
	}
	# ====================================================================================================== #
	# oxxxxx][ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ> Si on cherche à inscrire un nouvel utilisateur <ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ][xxxxxo #
	# ====================================================================================================== #
	elseif ( isset($_POST['inputPseudo']) && !empty($_POST['inputPseudo']) && isset($_POST['inputMdp1']) && !empty($_POST['inputMdp1']) ) {
		# $donnees respecte le format de la table 'utilisateurs' : id, nom, prenom, pseudo, motdepasse, couleur, ip, etat
		$donnees = array( 
			'nom'=>'au bataillon',
			'prenom'=>'inconnu',
			'pseudo'=>'',
			'motdepasse'=>'', 
			'couleur'=>'#dddddd', 
			'ip'=>$_SERVER['REMOTE_ADDR']
			);
		# On teste si le nom fourni est valide (2 minimum caractères alphabétiques espace et tiret)
		if ( isset($_POST['inputNom']) && !empty( $_POST['inputNom'] ) && is_string($_POST['inputNom']) 
			&& preg_match( '~^([a-z\s-]{2,})$~iu' , $_POST['inputNom'] ) ) {
			$donnees['nom'] = $_POST['inputNom'];
		}
		# On teste si le prénom fourni est valide (2 minimum caractères alphabétiques espace et tiret)
		if ( isset($_POST['inputPrenom']) && !empty( $_POST['inputPrenom'] ) && is_string($_POST['inputPrenom']) 
			&& preg_match( '~^([a-z\s-]{2,})$~iu' , $_POST['inputPrenom'] ) ) {
			$donnees['prenom'] = $_POST['inputPrenom'];
		}
		# On teste si le pseudo fourni est valide (au moins 2 caractères alphanumériques et + _ @ ! $ £ µ . , - espace)
		if ( isset($_POST['inputPseudo']) && !empty( $_POST['inputPseudo'] ) && is_string($_POST['inputPseudo']) 
			&& preg_match( '~^([\w\s\@\!\$\£\µ\.\,\+-]{2,})$~iu' , $_POST['inputPseudo'] )
			&& !utilisateur::identifier( array('pseudo'=>$_POST['inputPseudo']) ) ) { # Si le pseudo n'est pas déjà utilisé
			$donnees['pseudo'] = $_POST['inputPseudo'];
		}
		# On teste si les mots de passe fournis sont valides (au moins 6 caractères alphanumériques et + _ @ ! $ £ µ . ,-)
		if ( isset($_POST['inputMdp1']) && !empty( $_POST['inputMdp1'] ) && is_string($_POST['inputMdp1']) 
			&& isset($_POST['inputMdp2']) && !empty( $_POST['inputMdp2'] ) && is_string($_POST['inputMdp2']) 
			&& ($_POST['inputMdp1'] === $_POST['inputMdp2']) && preg_match( '~^([\w\@\!\$\£\µ\.\,\+-]{6,})$~iu' , $_POST['inputMdp1'] ) ) {
			$donnees['motdepasse'] = $_POST['inputMdp1']; # Encryptage md5 dans la classe utilisateur
		}
		# On teste si la couleur fournie est valide
		if ( isset($_POST['inputCouleur']) && !empty( $_POST['inputCouleur'] ) && is_string($_POST['inputCouleur'])
			&& preg_match( '~^#[0-9a-f]{6}$~i' , $_POST['inputCouleur'] ) ) { # Doit respecter le format #D90115
			$donnees['couleur'] = $_POST['inputCouleur'];
		}
		$_SESSION['nouveau'] = ''; // Valeur par défaut
		if ( $donnees['pseudo']!='' && $donnees['motdepasse']!='' ) { # Si les pseudo et mot de passe ont bien été correctement renseignés
			$membre = new utilisateur( $donnees );
			if ( $membre->inscrire() ) # Alors si parvient à stocker en base
				$_SESSION['nouveau'] = $donnees['pseudo']; # on indique qu'un nouveau est arrivé
		}
		if (isset($_SESSION['tentatives'])) { # Si le compteur de tentatives de connexion a été défini
			$_SESSION['tentatives'] = 0; # On le re-initialise au passage
		}
		// file_put_contents( '../inscription_verification.txt' , implode("\n",$donnees) ); # Vérification des données
		echo json_encode( $_SESSION ); # Il faut renvoyer quelque chose car l'ajax attend un retour, sinon parsererror
	}
	# ======================================================================================================= #
	# oxxxxx][ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ> S'il y a simplement un changement de couleur <ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ][xxxxxo #
	# ======================================================================================================= #
	# id de session + pas de clef message + couleur fournie
	elseif ( !empty($_SESSION['id']) && !empty($couleur) ) { 
		if ( preg_match( '~^#[0-9a-f]{6}$~i' , $couleur ) ) { # Si la couleur est écrite au bon format
			// $_SESSION['couleur'] = $couleur; # On reporte le changement de couleur en session
			$_SESSION['couleur'] = $couleur; # On reporte le changement de couleur en session
			// $admin->enregistrer( $_SESSION['id'] ); # On met à jour le compte
			file_put_contents( RACINE .'/datemaj' , ' ' ); # On modifie un fichier avec un peu de texte
		}
		echo json_encode( $_SESSION ); # Il faut renvoyer quelque chose car l'ajax attend un retour, sinon parsererror
	}
	# ============================================================================================================= #
	# oxxxxx][ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ> S'il y a une demande de changement de mot de passe <ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ][xxxxxo #
	# ============================================================================================================= #
	elseif ( isset($_SESSION['id']) && !empty($_SESSION['id']) && isset($_POST['mdp']) && !empty($_POST['mdp']) ) { # id de session + pas de clef message + mots de passe fournis
		$_SESSION['notification'] = 'Mot de passe erroné ou format non valide';
		$mdp = explode(' ',$_POST['mdp']);
		if ( preg_match( '~^([\w\@\!\$\£\µ\.\,\+-]{6,})$~iu' , $mdp[0] ) # Si l'ancien mot de passe est écrit au bon format
			&& preg_match( '~^([\w\@\!\$\£\µ\.\,\+-]{6,})$~iu' , $mdp[1] ) # Si le nouveau mot de passe est écrit au bon format
			&& utilisateur::identifier( array('id'=>$_SESSION['id'], 'motdepasse'=>md5($mdp[0])) ) ) {
			utilisateur::maj( array('motdepasse'=>sprintf('%s', sha1($mdp[1]))), intVal($_SESSION['id']) ); # On met à jour la table utilisateurs
			$_SESSION['notification'] = 'Changement de mot de passe effectué';
		}
		echo json_encode( $_SESSION ); # Il faut renvoyer quelque chose car l'ajax attend un retour, sinon parsererror
	}
	# ============================================================================================================ #
	# oxxxxx][ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ> S'il y a un nouveau message avec id de session <ΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞΞ][xxxxxo #
	# ============================================================================================================ #
	elseif ( !empty($_POST['quitter']) ) { # S'il y a une demande de départ
		verif($quitter);
		if ( file_exists( INC .'/detruireSession.inc.php' ) ) {
			include INC .'/detruireSession.inc.php';
		}
		# On renvoie quelque chose car l'ajax attend un retour, sinon 
		echo json_encode( ['ok'=>'bye'] ); 
		// echo json_encode( ['ok'=>'undefined'] , JSON_HEX_QUOT | JSON_HEX_TAG ); 
	}
	elseif ( !empty( $_SESSION['id'] ) && !empty( $message ) ) { # id de session + message existant non vide
		if ( isset($_POST['nick']) && !empty( $_POST['nick'] ) && is_string($_POST['nick']) # Si un pseudo est envoyé
			&& preg_match( '~^([\w\s\@\!\$\£\µ\.\,\+-]{2,})$~iu' , $_POST['nick'] ) ) { # Si le pseudo respecte le format
			if ( !utilisateur::identifier( array('pseudo'=>$_POST['nick']) ) ) { # Si le pseudo n'est pas déjà utilisé
				utilisateur::maj( array('pseudo'=>sprintf('%s',$_POST['nick'])), intVal($_SESSION['id']) );
				$_SESSION['pseudo'] = $_POST['nick']; # On reporte le changement de patronyme en session
			} else
				$_POST['message'] = '/moi a tenté de prendre un pseudo déjà pris'; # On change la commande
		}
		# On modifie un fichier, pour ensuite vérifier si la table 'message' est mise à jour, sans avoir à faire de requête SELECT
		file_put_contents( RACINE .'/datemaj' , ' ' );
		// require __DIR__ .'/inc/message.classe.php'; # Chargement de la classe de gestion des messages
		if ( empty($quitter) && false!= $messager ) {
			$msg = new Message( '', 1, $_SESSION['id'] , date('Y-m-d H:i:s'), $message ); # Datation automatique
			$messager->ajouterMessage( $msg );
		}
		// if ( $msg->ajouter() ) $_SESSION['notification'] = 'Message ajouté';
		// else
			// $_SESSION['notification'] = 'Message non ajouté';
		if ( !empty($quitter) ) { # S'il y a une demande de départ
			$_SESSION['notification'] = $quitter;
			// utilisateur::maj( array('etat'=>0) , intVal($_SESSION['id']) ); # On met à jour l'état de présence
			$_SESSION['ok'] = false; # On se contente d’invalider l’accès
			try {
				session_destroy(); # Détruit toutes les données associées à la session courante, mais pas le cookie de session, ni les variables globales associées
# PHP Warning:  session_destroy() [<a href='function.session-destroy'>function.session-destroy</a>]: Session object destruction failed in C:\wamp\www\chatv7\messages.php on line 165
				session_start(); # Démarre une nouvelle session
				session_regenerate_id(false); # Remplace l’identifiant de session courant par un nouveau
				setcookie(session_name(),'',0,'/'); # Détruit le cookie de session
			} catch( Exception $e ) { }
		}
		echo json_encode( $_SESSION , JSON_HEX_QUOT | JSON_HEX_TAG ); # On renvoie quelque chose car l'ajax attend un retour, sinon parsererror
		# http://php.net/manual/fr/json.constants.php
	}

}