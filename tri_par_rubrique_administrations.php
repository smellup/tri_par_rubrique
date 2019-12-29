<?php
/**
 * Fichier gérant l'installation et désinstallation du plugin Tri des articles par rubrique
 *
 * @plugin     Tri des articles par rubrique
 * @copyright  2019
 * @author     nicod_
 * @licence    GNU/GPL
 * @package    SPIP\Tri_par_rubrique\Installation
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Declaration des champs dans la table spip_rubriques
 *
 * @param array $tables_principales
 *
 * @return array
 */
function tri_par_rubrique_declarer_tables_principales($tables_principales){
	$tables_principales['spip_rubriques']['field']['trirub_articles'] = "varchar(20) DEFAULT 'date' NOT NULL";
	$tables_principales['spip_rubriques']['field']['trirub_articles_inverse'] = "tinyint(1) DEFAULT 1 NOT NULL";
	return $tables_principales;
}

/**
 * Declaration des champs éditables et versionnables de la table spip_rubriques
 *
 * @param array $tables
 *
 * @return array
 */
function tri_par_rubrique_declarer_tables_objets_sql($tables) {
	$tables['spip_rubriques']['champs_editables'][] = 'trirub_articles';
	$tables['spip_rubriques']['champs_editables'][] = 'trirub_articles_inverse';
	$tables['spip_rubriques']['champs_versionnes'][] = 'trirub_articles';
	$tables['spip_rubriques']['champs_versionnes'][] = 'trirub_articles_inverse';
	return $tables;
}
/**
 * Fonction d'installation et de mise à jour du plugin Tri des articles par rubrique.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @param string $version_cible
 *     Version du schéma de données dans ce plugin (déclaré dans paquet.xml)
 *
 * @return void
**/
function tri_par_rubrique_upgrade($nom_meta_base_version, $version_cible) {
	$maj = array();

	// Configuration par défaut à la première activation du plugin
	$defaut_config = array(
		'trirub_articles' => 'date',
		'trirub_articles_inverse' => 1
	);

	$maj['create'] = array(
		array('maj_tables', array('spip_rubriques')),
		array('ecrire_config', 'tri_par_rubrique', $defaut_config)
	);

	// On ajoute la configuration par défaut du plugin
	$maj['2.0.0'] = array(
		array('maj200_tri_par_rubrique', $defaut_config)
	);

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}


/**
 * Fonction de désinstallation du plugin Tri des articles par rubrique.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 *
 * @return void
**/
function tri_par_rubrique_vider_tables($nom_meta_base_version) {
	include_spip('inc/meta');
	include_spip('base/abstract_sql');
	
	sql_alter("TABLE spip_rubriques DROP trirub_articles");
	sql_alter("TABLE spip_rubriques DROP trirub_articles_inverse");
	
	effacer_config('tri_par_rubrique');
	effacer_meta($nom_meta_base_version);
}


/**
 * Migration du schéma 1.0.0 au 2.0.0.
 *
 * On ajoute la configuration par défaut du plugin si une configuration n'existe pas déjà.
 *
 * @param array $defaut_config Configuration par défaut du plugin.
 *
 * @return void
 */
function maj200_tri_par_rubrique($defaut_config) {

	// On initialise la configuration ajoutée avec celle par défaut
	include_spip('inc/config');
	$config = lire_config('tri_par_rubrique', array());

	// Mise à jour de la configuration par défaut si il n'y a pas de configuration existante.
	if (!isset($config['trirub_articles'])) {
		$config['trirub_articles'] = $defaut_config['trirub_articles'];
	}
	if (!isset($config['trirub_articles_inverse'])) {
		$config['trirub_articles_inverse'] = $defaut_config['trirub_articles_inverse'];
	}

	ecrire_config('tri_par_rubrique', $config);
}
