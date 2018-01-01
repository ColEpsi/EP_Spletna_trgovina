-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 01. jan 2018 ob 18.29
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
  `Ime_slike` varchar(45) COLLATE utf8_slovenian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Odloži podatke za tabelo `izdelek`
--

INSERT INTO `izdelek` (`ID`, `Ime`, `Cena`, `Opis`, `Proizvajalec`, `Operacijski_sistem`, `Velikost_zaslona`, `Ime_slike`) VALUES
(2, 'HUAWEI MATE 10 LITE DUAL SIM PRESTIGE GOLD', '319.00', 'Pametni telefon MATE 10 LITE je narejen za hitro delovanje. Vgrajen osemjedrni procesor\r\nHisilicon Kirin 659 omogoča odlično spletno izkušnjo, poganjanje bogatih 3D-iger\r\nter enostavno, bliskovito preklapljanje med aplikacijami. Opremljen je s 4 GB delovnega pomnilnika\r\nter najhitrejšo različico Androida v7.0 ter dvojno kamero spredaj in zadaj.', 'Huawei', 'Android', '5.9', 'huawei_mate_10_gold.jpg'),
(3, 'LG H870 G6 32GB ASTRO BLACK', '429.00', 'Pametni mobilni telefon LG G6 je namreč opremljen s 5,7-palčnim (14,47-centimetrskim)\r\nzaslonom IPS ločljivosti 2.880 x 1.440 slikovnih točk, 4-jedrnim procesorjem Snapdragon 821\r\nin sistemskim pomnilnikom kapacitete 4 gigabajtov.', 'LG', 'Android', '5.7', 'lg_g6_astro_black.jpg');

-- --------------------------------------------------------

--
-- Struktura tabele `košarica`
--

CREATE TABLE `košarica` (
  `ID` int(11) NOT NULL,
  `ID_kupec` int(11) NOT NULL,
  `ID_izdelek` int(11) NOT NULL,
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
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
  `Password` varchar(45) COLLATE utf8_slovenian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

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

--
-- Indeksi zavrženih tabel
--

--
-- Indeksi tabele `izdelek`
--
ALTER TABLE `izdelek`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksi tabele `košarica`
--
ALTER TABLE `košarica`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksi tabele `kupec`
--
ALTER TABLE `kupec`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksi tabele `naslov_za_posiljanje`
--
ALTER TABLE `naslov_za_posiljanje`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT zavrženih tabel
--

--
-- AUTO_INCREMENT tabele `izdelek`
--
ALTER TABLE `izdelek`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT tabele `kupec`
--
ALTER TABLE `kupec`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
