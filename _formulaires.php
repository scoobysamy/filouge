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
<body>

	<div class="text-center" style="margin-bottom:100px;">
		<h4>Services</h4>
		<p>Formulaire de création de section.</p>
	</div>
	<div class="container">
		<form>
			<div class="col-lg-offset-3 col-lg-6 text-center d-inline-block">
				<label>Veuillez choisir le nombre de formulaire.</label>
				<select class="" name="nombre_formulaire" style="width:10%; margin-bottom:100px;">
					<option value="0">&nbsp;</option>
				<?php for ($i = 1; $i < 11; $i++) : ?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php endfor; ?>
				</select>
			</div>
		</form>
	</div>

	<div id="retour" class="hidden">
		<div class="text-center" style="margin-bottom:100px; margin-top:100px;">
			<h4>Services</h4>
			<p>Saisir votre texte dans le(s) formulaire(s).</p>
		</div>
		<div class="container">
			<form action="traiter_le_contenu.php" method="post">
				<div id="boucle"><!-- Récipient pour les templates suivant le nombre choisi --></div>
				<div class="col-lg-12 text-center" style="margin-top:100px; margin-bottom:100px;">
					<button  type="submit" class="btn btn-primary btn-md button_services_remplissage" style="width:100%;">Valider</button>
				</div>
			</form>
		</div>
	</div>


	<p class="text-center"><a href="services_lecture.php" target="_self" class="btn btn-primary">Page d'affichage du contenu ajouté</a></p>

<?php
if ( file_exists( INC .'/footer.inc.php' ) ) include INC.'/footer.inc.php';

if ( file_exists( INC .'/scripts.inc.php' ) ) include INC.'/scripts.inc.php';
?>

	<script src="<?php echo VUE; ?>/js/lib/mustache.min.js"></script>

	<script id="template_pour_formulaire" type="text/html">
		<div class="col-lg-offset-3 col-lg-6 text-center">
			<div class="form-group">
				<label>Titre N°{{numero}} :</label>
				<input type="text" class="form-control" name="titre_formulaire_{{numero}}" id="titre_{{numero}}">
			</div>
			<div class="form-group">
				<label for="comment">Texte n°{{numero}} :</label>
				<textarea class="form-control" name="texte_formulaire_{{numero}}" rows="5" id="texte_{{numero}}"></textarea>
			</div>
			<hr>
		</div>
	</script>

	<script>
	/**
	 * Affiche un message d'alerte avec classes Bootstrap 3 (version JS)
	 * @param string msg : une chaîne de caractères
	 * @param string type : le type d'alerte parmi success, info, warning, danger
	 * @return string : Retourne le code html correspondant
	 * @author : DO Minh-Dung
	 */
	function alerter( msg , type ) {
		if ( [ 'success', 'info', 'warning', 'danger' ].indexOf( type ) == -1 ) {
			type = 'info';
		}
		code_html = '<div class="alert alert-'+type+' alert-dismissible" role="alert">'+"\n";
		code_html += "\t"+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+"\n";
		code_html += "\t"+'<strong>'+msg+'</strong>'+"\n";
		code_html += '</div>';
		return code_html;
	}

	// On met la définition de notre plug-in à l'intérieur d'une fonction anonyme et d'une closure.
	// Ceci pour éviter les conflits avec les autres bibliothèques JavaScript utilisant aussi le $
	( function ($) {

		var total = 0;
			tablo_moustachu = [] , // On initialise un tableau vide
			habillage_pour_formulaire = $("#template_pour_formulaire").html() , // Mise en cache du template pour un formulaire
			$retour = $("#retour") , // Mise en cache de l'objet jQuery non variable (memoizing)
			$boucle = $("#boucle") , // idem
			$selecteur_nombre = $("select[name='nombre_formulaire']"); // idem

		$selecteur_nombre.on("change", function(e) { // Gestionnaire de changement sur la sélection
			total = $(this).children("option:selected").val(); // on récupère la valeur sélectionnée de l'enfant direct
			tablo_moustachu.length = 0; // On vide le tableau tout en re-utilisant sa place déjà réservée en RAM
			total = (total*1); // Transforme une chaîne de caractères en nombre pour pas cher
			if ( total > 0 ) { // Si cette valeur est strictement positive
				for ( var i = 1 ; i <= total ; i++ ) { // On ajoute autant d'éléments que demandé
					tablo_moustachu.push( Mustache.render( habillage_pour_formulaire , {"numero": i} ) );
				}
				$boucle.html( tablo_moustachu.join("\n") ); // On recolle chaque élément du tableau pour l'afficher
				$retour.removeClass("hidden"); // Montre le résultat
				$(this).blur(); // Pour empêcher les boutons fléchés d'agir dessus juste après un choix
				$("html, body").stop().animate({ scrollTop: $retour.offset().top }, 1500, "easeInOutExpo");
			}
		});

		// On évite de dégrader les performances en attachant un gestionnaire d'événements à un formulaire encore inexistant
		$retour.on("submit", "form", function(e) { // Si on clique sur le bouton submit du formulaire
			e.preventDefault(); // On bloque le comportement par défaut
			var names_valeurs = $(this).serialize(); // On stocke tous les name et value
			// console.log("Chaîne à envoyer = ?"+names_valeurs); // On les affiche
			var courrier = $.post( "controle/" + $(this).attr("action") , names_valeurs+"&nb="+total );
			courrier.error( function(retour,statut) { console.log(statut); console.log(retour); } );
			courrier.success( function(retour,statut) {
				console.log(statut);
				var code_html = [];
				// console.log(JSON.stringify(retour));
				if ( typeof retour == "object" ) {
					for ( var i in retour ) {
						code_html.push( i+" : "+retour[i] );
					}
				}
				$("body").append( alerter("<ul><li>"+code_html.join("</li>\n<li>")+"</li></ul>" , "info") );
				$("html, body").stop().animate({ scrollTop: $(".button_services_remplissage").offset().top }, 1500, "easeInOutExpo");
			});
			// courrier.done( function(retour,statut) { $enregistrer.fadeOut(); } );
		});

	})(jQuery);
	</script>

</body>
</html>