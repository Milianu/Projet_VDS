use vds;

drop table if exists agenda;

CREATE TABLE agenda (
  id int AUTO_INCREMENT primary key,
  nom varchar(150)  NOT NULL,
  description text ,
  date date NOT NULL
) ENGINE=InnoDB;

INSERT INTO agenda (id, nom, description, date) VALUES
(1, 'Semi-marathon de la coulée verte', '<p>Organis&eacute; par nos amis du club d&rsquo;Esprit Run, le club offre l&#39;inscription &agrave; tous ses adh&eacute;rentes et adherents.&nbsp;<br />\r\n&nbsp;</p>', '2023-03-19'),
(2, 'Première édition des 4 saisons', '<p>Conctacter Christian Wyckaert si vous pouvez donner quelques heures de votre temps pour apporter votre aide &agrave; l&#39;organisation</p>', '2023-03-05'),
(3, 'Exposé sur l\'entrainement au semi marathon', '<p>Jean Fran&ccedil;ois vous propose de se retrouver &agrave; l hôtel Holiday Inn (pres de la gare du Nord d Amiens) &agrave; 10h pour un expos&eacute; concernant la pr&eacute;paration d&#39;un semi marathon (avec application pratique pour le semi de Salouel du 19 mars).</p>\r\n\r\n<p>R&eacute;serv&eacute; aux coureurs du club</p>', '2023-02-11');



