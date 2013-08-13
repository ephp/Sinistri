create or replace view stats_stati_operativi_completa as
select o.id, o.stato, count(*) as n
  from sx_stati_operativi o,
       sx_tabellone s
 where o.stats = 1
   and s.stato_operativo_id = o.id
   and s.gestore_id is not null
 group by o.id;
