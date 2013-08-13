create or replace view stats_stati_operativi_gestori as
select o.id, o.stato, g.id as gestore_id, g.sigla, count(*) as n
  from sx_stati_operativi o,
       sx_tabellone s,
       acl_gestori g
 where o.stats = 1
   and s.stato_operativo_id = o.id
   and s.gestore_id = g.id
 group by o.id, g.id;
