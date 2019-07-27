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
 * Afficher le formulaire pour choisir le mode de tri des articles sur les rubriques
 *
 * @param array $flux
 *
 * @return array
 */
function tri_par_rubrique_formulaire_fond($flux) {
	if ($flux['args']['form'] == 'editer_rubrique') {
		$contexte = $flux['args']['contexte'];
		$form = recuperer_fond('prive/objets/editer/rubrique-tri-articles', $contexte);
		if ($p = strpos($flux['data'], '<!--extra-->')) {
			$flux['data'] = substr_replace($flux['data'], $form, $p, 0);
		}
	}
	return $flux;
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
		if(_request('appliquer_tri_global')) {
			sql_updateq(
				'spip_rubriques',
				array(
					'trirub_articles'         => _request('trirub_articles'),
					'trirub_articles_inverse' => _request('trirub_articles_inverse'),
				)
			);
			
		}
		$flux['data']['message_ok'] = _T('tri_par_rubrique:tri_global_applique');
		ecrire_config('tri_par_rubrique/appliquer_tri_global','');
		set_request('appliquer_tri_global','');
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

	$e = trouver_objet_exec($flux['args']['exec']);
	$out = '';
	if ($e['type'] == 'rubrique'
		and $e['edition'] == false
		and $id_rubrique = intval($flux['args']['id_rubrique'])) {

		$infos_tri = sql_fetsel('trirub_articles, trirub_articles_inverse', 'spip_rubriques', 'id_rubrique=' . intval($id_rubrique));

		$tri = _T('tri_par_rubrique:tri_articles_' . $infos_tri['trirub_articles']);

		$out .= recuperer_fond('prive/objets/inclure/tri-articles', $infos_tri);
		//$out .= '<p>Tri des articles : '.$tri.($infos_tri['trirub_articles_inverse'] ? ' - inverse' : '').'</p>';
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