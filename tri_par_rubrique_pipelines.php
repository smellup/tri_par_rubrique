<?php
/**
 * Utilisations de pipelines par Tri des articles par rubrique
 *
 * @plugin     Tri des articles par rubrique
 * @copyright  2019
 * @author     nicod_
 * @licence    GNU/GPL
 * @package    SPIP\Tri_par_rubrique\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}


/**
 * Traiter le formulaire de configuration
 *
 * @param array $flux
 *
 * @return array
 */
function tri_par_rubrique_formulaire_traiter($flux) {
	if ($flux['args']['form'] == 'configurer_tri_par_rubrique') {
		if(_request('appliquer_tri_global') && _request('trirub_articles')) {
			sql_updateq(
				'spip_rubriques',
				array(
					'trirub_articles'         => _request('trirub_articles'),
					'trirub_articles_inverse' => _request('trirub_articles_inverse'),
				)
			);
			$flux['data']['message_ok'] = _T('tri_par_rubrique:tri_global_applique');
		} else {
			$flux['data']['message_ok'] = _T('tri_par_rubrique:aucun_changement');
		}
	}	
	
	return $flux;
}

/**
 * Afficher le mode de tri des articles sur les rubriques
 *
 * @param array $flux
 *
 * @return array
 */
function tri_par_rubrique_affiche_milieu($flux) {

	$exec = trouver_objet_exec($flux['args']['exec']);
	$out = '';
	if (
		$exec['type'] == 'rubrique'
		and $exec['edition'] == false
		and $id_rubrique = intval($flux['args']['id_rubrique'])
	) {
		$out = recuperer_fond(
		'prive/objets/editer/tri_rubrique',
			array(
				'id_rubrique' => $id_rubrique
			)
		);
	}

	if ($out) {
		if ($p = strpos($flux['data'], '<!--affiche_milieu-->')) {
			$flux['data'] = substr_replace($flux['data'], $out, $p, 0);
		} else {
			$flux['data'] .= $out;
		}
	}
	
	return $flux;
}
