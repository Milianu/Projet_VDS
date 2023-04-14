use vds;

drop table if exists statistique;

create table statistique (
    nom varchar(100) not null primary key,
    nb  int not null default 1
);

-- Procédure pour mettre à jour le nb de visite sur chaque page
drop procedure if exists majStatistique;

create procedure majStatistique(_nom varchar(100)) sql security definer
begin
    if exists(Select 1 from statistique where nom = _nom) then
        update statistique
            set nb = nb + 1
        where nom = _nom;
    else
        insert into statistique(nom) values (_nom);
    end if;
end;

select * from statistique;