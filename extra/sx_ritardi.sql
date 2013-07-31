create or replace view sx_ritardi as
select s.id, s.claimant, s.gestore_id, g.sigla, s.priorita_id, sp.priorita, s.stato_id, ss.stato, s.stato_operativo_id, sso.stato as stato_operativo, count(*) as n, max(e.data_ora) as ultima_modifica, datediff(now(), max(e.data_ora)) as giorni
  from cal_eventi e
 inner join cal_eventi_sx cs
       on e.id = cs.id
 inner join sx_tabellone s
       on cs.scheda_id = s.id
 inner join cal_tipi t
       on e.tipo_id = t.id
 inner join sx_priorita sp
       on sp.id = s.priorita_id
 inner join sx_stati ss
       on ss.id = s.stato_id
 inner join sx_stati_operativi sso
       on sso.id = s.stato_operativo_id
 inner join acl_gestori g
       on g.id = s.gestore_id
 
 where t.sigla = 'OTH'

 group by cs.scheda_id
 order by giorni desc