<?php
if ( !function_exists('vd') ) {
	/**
	 * Fonction qui préformate l’affichage pour débugger avec var_dump, prism.css et prism.js
	 * pour davantage de lisibilité dans une page html avec un label pour intituler ce qu’on affiche
	 * À SUPPRIMER EN PRODUCTION !!!
	 * @param mixed $var : une variable mixte
	 * @param string $label : un titre facultatif
	 * @return void
	 * @author : DO Minh-Dung
	 */
	function vd( $var , $label = '' ) {
		if ( !isset($var) ) return;
		echo '<hr><h4>'.date('H:i:s').' - '.$label.'</h4><pre class="language-php"><code>';
		var_dump( $var );
		echo '</code></pre>';
	}
}

function cleval($value,$key) {
	echo "$key :=> $value\n\t";
}

if ( !function_exists('verif') ) {
	/**
	 * Fonction qui enregistre dans un fichier
	 * @param mixed $var : une variable mixte
	 * @param string $label : un titre facultatif
	 * @return void
	 * @author : DO Minh-Dung
	 */
	function verif( $var , $label = '' ) {
		if ( !isset($var) ) return;
		ob_start();
		// array_walk_recursive( $var, "cleval" );
		var_dump($var);
		$out = ob_get_clean();
		file_put_contents(LOG.'/___.txt', "\n".'---=== '.$label.' ===---'."\n".$out, FILE_APPEND );
	}
}

if ( !function_exists('alerter') ) {
	/**
	 * Affiche un message d’alerte avec classes Bootstrap 3
	 * @param string $msg : une chaîne de caractères
	 * @param string $type : le type d’alerte parmi success, info, warning, danger
	 * @return void
	 * @author : DO Minh-Dung
	 */
	function alerter( $msg , $type = 'info' ) {
		if ( empty($msg) ) return;
		if ( !in_array( $type , [ 'success', 'info', 'warning', 'danger' ] ) ) {
			$type = 'info';
		}
		echo '<div class="alert alert-'.$type.' alert-dismissible" role="alert">', "\n\t",
			'<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>', "\n\t",
			'<strong>', $msg, '</strong>' , "\n",
			'</div>', "\n";
	}
}

if ( !function_exists('afficheTab') ) {
	/**
	 * Fonction qui affiche un tableau html avec classes Bootstrap à partir d'un tableau php à 2 dimensions
	 * @param array $tab : un tableau à 2 dimensions
	 * @param string $label : un titre pour ce tableau
	 * @return string : un code html
	 * @author : DO Minh-Dung
	 */
	function afficheTab( $tab , $label = '' ) {
		$sortie = '';
		if ( is_array($tab) ) {
			$sortie .= '<table class="table table-bordered table-striped table-hover ">';
			if ( $label != '' )
				$sortie .= '<caption class="text-center"><strong>'.$label.'</strong><caption>';
			$sortie .= '<thead><tr class="active">';
			if ( is_array(reset($tab)) )
				$sortie .= '<th>#</th><th>'. implode( '</th><th>' , array_keys($tab[0]) ) .'</th>';
			else
				$sortie .= '<th>#</th><th>'. implode( '</th><th>' , array_keys($tab) ) .'</th>';
			$sortie .= '</tr></thead>';
			$sortie .= '<tbody>' ;
			if ( is_array(reset($tab)) ) {
				foreach( $tab as $i => $ligne ) {
					if ( is_array($ligne) )
						$sortie .= '<tr class="info"><td style="width:50px;">'.$i.'</td><td>'. implode( '</td><td>' , $ligne ) .'</td></tr>';
				}
			}
			else {
				$sortie .= '<tr class="info"><td style="width:50px;">1</td><td>'. implode( '</td><td>' , $tab ) .'</td></tr>';
			}
			$sortie .= '</tbody>' ;
			$sortie .= '</table>' ;
		}
		return $sortie;
	}
}
