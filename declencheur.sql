# contrôle sur nbParticipant, saison et distance
# modification impossible de id, saison et distance
# intégrité sur la suppression
drop  trigger if exists avantAjoutCourse;
drop  trigger if exists avantModificationCourse;
drop  trigger if exists avantSuppressionCourse;


delimiter $$
create trigger avantAjoutCourse before insert on course
for each row
begin
	if new.nbParticipant > 0 then
  		SIGNAL sqlstate '45000' set message_text = 'Le champ nbParticipant ne peut être renseigné à la création de la course';
	end if ;
    if new.saison not in ('PRINTEMPS', 'ETE', 'AUTOMNE', 'HIVER') then
		SIGNAL sqlstate '45000' set message_text = '#Cette saison n''existe pas';
	end if;
    if new.distance not in ('5 Km', '10 Km') then
		SIGNAL sqlstate '45000' set message_text = '#Cette distance n''existe pas';
	end if;
	if exists(select 1 from course where saison = new.saison and distance = new.distance and year(date) = year(new.date)) then
		SIGNAL sqlstate '45000' set message_text = '#Cette course existe déjà';
	end if;
end
$$


create trigger avantModificationCourse before update on course
for each row
begin
	if new.saison != old.saison or new.distance != old.distance or old.id != new.id then
   		SIGNAL sqlstate '45000' set message_text = 'Modification non autorisée';
	end if;
	if exists(select 1 from course where saison = new.saison and distance = new.distance and year(date) = year(new.date) and id != new.id )then
		SIGNAL sqlstate '45000' set message_text = '#Une autre course de même distance existe déjà cette année pour cette saison ';
	end if;
end
    
$$

create trigger avantSuppressionCourse before delete on course 
for each row
	if old.nbParticipant > 0 then
		SIGNAL sqlstate '45000' set message_text = '#La suppression n''est pas possible, cette course contient des participants ';
end if;
$$ 


