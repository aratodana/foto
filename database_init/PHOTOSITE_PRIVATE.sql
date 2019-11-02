-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Gép: localhost
-- Létrehozás ideje: 2019. Nov 02. 10:17
-- Kiszolgáló verziója: 10.1.36-MariaDB
-- PHP verzió: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `PHOTOSITE_PRIVATE`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `FELHASZNALO_PRIVATE`
--

CREATE TABLE `FELHASZNALO_PRIVATE` (
  `EMAIL` varchar(253) NOT NULL,
  `NICKNEV` varchar(100) NOT NULL,
  `JELSZO` varchar(128) NOT NULL,
  `ADMIN` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `FELHASZNALO_PRIVATE`
--

INSERT INTO `FELHASZNALO_PRIVATE` (`EMAIL`, `NICKNEV`, `JELSZO`, `ADMIN`) VALUES
('aratodana@gmail.com', 'aratodana', SHA2('cica', 512), 0),
('cica', 'cica', SHA2('cica', 512), 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `REGISZTRACIOS_KOD`
--

CREATE TABLE `REGISZTRACIOS_KOD` (
  `KOD` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `REGISZTRACIOS_KOD`
--

INSERT INTO `REGISZTRACIOS_KOD` (`KOD`) VALUES
('VO2ME7UEMP');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `FELHASZNALO_PRIVATE`
--
ALTER TABLE `FELHASZNALO_PRIVATE`
  ADD PRIMARY KEY (`EMAIL`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
