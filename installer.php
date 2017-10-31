<?php
# Fixe le format des données via l’entête HTTP Content-Type,
# ainsi que l’encodage des caractères de cette page en UTF-8
header('Content-type: text/html ; charset=utf-8');
require_once __DIR__ .'/config/config.php';
require_once __DIR__ .'/classes/autoload.php';
require_once __DIR__ .'/debug.php';

if ( class_exists('Bdd') ) {
	$pdo = Bdd::instancier(); # On instancie un objet de connexion \PDO

	if ( false!=$pdo && class_exists('Requete') ) { # Si on peut le faire
		$demandeur = new Requete( $pdo ); # On instancie un gestionnaire de requêtes SQL
		if ( false!=$demandeur ) {
			$tables = $demandeur->listerTables();
			usort( $tables , ['Utile','compareLongueurChaine'] );
		}
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php include INC.'/metalink.inc.php'; ?>
<body>

	<main class="container text-center">
		<h2>Gestion des tables de la base de données</h2>
<?php
# Si on a reçu un POST et si on a pu se connecter à la base de données
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && $pdo ) {

	if ( !empty($_POST['hydrater']) ) {
		if ( $demandeur->executerFichier( __DIR__ .'/config/creerTables.sql' ) ) {
			alerter( 'Création des tables effectuée' , 'success' );
			usleep(700000); # Fait une pause de 0.7 secondes
			if ( $demandeur->executerFichier( __DIR__ .'/config/insererDonnees.sql' ) ) {
				alerter( 'Ajout du jeu de données effectuée' , 'success' );
				header( 'Refresh: 1, installer.php' );
			}
			else {
				alerter( 'Échec de l’ajout de données !!!', 'warning' );
			}
		}
		else
			alerter( 'Échec de création des tables !!!', 'warning' );
	}

	elseif ( !empty($_POST['supprimer']) && $_POST['supprimer'] == 'Supprimer la table choisie' ) {
		if ( $_POST['avirer'] !== '0' ) {
			$resultat = $demandeur->supprimerTable( $_POST['avirer'] );
			if ( $resultat ) {
				alerter( 'Suppression accomplie' , 'success' );
				header( 'Refresh: 1, installer.php' );
			}
			else {
				alerter( 'Suppression échouée !', 'warning' );
			}
		}
	}

	elseif ( !empty($_POST['toutsupprimer']) && $_POST['toutsupprimer'] == 'Supprimer toutes les tables' ) {
		$resultat = 0;
		foreach( $tables as $tab ) {
			$resultat += $demandeur->supprimerTable( $tab );
		}
		if ( $resultat == count($tables) ) {
			alerter( 'Suppressions accomplies' , 'success' );
			header( 'Refresh: 1, installer.php' );
		}
		else {
			alerter( 'Suppressions échouées !', 'warning' );
		}
	}

} # FIN DE LA GESTION DES POST
?>
		<section id="hydratation" class="well text-center">
			<form action="<?php echo ACTION; ?>" method="post">
				<input type="submit" class="btn btn-primary" name="hydrater" value="Créer les tables de la base de données + ajouter quelques données">
			</form>
		</section>
		<section class="row well text-center">
			<div class="text-center col-lg-9 col-md-7 col-sm-7 col-xs-12">
				<div class="panel-group" id="accordeon">
				<?php
				if ( !empty($tables) && is_array($tables) ) :
					foreach( $tables as $nom ) :
				?>
					<div class="panel panel-primary">
						<div class="panel-heading" style="padding: 2px 0;">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordeon" style="display: inline-block; padding: 2px; width: 100%;" href="#<?php echo $nom; ?>"><?php echo $nom; ?></a>
							</h4>
						</div>
						<div id="<?php echo $nom; ?>" class="panel-collapse collapse">
							<div class="panel-body text-center center-block">
							<?php echo afficheTab( $demandeur->listerChamps( $nom ) ); ?>
							</div>
						</div>
					</div>
				<?php endforeach; endif; ?>
				</div><!-- FIN DE L'ACCORDÉON -->
			</div>
			<form action="<?php echo ACTION; ?>" method="post" class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
				<div class="form-group">
					<select name="avirer" class="form-control">
						<option readonly="readonly" value="0">Choisir une table</option>
						<?php
						if ( !empty($tables) && is_array($tables) ) :
							foreach( $tables as $nom ) :
						?>
							<option value="<?php echo $nom; ?>"><?php echo $nom; ?></option>
						<?php endforeach; endif; ?>
					</select>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" name="supprimer" value="Supprimer la table choisie" onclick="javascript: if ( confirm('Confirmer la suppression ?') ) return true; else return false;">
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" name="toutsupprimer" value="Supprimer toutes les tables" onclick="javascript: if ( confirm('Confirmer la suppression ?') ) return true; else return false;">
				</div>
			</form>

		</section>

		<div class="text-center">
			<a href="./" target="_self">Aller à la page d’accueil</a>
		</div>

	</main>

	<?php include INC.'/scripts.inc.php'; ?>
</body>
</html>
