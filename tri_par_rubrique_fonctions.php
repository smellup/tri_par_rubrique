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
		&& function_exists('rang_liste_objets') 
		&& in_array('articles', rang_liste_objets())
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

	if($boucle->id_table != 'articles'){
		return (array('zbug_tri_rubrique_sur_articles_uniquement'));
	}
	$tri = 'sql_getfetsel(\'trirub_articles\',\'spip_rubriques\',\'id_rubrique = \'.intval($Pile[0][\'id_rubrique\']))';
	$inverse = '(sql_getfetsel(\'trirub_articles_inverse\',\'spip_rubriques\',\'id_rubrique = \'.intval($Pile[0][\'id_rubrique\'])) ? " DESC" : "")';
	
	$boucle->order[] = $tri.'.'.$inverse;	
}