# Tri des articles par rubrique

Ce plugin permet de définir le tri des articles par rubrique.

Le tri est pris en compte dans l'espace privé.

Pour utiliser le tri dans l'espace public, utiliser ces critères dans les boucles :

<BOUCLE_articles(ARTICLES){id_rubrique}{par #INFO_TRIRUB_ARTICLES{rubrique,#ID_RUBRIQUE}}{inverse #INFO_TRIRUB_ARTICLES_INVERSE{rubrique,#ID_RUBRIQUE}}>

Si le plugin Rang est installé et activé sur les articles, il est pris en compte.

La configuration du plugin permet d'appliquer un tri global à toutes les rubriques en une fois.
