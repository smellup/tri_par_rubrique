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