use
vds;

-- création d'une table

DROP TABLE IF EXISTS visite;
CREATE TABLE visite
(
    date date NOT NULL PRIMARY KEY,
    nb   int  NOT NULL
) ENGINE=InnoDB;


-- réalisation d'une procédure stockée pour comptabilite la visite

DROP PROCEDURE if exists comptabiliserVisite;

create procedure comptabiliserVisite() SQL SECURITY DEFINER
    if exists (select 1 from Visite where date = curdate()) then
        update visite
            set nb = nb + 1
        where date =  curdate();
    else
        insert into visite(date, nb) values (curdate(), 1);
    end if;



select * from visite;