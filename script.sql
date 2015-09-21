
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";




CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `index` int(11) NOT NULL,
  `id_categorie_pere` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_categorie`),
  UNIQUE KEY `libelle` (`libelle`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=23 ;





CREATE TABLE IF NOT EXISTS `categorie_recette` (
  `id_categorie` int(11) NOT NULL,
  `id_recette` int(11) NOT NULL,
  UNIQUE KEY `id_categorie_2` (`id_categorie`,`id_recette`),
  KEY `id_categorie` (`id_categorie`,`id_recette`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;



CREATE TABLE IF NOT EXISTS `critere` (
  `id_critere` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(32) NOT NULL,
  `index` smallint(6) NOT NULL,
  PRIMARY KEY (`id_critere`),
  UNIQUE KEY `libelle` (`libelle`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


INSERT INTO `critere` (`id_critere`, `libelle`, `index`) VALUES
(1, 'Difficulté', 3),
(2, 'Note', 1),
(3, 'Rapport Qualité/Prix', 2),
(4, 'Durée de conservation', 4);



CREATE TABLE IF NOT EXISTS `critere_recette` (
  `id_critere` int(11) NOT NULL,
  `id_recette` int(11) NOT NULL,
  `valeur` smallint(6) NOT NULL,
  UNIQUE KEY `id_critere` (`id_critere`,`id_recette`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;





CREATE TABLE IF NOT EXISTS `ingredient` (
  `id_recette` int(11) NOT NULL,
  `phase` varchar(16) COLLATE latin1_general_ci DEFAULT NULL,
  `nom` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `qte1` varchar(32) COLLATE latin1_general_ci DEFAULT NULL,
  `qte2` varchar(32) COLLATE latin1_general_ci DEFAULT NULL,
  UNIQUE KEY `id_recette` (`id_recette`,`nom`),
  KEY `phase` (`phase`,`nom`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;





CREATE TABLE IF NOT EXISTS `recette` (
  `id_recette` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(256) COLLATE latin1_general_ci NOT NULL,
  `resume` text COLLATE latin1_general_ci,
  `temps` varchar(16) COLLATE latin1_general_ci DEFAULT NULL,
  `difficulte` int(11) NOT NULL,
  `modeoperatoire` text COLLATE latin1_general_ci,
  `image` longblob,
  `source` varchar(512) COLLATE latin1_general_ci DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_recette`),
  KEY `titre` (`titre`,`temps`,`difficulte`),
  KEY `date_creation` (`date_creation`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=40 ;





CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `crypt_password` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `roles` varchar(128) NOT NULL DEFAULT 'USER',
  `enable` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;