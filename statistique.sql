use vds;

Create table if not exists statistique
(
    nom varchar(100) not null primary key,
    nb  int(11)      not null default 1
) engine = innodb;


-- Procédure stockée afin de mettre à jour la table statisitique
DROP PROCEDURE if exists majStatistique;

create procedure majStatistique(_nom varchar(75)) SQL SECURITY DEFINER
    if exists (select 1 from statistique where nom = _nom) then
        update statistique
        set nb = nb + 1
        where nom =  _nom;
    else
        insert into statistique(nom, nb) values (_nom, 1);
    end if;


Select * from statistique;