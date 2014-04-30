-- phpMyAdmin SQL Dump
-- version 3.5.0
-- http://www.phpmyadmin.net
--
-- Počítač: wm17.wedos.net:3306
-- Vygenerováno: Čtv 23. srp 2012, 23:44
-- Verze MySQL: 5.5.23
-- Verze PHP: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `d19546_skoldat`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_grou`
--

CREATE TABLE IF NOT EXISTS `sk_grou` (
  `id_grou` int(10) NOT NULL,
  `name_grou` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `abbrev_grou` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `ident_grou` varchar(5) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id_grou`),
  UNIQUE KEY `name_grou` (`name_grou`,`ident_grou`),
  KEY `abbrev_grou` (`abbrev_grou`),
  KEY `id_grou` (`id_grou`),
  KEY `id_grou_2` (`id_grou`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `sk_grou`
--

INSERT INTO `sk_grou` (`id_grou`, `name_grou`, `abbrev_grou`, `ident_grou`) VALUES
(2, 'Administrátoři', 'ADMIN', '00001'),
(3, 'Everybody', 'EVER', '00002'),
(4, 'Pronájmy', 'RENT', '00003');

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_mail`
--

CREATE TABLE IF NOT EXISTS `sk_mail` (
  `recipient_mail` varchar(250) COLLATE utf8_czech_ci NOT NULL,
  `subject_mail` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `body_mail` text COLLATE utf8_czech_ci NOT NULL,
  `sending_count_mail` int(10) NOT NULL DEFAULT '0',
  `sending_error_mail` text COLLATE utf8_czech_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_meit`
--

CREATE TABLE IF NOT EXISTS `sk_meit` (
  `id_meit` int(10) NOT NULL,
  `id_meli_meit` int(10) NOT NULL,
  `name_meit` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `link_meit` varchar(250) COLLATE utf8_czech_ci NOT NULL,
  `target_meit` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '_self',
  `rank_meit` int(10) NOT NULL DEFAULT '100',
  `perm_level_user_meit` int(2) NOT NULL DEFAULT '1',
  KEY `SK_MEIT_FK1` (`id_meli_meit`),
  KEY `SK_MEIT_FK2` (`id_meit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `sk_meit`
--

INSERT INTO `sk_meit` (`id_meit`, `id_meli_meit`, `name_meit`, `link_meit`, `target_meit`, `rank_meit`, `perm_level_user_meit`) VALUES
(14, 5, 'Osobní informace', '/User/myInfo', '_self', 100, 1),
(6, 5, 'Odhlásit', '/User/logout', '_self', 1, 1),
(8, 7, 'Přidat místnost', '/Rents/addRoom', '_self', 1, 2),
(11, 7, 'Přehled místností', '/Rents/showRooms', '_self', 11, 1),
(9, 7, 'Přidat smluvní stranu', '/Rents/addRent', '_self', 2, 2),
(12, 7, 'Přehled smluvních stran', '/Rents/showRent', '_self', 12, 1),
(10, 7, 'Přidat pronájem', '/Rents/addRecu', '_self', 3, 2),
(13, 7, 'Přehled pronájmů', '/Rents/showRecu', '_self', 13, 1),
(16, 15, 'Přehled uživatelů', '/Admin/showUser', '_self', 70, 1),
(17, 15, 'Nový uživatel', '/Admin/addUser', '_self', 60, 2),
(384, 15, 'Licence', '/Admin/showLicense', '_self', 1, 2),
(335, 5, 'Změnit heslo', '/User/changePassword', '_self', 50, 1),
(334, 15, 'Základní nastavení', '/Admin/mainSettings', '_self', 20, 2),
(389, 15, 'Přidat novou skupinu', '/Admin/newGrou', '_self', 90, 2),
(394, 15, 'Přehled skupin', '/Admin/showGrou', '_self', 100, 2);

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_meli`
--

CREATE TABLE IF NOT EXISTS `sk_meli` (
  `id_meli` int(10) NOT NULL,
  `name_meli` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `id_grou_meli` int(10) NOT NULL,
  `rank_meli` int(10) NOT NULL DEFAULT '100',
  `color_meli` int(2) NOT NULL DEFAULT '1',
  UNIQUE KEY `id_meli` (`id_meli`),
  KEY `SK_MELI_FK2` (`id_grou_meli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `sk_meli`
--

INSERT INTO `sk_meli` (`id_meli`, `name_meli`, `id_grou_meli`, `rank_meli`, `color_meli`) VALUES
(5, 'Moje nastavení', 3, 2, 1),
(7, 'Pronájmy', 4, 100, 2),
(15, 'Administrace', 2, 54, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_recu`
--

CREATE TABLE IF NOT EXISTS `sk_recu` (
  `id_recu` int(10) NOT NULL,
  `id_rent_recu` int(10) NOT NULL,
  `id_room_recu` int(10) NOT NULL,
  `id_user_recu` int(10) NOT NULL,
  `time_from_recu` time NOT NULL,
  `time_to_recu` time NOT NULL,
  `day_recu` date NOT NULL,
  `status_recu` int(2) NOT NULL DEFAULT '2',
  KEY `SK_RECU_FK1` (`id_recu`),
  KEY `SK_RECU_FK2` (`id_user_recu`),
  KEY `SK_RECU_FK3` (`id_rent_recu`),
  KEY `SK_RECU_FK4` (`id_room_recu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_refi`
--

CREATE TABLE IF NOT EXISTS `sk_refi` (
  `id_refi` int(10) NOT NULL,
  `id_rent_refi` int(10) NOT NULL,
  `id_user_refi` int(10) NOT NULL,
  `dt_add_refi` datetime NOT NULL,
  `hash_refi` varchar(250) COLLATE utf8_czech_ci NOT NULL,
  `filepath_refi` text COLLATE utf8_czech_ci NOT NULL,
  `mimetype_refi` varchar(250) COLLATE utf8_czech_ci NOT NULL,
  `filesize_refi` int(100) NOT NULL,
  KEY `SK_REFI_FK1` (`id_refi`),
  KEY `SK_REFI_FK2` (`id_rent_refi`),
  KEY `SK_REFI_FK3` (`id_user_refi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_rent`
--

CREATE TABLE IF NOT EXISTS `sk_rent` (
  `id_rent` int(10) NOT NULL,
  `id_user_rent` int(10) NOT NULL,
  `first_name_rent` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `surname_rent` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `phone_rent` varchar(12) COLLATE utf8_czech_ci DEFAULT NULL,
  `address_rent` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  `town_rent` varchar(200) COLLATE utf8_czech_ci DEFAULT NULL,
  `email_rent` varchar(200) COLLATE utf8_czech_ci DEFAULT NULL,
  `sel` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  KEY `SK_RENT_FK1` (`id_rent`),
  KEY `SK_RENT_FK2` (`id_user_rent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_room`
--

CREATE TABLE IF NOT EXISTS `sk_room` (
  `id_room` int(10) NOT NULL,
  `name_room` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `location_room` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `description_room` text COLLATE utf8_czech_ci,
  `sel` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  KEY `SK_ROOM_FK1` (`id_room`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_subj`
--

CREATE TABLE IF NOT EXISTS `sk_subj` (
  `id_subj` int(10) NOT NULL AUTO_INCREMENT,
  `type_subj` int(2) NOT NULL,
  PRIMARY KEY (`id_subj`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=433 ;

--
-- Vypisuji data pro tabulku `sk_subj`
--

INSERT INTO `sk_subj` (`id_subj`, `type_subj`) VALUES
(1, 1),
(2, 2),
(3, 2),
(4, 2),
(5, 3),
(6, 4),
(7, 3),
(8, 4),
(9, 4),
(10, 4),
(11, 4),
(12, 4),
(13, 4),
(14, 4),
(15, 3),
(16, 4),
(17, 4),
(334, 4),
(335, 4),
(384, 4),
(389, 4),
(394, 3),
(431, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_syst`
--

CREATE TABLE IF NOT EXISTS `sk_syst` (
  `type_send_mail_syst` int(2) NOT NULL DEFAULT '1',
  `mail_format_syst` varchar(6) COLLATE utf8_czech_ci NOT NULL DEFAULT 'html',
  `smtp_server_syst` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `smtp_auth_syst` int(1) NOT NULL DEFAULT '0',
  `smtp_auth_email_syst` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `smtp_auth_pwd_syst` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `mail_sender_name_syst` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `mail_wordwrap_syst` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `row_show_syst` int(20) NOT NULL DEFAULT '20',
  `site_name_syst` varchar(200) COLLATE utf8_czech_ci NOT NULL DEFAULT 'Školdat',
  `smtp_port_syst` int(20) NOT NULL DEFAULT '25',
  `smtp_secure_syst` varchar(200) COLLATE utf8_czech_ci DEFAULT NULL,
  `school_name_syst` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `school_address_syst` text COLLATE utf8_czech_ci,
  `school_contact_user_syst` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `cron_hourly_syst` datetime DEFAULT NULL,
  `cron_daily_syst` datetime DEFAULT NULL,
  `cron_weekly_syst` datetime DEFAULT NULL,
  `cron_monthly_syst` datetime DEFAULT NULL,
  `cron_yearly_syst` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `sk_syst`
--

INSERT INTO `sk_syst` (`type_send_mail_syst`, `mail_format_syst`, `smtp_server_syst`, `smtp_auth_syst`, `smtp_auth_email_syst`, `smtp_auth_pwd_syst`, `mail_sender_name_syst`, `mail_wordwrap_syst`, `row_show_syst`, `site_name_syst`, `smtp_port_syst`, `smtp_secure_syst`, `school_name_syst`, `school_address_syst`, `school_contact_user_syst`, `cron_hourly_syst`, `cron_daily_syst`, `cron_weekly_syst`, `cron_monthly_syst`, `cron_yearly_syst`) VALUES
(2, 'html', 'smtp-mail1.wedos.net', 1, 'notification@skoldat.cz', 'Heslo1234', 'Školdat', '150', 30, 'Školdat', 25, '', 'ZŠ Květnového vítězství 1554', 'ZŠ Květnového vítězství 1554/15 - Praha 4   149 00', 'Mgr. Pavel Kopečný', '2012-08-23 23:00:00', '2012-08-24 00:00:00', '2012-08-27 00:00:00', '2012-09-01 00:00:00', '2013-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_user`
--

CREATE TABLE IF NOT EXISTS `sk_user` (
  `id_user` int(10) NOT NULL,
  `login_user` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `password_user` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  `first_name_user` varchar(200) COLLATE utf8_czech_ci DEFAULT NULL,
  `surname_user` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `email_user` varchar(250) COLLATE utf8_czech_ci NOT NULL,
  `role_user` varchar(200) COLLATE utf8_czech_ci DEFAULT NULL,
  `phone_user` varchar(20) COLLATE utf8_czech_ci DEFAULT NULL,
  `permission_level_user` int(2) NOT NULL DEFAULT '3',
  UNIQUE KEY `id_user` (`id_user`,`login_user`,`email_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `sk_user`
--

INSERT INTO `sk_user` (`id_user`, `login_user`, `password_user`, `first_name_user`, `surname_user`, `email_user`, `role_user`, `phone_user`, `permission_level_user`) VALUES
(1, 'tirus', 'af37f1de61cf0fc4f009a808098a9a12e534f96c', 'Tomáš', 'Kulhánek', 'tomas.kulhanek@tirus.eu', '', '732947445', 3),
(431, 'jiri.kulhanek', '8fa76f8a31019006c2e71f9813da8f232cc5e0c0', 'Jiří', 'Kulhánek', 'info@helppc.cz', 'Technický pracovník', '603393538', 3);

-- --------------------------------------------------------

--
-- Struktura tabulky `sk_usgr`
--

CREATE TABLE IF NOT EXISTS `sk_usgr` (
  `id_user_usgr` int(10) NOT NULL,
  `id_grou_usgr` int(10) NOT NULL,
  `permission_usgr` int(1) NOT NULL DEFAULT '0',
  KEY `SK_USGR_FK1` (`id_grou_usgr`),
  KEY `SK_USGR_FK2` (`id_user_usgr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `sk_usgr`
--

INSERT INTO `sk_usgr` (`id_user_usgr`, `id_grou_usgr`, `permission_usgr`) VALUES
(1, 2, 2),
(1, 3, 2),
(1, 4, 2),
(431, 3, 1),
(431, 4, 2),
(431, 2, 2);

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `sk_grou`
--
ALTER TABLE `sk_grou`
  ADD CONSTRAINT `SK_GROU_FK1` FOREIGN KEY (`id_grou`) REFERENCES `sk_subj` (`id_subj`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `sk_meit`
--
ALTER TABLE `sk_meit`
  ADD CONSTRAINT `SK_MEIT_FK1` FOREIGN KEY (`id_meli_meit`) REFERENCES `sk_meli` (`id_meli`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `SK_MEIT_FK2` FOREIGN KEY (`id_meit`) REFERENCES `sk_subj` (`id_subj`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `sk_meli`
--
ALTER TABLE `sk_meli`
  ADD CONSTRAINT `SK_MELI_FK1` FOREIGN KEY (`id_meli`) REFERENCES `sk_subj` (`id_subj`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `SK_MELI_FK2` FOREIGN KEY (`id_grou_meli`) REFERENCES `sk_grou` (`id_grou`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `sk_recu`
--
ALTER TABLE `sk_recu`
  ADD CONSTRAINT `SK_RECU_FK1` FOREIGN KEY (`id_recu`) REFERENCES `sk_subj` (`id_subj`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `SK_RECU_FK2` FOREIGN KEY (`id_user_recu`) REFERENCES `sk_user` (`id_user`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `SK_RECU_FK3` FOREIGN KEY (`id_rent_recu`) REFERENCES `sk_rent` (`id_rent`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `SK_RECU_FK4` FOREIGN KEY (`id_room_recu`) REFERENCES `sk_room` (`id_room`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `sk_refi`
--
ALTER TABLE `sk_refi`
  ADD CONSTRAINT `SK_REFI_FK1` FOREIGN KEY (`id_refi`) REFERENCES `sk_subj` (`id_subj`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `SK_REFI_FK2` FOREIGN KEY (`id_rent_refi`) REFERENCES `sk_rent` (`id_rent`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `SK_REFI_FK3` FOREIGN KEY (`id_user_refi`) REFERENCES `sk_user` (`id_user`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `sk_rent`
--
ALTER TABLE `sk_rent`
  ADD CONSTRAINT `SK_RENT_FK1` FOREIGN KEY (`id_rent`) REFERENCES `sk_subj` (`id_subj`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `SK_RENT_FK2` FOREIGN KEY (`id_user_rent`) REFERENCES `sk_user` (`id_user`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `sk_room`
--
ALTER TABLE `sk_room`
  ADD CONSTRAINT `SK_ROOM_FK1` FOREIGN KEY (`id_room`) REFERENCES `sk_subj` (`id_subj`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `sk_user`
--
ALTER TABLE `sk_user`
  ADD CONSTRAINT `SK_USER_FK1` FOREIGN KEY (`id_user`) REFERENCES `sk_subj` (`id_subj`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `sk_usgr`
--
ALTER TABLE `sk_usgr`
  ADD CONSTRAINT `SK_USGR_FK1` FOREIGN KEY (`id_grou_usgr`) REFERENCES `sk_grou` (`id_grou`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `SK_USGR_FK2` FOREIGN KEY (`id_user_usgr`) REFERENCES `sk_user` (`id_user`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
