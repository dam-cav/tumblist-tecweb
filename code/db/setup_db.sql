SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE `categoria` (
  `Nome` varchar(25) NOT NULL,
  `TagImg` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categoria` (`Nome`, `TagImg`) VALUES
('Animali', 'animal'),
('Arte e Cultura', 'art'),
('Gravidanza e Genitori', 'baby'),
('Bellezza e Stile', 'beauty'),
('Libri', 'book'),
('Auto e Trasporti', 'car'),
('Affari e Finanza', 'earning'),
('Alimentazione e cibi', 'food'),
('Videogiochi', 'game'),
('Salute', 'health'),
('Relazioni e Famiglia', 'heart'),
('Casa e Giardino', 'house'),
('Giochi e Passatempi', 'ilcava'),
('Film', 'movie'),
('Notizie ed Eventi', 'news'),
('Politica e Governo', 'politics'),
('Scuola ed Educazione', 'school'),
('Matematica e Scienze', 'science'),
('Ambiente', 'seeding'),
('Social', 'share'),
('Negozi e Aziende', 'shopping'),
('Sport', 'sport'),
('Tecnologia', 'tech'),
('Viaggi', 'travel'),
('Esoterismo', 'yoga');

CREATE TABLE `elemento` (
  `Id` bigint(20) NOT NULL,
  `Nome` varchar(140) NOT NULL,
  `Etichetta` varchar(250) DEFAULT NULL,
  `LinkImg` varchar(140) DEFAULT NULL,
  `AltImg` varchar(100) DEFAULT NULL,
  `Ordine` int(11) DEFAULT NULL,
  `IdL` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `interazione` (
  `Id` bigint(20) NOT NULL,
  `Tipo` varchar(1) DEFAULT 'C',
  `Data` datetime NOT NULL,
  `Testo` varchar(250) DEFAULT NULL,
  `NickIns` varchar(20) DEFAULT NULL,
  `IdL` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `lista` (
  `Id` bigint(20) NOT NULL,
  `Autore` varchar(20) DEFAULT NULL,
  `Titolo` varchar(140) NOT NULL,
  `Sottotitolo` varchar(300) DEFAULT NULL,
  `Descrizione` varchar(400) DEFAULT NULL,
  `Pubblico` tinyint(1) DEFAULT '0',
  `LinkFig` varchar(140) DEFAULT NULL,
  `AltFig` varchar(40) DEFAULT NULL,
  `Categoria` varchar(20) DEFAULT NULL,
  `DataCreazione` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `utente` (
  `Mail` varchar(100) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Biografia` varchar(140) DEFAULT NULL,
  `LinkImg` varchar(25) DEFAULT NULL,
  `Password` char(128) NOT NULL,
  `Conferma` char(6) DEFAULT NULL,
  `DataIscrizione` datetime DEFAULT CURRENT_TIMESTAMP,
  `Ruolo` char(1) DEFAULT 'U'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `utente` (`Mail`, `Username`, `Biografia`, `LinkImg`, `Password`, `Conferma`, `DataIscrizione`, `Ruolo`) VALUES
('admin@admin.admin', 'admin', 'Admin', NULL, 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', NULL, '2018-02-11 21:32:19', 'A'),
('use@user.user', 'user', 'Nuovo utente1', NULL, 'b14361404c078ffd549c03db443c3fede2f3e534d73f78f77301ed97d4a436a9fd9db05ee8b325c0ad36438b43fec8510c204fc1c1edb21d0941c00e9e2c1ce2', NULL, '2018-02-12 07:22:47', 'U');

ALTER TABLE `categoria`
  ADD PRIMARY KEY (`Nome`),
  ADD UNIQUE KEY `TagImg` (`TagImg`);

ALTER TABLE `elemento`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `IdL` (`IdL`,`Ordine`);

ALTER TABLE `interazione`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `NickIns` (`NickIns`),
  ADD KEY `IdL` (`IdL`);

ALTER TABLE `lista`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Autore` (`Autore`),
  ADD KEY `Categoria` (`Categoria`);

ALTER TABLE `utente`
  ADD PRIMARY KEY (`Username`),
  ADD UNIQUE KEY `Mail` (`Mail`),
  ADD UNIQUE KEY `Conferma` (`Conferma`);

ALTER TABLE `elemento`
  MODIFY `Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

ALTER TABLE `interazione`
  MODIFY `Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `lista`
  MODIFY `Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `elemento`
  ADD CONSTRAINT `elemento_ibfk_1` FOREIGN KEY (`IdL`) REFERENCES `lista` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `interazione`
  ADD CONSTRAINT `interazione_ibfk_1` FOREIGN KEY (`NickIns`) REFERENCES `utente` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `interazione_ibfk_2` FOREIGN KEY (`IdL`) REFERENCES `lista` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `lista`
  ADD CONSTRAINT `lista_ibfk_1` FOREIGN KEY (`Autore`) REFERENCES `utente` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lista_ibfk_2` FOREIGN KEY (`Categoria`) REFERENCES `categoria` (`Nome`) ON DELETE CASCADE ON UPDATE CASCADE;
