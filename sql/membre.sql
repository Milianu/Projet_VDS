use vds;

-- Ajouter un champ nbconnexion
alter table membre
    add nbConnexion int not null default 0;

-- Procédure de mise à jour de la colonne nbConnexion
drop procedure if exists enregistrerConnexion;

create procedure enregistrerConnexion(_id int)
begin
    update membre
        set nbConnexion = nbConnexion + 1
    where id = _id;
end;

call enregistrerConnexion(1);

select * from membre;