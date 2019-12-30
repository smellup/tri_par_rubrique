<?php
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Chargement des donnees du formulaire
 *
 * @param int $id_rubrique
 *
 * @return array
 */
function formulaires_editer_tri_rubrique_charger($id_rubrique, $redirect) {

	$valeurs = array();
	
	// On passe au formulaire l'id de la rubrique.
	$valeurs['id_rubrique'] = $id_rubrique;

	// On détermine si un article est déjà sélectionné ou pas.
	if ($infos_tri = sql_fetsel(array('trirub_articles', 'trirub_articles_inverse'), 'spip_rubriques', 'id_rubrique='.intval($id_rubrique))) {
		$valeurs = array_merge($valeurs, $infos_tri);
		$valeurs['editable'] = true;
	} else {
		$valeurs['editable'] = false;
	}

	return $valeurs;
}

/**
 * Traitement
 *
 * @param int $id_rubrique
 *
  * @return array
 */
function formulaires_editer_tri_rubrique_traiter($id_rubrique, $redirect) {

	$retour = array(
		'message_ok' => '',
		'editable'   => true
	);
	
	if (!_request('annuler')) {
		$update = array();
		$tri = _request('trirub_articles');
		$sens = _request('trirub_articles_inverse');
		if (
			!is_null($tri)
			and !is_null($sens)
		) {
			$update['trirub_articles'] = $tri;
			$update['trirub_articles_inverse'] = $sens;
			sql_updateq('spip_rubriques', $update, 'id_rubrique='.intval($id_rubrique));
		}

		if ($redirect) {
			$retour['redirect'] = $redirect;
		}
	}

	return $retour;
}
