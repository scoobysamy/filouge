<?php
# Fixe le format des données via l’entête HTTP Content-Type,
# ainsi que l’encodage des caractères de cette page en UTF-8
header('Content-Type: text/html ; charset=utf-8');
require_once __DIR__ .'/config/config.php';
require_once __DIR__ .'/debug.php';
# A charger impérativement AVANT session_start() sinon objets incomplets
require_once __DIR__ .'/classes/autoload.php'; # Auto-chargement de mes classes à moi
// session_start(); # Démarrage ou reprise d'une session existante

include RACINE.'/vue/enteteHtml.inc.php';

?>
<main class="container-fluid" style="font-size: 1.1em;">

	<h2>Cahier des charges</h2>

	<section class="well">
		<h3>Fonctionnalités pour le stagiaire :</h3>
		<ul>
			<li><h4>Agir sur un menu de navigation classique en haut, avec, à gauche, des liens vers :</h4>
				<ol>
					<li>la page d’accueil</li>
					<li>la page de planning des cours avec un agenda</li>
					<li>la liste des sessions</li>
					<li>la liste des membres inscrits</li>
					<li>les QCM</li>
					<li>les commentaires</li>
				</ol>
				<h4>et liens à droite sur un pictogramme (avec burger) avec sous-menu déroulant pour accéder au :</h4>
				<ol>
					<li>détail du profil</li>
					<li>bouton de déconnexion</li>
					<li>choix de la langue d’affichage, par simple clic sur une mini-image de drapeau</li>
				</ol>
			</li>
			<li><h4>Consulter une page d’accueil actualisée, avec le ou les cours du jour liés à LA session à laquelle il s’est inscrit, avec tous les détails :</h4>
				<ol>
					<li>l’intitulé du cours,</li>
					<li>le nom du formateur,</li>
					<li>le descriptif,</li>
					<li>les horaires,</li>
					<li>le nombre d’heures,</li>
					<li>le numéro identifiant de la salle de cours,</li>
					<li>l’étage,</li>
					<li>la liste des autres étudiants inscrits,</li>
					<li>les mots clefs associés cliquables pour afficher d’autres cours en relation avec le cours actuel présent</li>
				</ol>
			</li>
			<li><h4>Consulter le planning de la formation, à partir d’un agenda responsive design en html5, ajax, bootstrap, contenant :</h4>
				<ol>
					<li>L'intitulé du mois en cours</li>
					<li>Des colonnes indiquant un des 7 jours de chaque semaine, en gras et centré</li>
					<li>Des cellules représentant chaque jour d’un mois courant avec numéro du jour, en haut à droite de chaque cellule</li>
					<li>Un lien cliquable, pour chaque cellule jour, menant à une fenêtre modale affichant les détails du cours du jour au clic (intitulé, formateur, descriptif,...)</li>
					<li>Un bouton précédent menant au mois précédent le mois affiché</li>
					<li>Un bouton aujourd’hui pour ramener à une page affichant les jours du mois lié au jour d’aujourd’hui</li>
					<li>Un bouton suivant menant au mois suivant le mois affiché</li>
				</ol>
			</li>
			<li><h4>Consulter la liste des sessions :</h4>
				<ol>
					<li>chaque référence</li>
					<li>chaque intitulé</li>
					<li>chaque nombre d’heures</li>
					<li>chaque salle</li>
					<li>la liste des autres étudiants inscrits</li>
					<li>chaque date de début</li>
					<li>chaque date de fin</li>
					<li>chaque description</li>
					<li>chaque formateur</li>
					<li>les liens directs vers les supports de cours au format PDF, avec contrôle de lien parent</li>
					<li>Pour chaque colonne, un lien de classification ascendant ou descendant</li>
					<li>Une éventuelle pagination</li>
					<li>un bouton d’actualisation</li>
					<li>un bouton de recherche d’une session par mots clefs séparés par une virgule à partir d’un formulaire offrant des choix multiples de recherche (intitulé de cours, mots clefs, formateur)</li>
					<li>Converser sur un salon</li>
				</ol>
			</li>
			<li><h4>Consulter la liste des membres inscrits avec :</h4>
				<ol>
					<li>Un titre</li>
					<li>un bouton d’actualisation</li>
					<li>un filtre de recherche d’une personne par nom ou prénom</li>
					<li>ID,</li>
					<li>prénom,</li>
					<li>nom,</li>
					<li>login,</li>
					<li>adresse e-mail,</li>
					<li>statut stagiaire</li>
					<li>Pour chaque colonne, un lien de classification ascendant ou descendant</li>
					<li>Une éventuelle pagination</li>
				</ol>
			</li>
			<li><h4>Afficher la liste des QCM, avec en colonnes :</h4>
				<ol>
					<li>Un titre</li>
					<li>Un bouton d’actualisation</li>
					<li>un champ nom</li>
					<li>un champ session</li>
					<li>un champ pour les tentatives restantes</li>
					<li>un champ pour la note</li>
					<li>un bouton pour passer le QCM</li>
				</ol>
			</li>
			<li><h4>Répondre au QCM choisi, avec :</h4>
				<ol>
					<li>un titre correspondant au choix</li>
					<li>un numéro de question avec la question</li>
					<li>une réponse par ligne avec boutons radio ou à choix multiples</li>
					<li>un bouton de validation des réponses</li>
					<li>ou bien un bouton de validation par réponse</li>
				</ol>
			</li>
			<li><h4>Consulter la liste des sessions à évaluer avec :</h4>
				<ol>
					<li>un champ nom</li>
					<li>un bouton pour accéder à un formulaire d’évaluation pour chaque session</li>
				</ol>
			</li>
			<li><h4>Accéder à une page d’évaluation, par session avec :</h4>
				<ul>
					<li>un titre de session avec le nom de la personne</li>
					<li><ol>
							<li><strong>L’environnement</strong>
								<ol>
									<li>L’accueil, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Les salles de formation, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Le matériel informatique, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Le confort de travail, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Commentaires sur l’environnement, avec un champ textarea</li>
								</ol>
							</li>
							<li><strong>Le contenu des cours</strong>
								<ol>
									<li>Richesse du cours, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Niveau adapté à vos attentes, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Choix des exercices et des exemples, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Logique du scénario de cours, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Les supports de cours, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Rythme de progression, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Présentation théorique, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Applications pratiques, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Répartition entre théorie et pratique, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Autres, avec un champ textarea</li>
									<li>Sujets à approfondir ou à ajouter, avec un champ textarea</li>
								</ol>
							</li>
							<li><strong>Le formateur</strong>
								<ol>
									<li>Compétences techniques, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Qualités de communication, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Capacité d’écoute, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Disponibilité, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Connaissance du scénario de cours, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Commentaires sur le formateur, avec un champ textarea</li>
								</ol>
							</li>
							<li><strong>Objectifs</strong>
								<ol>
									<li>Est-ce que le stage a atteint ses objectifs, avec 1 à 5 étoiles au choix ★★★★★</li>
									<li>Suggestions d’améliorations, avec un champ textarea</li>
								</ol>
							</li>
						</ol>
					</li>
				</ul>
			</li>
			<li><h4>Une page de profil utilisateur, avec :</h4>
				<ol>
					<li>une image par défaut dans un rond,</li>
					<li>un bouton d’ajout/modification de photo</li>
					<li>un formulaire d’envoi d’image,</li>
					<li>un texte court précisant le poids et les dimensions max à respecter</li>
					<li>un message d’avertissement</li>
					<li>un gestionnaire de redimensionnement intégré d’image pour égaliser largeur et hauteur</li>
					<li>une section infos classiques de compte utilisateur avec :
						<ol>
							<li>ID</li>
							<li>prénom</li>
							<li>nom</li>
							<li>login</li>
							<li>mot de passe</li>
							<li>adresse e-mail</li>
							<li>statut stagiaire</li>
						</ol>
					</li>
				</ol>
			</li>
		</ul>
	</section>
	<hr>
	<section class="well">
		<p>Penser à mettre des pictogrammes FontAwesome ici et là sur tous les liens pour faire sympatoche</p>
	</section>
	<hr>
	<section class="well">
		<h3>Fonctionnalités pour le formateur :</h3>
		<ul>
			<li><h4>Agir sur un menu de navigation classique en haut, avec, à gauche, des liens vers :</h4>
				<ol>
					<li>la page d’accueil</li>
					<li>la page de planning des cours avec un agenda</li>
					<li>la liste des sessions</li>
					<li>la liste des membres inscrits</li>
					<li>les QCM</li>
					<li>les commentaires</li>
				</ol>
				<h4>et liens à droite sur un pictogramme (avec burger) avec sous-menu déroulant pour accéder au :</h4>
				<ol>
					<li>détail du profil</li>
					<li>bouton de déconnexion</li>
					<li>choix de la langue d’affichage, par simple clic sur une mini-image de drapeau</li>
				</ol>
			</li>
			<li><h4>Consulter une page d’accueil actualisée, avec le ou les cours du jour à donner avec tous les détails :</h4>
				<ol>
					<li>l’intitulé du cours,</li>
					<li>le descriptif,</li>
					<li>les horaires,</li>
					<li>le nombre d’heures,</li>
					<li>le numéro identifiant de la salle de cours,</li>
					<li>l’étage,</li>
					<li>la liste des étudiants inscrits</li>
				</ol>
			</li>
			<li><h4>Consulter et éditer le planning de la formation, à partir d’un agenda responsive design en html5, ajax, bootstrap, contenant :</h4>
				<ol>
					<li>L'intitulé du mois en cours</li>
					<li>Des colonnes indiquant un des 7 jours de chaque semaine, en gras et centré</li>
					<li>Des cellules représentant chaque jour d’un mois courant avec numéro du jour, en haut à droite de chaque cellule</li>
					<li>Un lien cliquable, pour chaque cellule jour, menant à une fenêtre modale affichant les détails du cours du jour au clic (intitulé, formateur, descriptif,...)</li>
					<li>Un bouton précédent menant au mois précédent le mois affiché</li>
					<li>Un bouton aujourd’hui pour ramener à une page affichant les jours du mois lié au jour d’aujourd’hui</li>
					<li>Un bouton suivant menant au mois suivant le mois affiché</li>
				</ol>
			</li>
			<li><h4>Consulter la liste des sessions de cours :</h4>
				<ol>
					<li>chaque référence</li>
					<li>chaque intitulé</li>
					<li>chaque nombre d’heures</li>
					<li>chaque horaire</li>
					<li>chaque salle</li>
					<li>la liste des étudiants inscrits dans une liste déroulante</li>
					<li>chaque date de début</li>
					<li>chaque date de fin</li>
					<li>chaque description</li>
					<li>chaque formateur</li>
					<li>les liens directs vers les supports de cours au format PDF, avec contrôle de lien parent</li>
					<li>Pour chaque colonne, un lien de classification ascendant ou descendant</li>
					<li>Une éventuelle pagination</li>
					<li>un bouton d’actualisation</li>
					<li>un bouton de recherche d’une session par mots clefs séparés par une virgule à partir d’un formulaire offrant des choix multiples de recherche (intitulé de cours, mots clefs, formateur)</li>
					<li>Converser sur un salon pour répondre aux questions des stagiaires</li>
				</ol>
			</li>
			<li><h4>Ajouter une nouvelle session de cours, à partir d’un formulaire :</h4>
				<ol>
					<li>chaque référence</li>
					<li>chaque intitulé</li>
					<li>chaque nombre d’heures</li>
					<li>chaque salle</li>
					<li>chaque date de début</li>
					<li>chaque date de fin</li>
					<li>chaque description</li>
					<li>un formulaire pour déposer un support de cours au format PDF</li>
					<li>pour chaque colonne, un lien de classification ascendant ou descendant</li>
					<li>une éventuelle pagination</li>
					<li>un bouton d’actualisation</li>
					<li>un bouton de recherche d’une session par mots clefs séparés par une virgule à partir d’un formulaire offrant des choix multiples de recherche (intitulé de cours, mots clefs)</li>
					<li>Converser sur un salon pour répondre aux questions des stagiaires</li>
				</ol>
			</li>
			<li><h4>Consulter la liste des membres inscrits avec :</h4>
				<ol>
					<li>un titre</li>
					<li>un bouton d’actualisation</li>
					<li>un filtre de recherche d’une personne par nom ou prénom</li>
					<li>ID,</li>
					<li>prénom,</li>
					<li>nom,</li>
					<li>adresse e-mail,</li>
					<li>statut stagiaire</li>
					<li>liste des cours dans lesquels le stagiaire est inscrit</li>
					<li>Pour chaque colonne, un lien de classification ascendant ou descendant</li>
					<li>Une éventuelle pagination</li>
				</ol>
			</li>
			<li><h4>Afficher la liste des QCM, avec en colonnes :</h4>
				<ol>
					<li>un titre de session</li>
					<li>un bouton d’actualisation</li>
					<li>un champ nom de candidat</li>
					<li>un champ pour la note</li>
					<li>un bouton pour éditer le QCM</li>
				</ol>
			</li>
			<li><h4>Proposer un QCM sur un cours existant</h4>
				<ol>
					<li>un titre de session</li>
					<li>un bouton d’actualisation de l’affichage</li>
					<li>un champ pour affecter le nombre de question</li>
					<li>un bouton pour ajouter un QCM pour cette session-là</li>
				</ol>
			</li>
			<li><h4>Ajouter chaque question au QCM choisi, avec :</h4>
				<ol>
					<li>un titre correspondant au choix</li>
					<li>un numéro de question avec la question</li>
					<li>un bouton d’ajout de proposition de réponse</li>
					<li>des boutons à choix multiples pour indiquer la ou les bonnes réponses</li>
					<li>un bouton d’ajout à la base</li>
				</ol>
			</li>
			<li><h4>Consulter la liste des sessions à commenter avec :</h4>
				<ol>
					<li>un champ nom</li>
					<li>un bouton pour accéder à un formulaire pour répondre aux commentaires de chaque session</li>
				</ol>
			</li>
			<li><h4>Accéder à une page d’évaluation permettant d’évaluer chaque stagiaire avec :</h4>
				<h5>un titre avec le nom de la personne</h5>
				<ul>
					<li>Titre de la session 1, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Titre de la session 2, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Titre de la session 3, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Titre de la session 4, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Titre de la session 5, avec 1 à 5 étoiles au choix ★★★★★</li>
					<Li>...</Li>
					<Li>...</Li>
					<li>Compétences techniques, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Qualités de communication, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Capacité d’assimilation, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Participation, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Connaissance du cours, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Avis général sur la personne, avec un champ textarea</li>
				</ul>
			</li>
			<li><h4>Accéder à une page d’évaluation permettant de noter le groupe sur chaque session, avec :</h4>
				<ul>
					<li>Titre de la session 1, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Titre de la session 2, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Titre de la session 3, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Titre de la session 4, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Titre de la session 5, avec 1 à 5 étoiles au choix ★★★★★</li>
					<Li>...</Li>
					<Li>...</Li>
					<li><strong>Objectifs</strong>
					<li>Est-ce que ce cours a atteint ses objectifs, avec 1 à 5 étoiles au choix ★★★★★</li>
					<li>Suggestions d’améliorations, avec un champ textarea</li>
				</ul>
			</li>
			<li><h4>Une page de profil utilisateur, avec :</h4>
				<ol>
					<li>une image par défaut dans un rond,</li>
					<li>un bouton d’ajout/modification de photo</li>
					<li>un formulaire d’envoi d’image,</li>
					<li>un texte court précisant le poids et les dimensions max à respecter</li>
					<li>un message d’avertissement</li>
					<li>un gestionnaire de redimensionnement intégré d’image pour égaliser largeur et hauteur</li>
					<li>une section infos classiques de compte utilisateur avec :
						<ol>
							<li>ID</li>
							<li>prénom</li>
							<li>nom</li>
							<li>login</li>
							<li>mot de passe</li>
							<li>adresse e-mail</li>
							<li>statut formateur</li>
						</ol>
					</li>
				</ol>
			</li>
		</ul>
	</section>
	<hr>
	<section class="well">
		<h3>Fonctionnalités pour l’administrateur, le modérateur ou le gestionnaire de comptes :</h3>
		<ul>
			<li>S’il reste du temps...</li>
		</ul>
	</section>

	<aside>
		<pre>
PDF :
http://www.fpdf.org/
https://packagist.org/packages/dompdf/dompdf

MAILER :
https://packagist.org/packages/swiftmailer/swiftmailer

API pour lateX
http://math.et.info.free.fr/TikZ/bdd/TikZ-Impatient.pdf

Génération du code SQL de la base de données
DB Designer en ligne

AGENDA :
https://code.tutsplus.com/articles/15-best-php-calendar-booking-events-scripts--cms-28635
https://ourcodeworld.com/articles/read/55/top-5-best-jquery-scheduler-and-events-calendar-for-web-applications
https://github.com/Serhioromano/bootstrap-calendar
https://fullcalendar.io/js/fullcalendar-2.6.1/demos/agenda-views.html
https://alloyui.com/examples/scheduler/real-world
https://stackoverflow.com/questions/45781072/how-to-localize-the-alloyui-scheduler-component
https://docs.dhtmlx.com/scheduler/how_to_start.html
https://docs.dhtmlx.com/scheduler/samples/05_calendar/06_recurring_form.html
https://github.com/DHTMLX/scheduler
		</pre>
	</aside>

</main>

<?php include RACINE.'/vue/footerHtml.inc.php'; ?>
