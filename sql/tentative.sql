use vds;

drop table if exists tentative;

create table tentative
(
    id       int auto_increment primary key,
    date     datetime    not null default now(),
    ip       varchar(15) not null,
    login    varchar(30) not null,
    password varchar(50) not null
);


Select * from  tentative;

-- delete from tentative;


/*
select count(*) from tentative
where (login = 'test' or ip = '::1' )
  and date > now() - interval 10 minute;
*/