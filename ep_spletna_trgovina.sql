-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 05. jan 2018 ob 14.20
-- Različica strežnika: 10.1.21-MariaDB
-- Različica PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Zbirka podatkov: `ep_spletna_trgovina`
--
CREATE DATABASE IF NOT EXISTS `ep_spletna_trgovina` DEFAULT CHARACTER SET utf8 COLLATE utf8_slovenian_ci;
USE `ep_spletna_trgovina`;

-- --------------------------------------------------------

--
-- Struktura tabele `administrator`
--

CREATE TABLE `administrator` (
  `ID` int(10) NOT NULL,
  `Ime` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Priimek` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Email` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Geslo` varchar(45) COLLATE utf8_slovenian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `izdelek`
--

CREATE TABLE `izdelek` (
  `ID` int(10) NOT NULL,
  `Ime` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Cena` decimal(5,2) DEFAULT NULL,
  `Opis` text COLLATE utf8_slovenian_ci,
  `Proizvajalec` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Operacijski_sistem` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Velikost_zaslona` decimal(10,1) NOT NULL,
  `Ime_slike` varchar(45) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `Zaloga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Odloži podatke za tabelo `izdelek`
--

INSERT INTO `izdelek` (`ID`, `Ime`, `Cena`, `Opis`, `Proizvajalec`, `Operacijski_sistem`, `Velikost_zaslona`, `Ime_slike`, `Zaloga`) VALUES
(2, 'HUAWEI MATE 10 LITE DUAL SIM PRESTIGE GOLD', '319.00', 'Pametni telefon MATE 10 LITE je narejen za hitro delovanje. Vgrajen osemjedrni procesor\r\nHisilicon Kirin 659 omogoča odlično spletno izkušnjo, poganjanje bogatih 3D-iger\r\nter enostavno, bliskovito preklapljanje med aplikacijami. Opremljen je s 4 GB delovnega pomnilnika\r\nter najhitrejšo različico Androida v7.0 ter dvojno kamero spredaj in zadaj.', 'Huawei', 'Android', '5.9', 'huawei_mate_10_gold.jpg', 3),
(3, 'LG H870 G6 32GB ASTRO BLACK', '429.00', 'Pametni mobilni telefon LG G6 je namreč opremljen s 5,7-palčnim (14,47-centimetrskim)\r\nzaslonom IPS ločljivosti 2.880 x 1.440 slikovnih točk, 4-jedrnim procesorjem Snapdragon 821\r\nin sistemskim pomnilnikom kapacitete 4 gigabajtov.', 'LG', 'Android', '5.7', 'lg_g6_astro_black.jpg', 10),
(4, 'SAMSUNG G950F GALAXY S8 64GB ARCTIC SILVER', '569.00', 'Samsung Galaxy S8 je vrhunski mobitel, ki vam v edinstveni kombinaciji kovine in stekla zagotavlja brezčasno eleganco.\r\nPoganja ga najnovejša in najnaprednejša Samsungova jedrna tehnologija.', 'SAMSUNG', 'Android', '5.8', 'samsung_galaxy_s8_arctic_silver.jpg', 1),
(6, 'APPLE IPHONE 7 JET BLACK', '559.00', 'IPhone 7 ima nov procesor A10 Fusion, ki naj bi bil dvakrat učinkovitejši od tistega v predhodniku, iPhonu 6s,\r\nv njem je več kot milijarda tranzistorjev. iPhone 7 naj bi imel zavidljivo vzdržljivost baterije, \r\nuporabljali ga bomo lahko za 12 ur brskanja v omrežju LTE, v pripravljenosti pa bo ostal 240 ur. \r\niPhone 7 bo prav tako zaznal vaš prstni odtis, s pritiskom na gumb ga boste lahko odklenili in opravili nakup,\r\npravtako nudi vodoodpornost in odpornost proti vdoru prašnih delcev.', 'APPLE', 'iOS', '4.7', 'apple_iphone_7_black.jpg', 0),
(7, 'HUAWEI HONOR 8 32GB MIDNIGHT BLACK', '319.00', 'Vitek in inovativen pametni telefon se ponaša z izboljšano inteligenco, ki bo olajšala vaš vsakdan.\r\nNa 5,2-palčnem (13,2 cm) LTPS zaslonu Huawei HONOR 8 bodo vsebine prikazane kot še nikoli poprej.\r\nDvojna kamera z ločljivostjo 12 milijonov točk na hrbtni strani in 8 milijona\r\ntočk na sprednji strani ponuja uporabniku številne inteligentne funkcije ter možnosti.', 'HUAWEI', 'Android', '5.2', 'huawei_honor_8_midnight_black.jpg', 3);

-- --------------------------------------------------------

--
-- Struktura tabele `izdelek_nakupa`
--

CREATE TABLE `izdelek_nakupa` (
  `ID_nakup` int(10) NOT NULL,
  `ID_izdelek` int(10) NOT NULL,
  `Kolicina` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Odloži podatke za tabelo `izdelek_nakupa`
--

INSERT INTO `izdelek_nakupa` (`ID_nakup`, `ID_izdelek`, `Kolicina`) VALUES
(20, 3, 1);

-- --------------------------------------------------------

--
-- Struktura tabele `kosarica`
--

CREATE TABLE `kosarica` (
  `ID` int(11) NOT NULL,
  `ID_kupec` int(11) NOT NULL,
  `ID_izdelek` int(11) NOT NULL,
  `Kolicina` int(10) NOT NULL DEFAULT '1',
  `datum` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `kupec`
--

CREATE TABLE `kupec` (
  `ID` int(10) NOT NULL,
  `Ime` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Priimek` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Email` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Naslov` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Telefonska_stevilka` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Password` varchar(45) COLLATE utf8_slovenian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Odloži podatke za tabelo `kupec`
--

INSERT INTO `kupec` (`ID`, `Ime`, `Priimek`, `Email`, `Naslov`, `Telefonska_stevilka`, `Password`) VALUES
(5, 'UroÅ¡', 'VaupotiÄ', 'ugi.vaupotic@gmail.com', 'Videm pri Ptuju 8a', '041865272', 'uros123'),
(6, 'Lara', 'Carli', 'lara.carli@gmail.com', 'RogozniÅ¡ka cesta 38, Ptuj', '111222333', 'lara'),
(7, 'BlaÅ¾', 'Milar', 'blaz.milar@gmail.com', 'Drnovo 14', '034543043', 'bm');

-- --------------------------------------------------------

--
-- Struktura tabele `nakup`
--

CREATE TABLE `nakup` (
  `ID` int(10) NOT NULL,
  `ID_kupec` int(10) NOT NULL,
  `Datum` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Cena` decimal(10,2) NOT NULL,
  `Status` varchar(45) COLLATE utf8_slovenian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Odloži podatke za tabelo `nakup`
--

INSERT INTO `nakup` (`ID`, `ID_kupec`, `Datum`, `Cena`, `Status`) VALUES
(20, 5, '2018-01-05 13:44:31', '434.49', 'V obdelavi');

-- --------------------------------------------------------

--
-- Struktura tabele `naslov_za_posiljanje`
--

CREATE TABLE `naslov_za_posiljanje` (
  `ID` int(11) NOT NULL,
  `ID_kupec` int(11) NOT NULL,
  `Ime` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Priimek` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Ulica_stevilka` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Kraj` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Postna_stevilka` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `prodajalec`
--

CREATE TABLE `prodajalec` (
  `ID` int(11) NOT NULL,
  `Ime` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Priimek` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Email` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
  `Geslo` varchar(45) COLLATE utf8_slovenian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Indeksi zavrženih tabel
--

--
-- Indeksi tabele `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksi tabele `izdelek`
--
ALTER TABLE `izdelek`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksi tabele `kosarica`
--
ALTER TABLE `kosarica`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksi tabele `kupec`
--
ALTER TABLE `kupec`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksi tabele `nakup`
--
ALTER TABLE `nakup`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksi tabele `naslov_za_posiljanje`
--
ALTER TABLE `naslov_za_posiljanje`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksi tabele `prodajalec`
--
ALTER TABLE `prodajalec`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT zavrženih tabel
--

--
-- AUTO_INCREMENT tabele `administrator`
--
ALTER TABLE `administrator`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT tabele `izdelek`
--
ALTER TABLE `izdelek`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT tabele `kosarica`
--
ALTER TABLE `kosarica`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT tabele `kupec`
--
ALTER TABLE `kupec`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT tabele `nakup`
--
ALTER TABLE `nakup`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT tabele `prodajalec`
--
ALTER TABLE `prodajalec`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
