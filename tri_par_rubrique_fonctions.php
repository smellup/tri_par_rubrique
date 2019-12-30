<?php
/**
 * Fonctions utiles au plugin Tri des articles par rubrique
 *
 * @plugin     Tri des articles par rubrique
 * @copyright  2019
 * @author     nicod_
 * @licence    GNU/GPL
 * @package    SPIP\Tri_par_rubrique\Fonctions
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Génère la liste des critères de tri d'article utilisables sur les rubriques
 * @return array
 */
function filtre_tri_par_rubrique_criteres_dist() {
	$criteres = array(
		'date'       => _T('tri_par_rubrique:tri_articles_date'),
		'maj'        => _T('tri_par_rubrique:tri_articles_maj'),
		'titre'      => _T('tri_par_rubrique:tri_articles_titre'),
		'num titre'  => _T('tri_par_rubrique:tri_articles_num_titre'),
		'id_article' => _T('tri_par_rubrique:tri_articles_id_article'),
	);
	if(
		test_plugin_actif('rang') 
		and function_exists('rang_liste_objets')
		and in_array('articles', rang_liste_objets())
	){
		$criteres['rang'] = _T('tri_par_rubrique:tri_articles_rang');
	}
	
	return $criteres;
}

/**
 * Critère tri_rubrique, qui affiche les articles selon le tri défini sur la rubrique dans l'espace privé
 * @param $idb
 * @param $boucles
 * @param $crit
 */
function critere_tri_rubrique($idb, &$boucles, $crit) {

	$boucle = &$boucles[ $idb ];

	// Uniquement les boucles articles sont concernées par le critère de tri.
	if ($boucle->id_table != 'articles') {
		return array('zbug_tri_rubrique_sur_articles_uniquement');
	}

	// Existence d'un collate ou pas
	$collecte = '';
	if (isset($boucle->modificateur['collate'])) {
		$collecte = $boucle->modificateur['collate'];
	}

	// Détermination du tri configuré pour la rubrique ou globalement par défaut si aucun id_rubrique dans l'environnement
	$boucle->order[] = 'calculer_tri_rubrique(intval($Pile[0][\'id_rubrique\']), $collecte)';
}

function calculer_tri_rubrique($id_rubrique, $collecte) {

	$expression_tri = '';

	if ($id = intval($id_rubrique)) {
		// On a bien un id_rubrique dans l'environnement
		$champs_tri = array('trirub_articles', 'trirub_articles_inverse');
		$config_tri = sql_fetsel($champs_tri, 'spip_rubriques', 'id_rubrique=' . $id);
	} else {
		// Pas d'id_rubrique, on se rabat sur la config globale
		include_spip('inc/config');
		$config_tri = lire_config('tri_par_rubrique', array());
	}

	if ($config_tri) {
		// On a bien la config du tri, on peut construire l'expression de tri.
		// Sinon, on renvoie une expression vide ce qui laisse le tri par défaut de SQL.
		$tri = $config_tri['trirub_articles'];
		$sens = $config_tri['trirub_articles_inverse'] ? 'DESC' : 'ASC';

		$expression_tri = "${tri} ${collecte} ${sens}";
	}

	return $expression_tri;
}

function tri_rubrique_champ($id_rubrique) {

	// On initialise le champ à vide.
	$champ = '';

	if ($id = intval($id_rubrique)) {
		// On a bien un id_rubrique dans l'environnement
		$champ = sql_fetsel('trirub_articles', 'spip_rubriques', 'id_rubrique=' . $id);
	}

	if (!$champ) {
		// Pas d'id_rubrique ou erreur SQL sur le fetsel, on se rabat sur la config globale
		include_spip('inc/config');
		$champ = lire_config('tri_par_rubrique/trirub_articles');
	}

	return $champ;
}

function tri_rubrique_sens($id_rubrique) {

	// On initialise la variable à false pour la distinguer de 0.
	$inverse = false;

	if ($id = intval($id_rubrique)) {
		// On a bien un id_rubrique dans l'environnement
		$inverse = sql_fetsel('trirub_articles', 'spip_rubriques', 'id_rubrique=' . $id);
	}

	if ($inverse === false) {
		// Pas d'id_rubrique ou erreur SQL sur le fetsel, on se rabat sur la config globale
		include_spip('inc/config');
		$inverse = lire_config('tri_par_rubrique/trirub_articles_inverse');
	}

	return $inverse ? -1 : 1;
}
