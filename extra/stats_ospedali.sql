create or replace view stats_ospedali as
select o.sigla, (
       select count(*) 
		   from sx_tabellone s 
		  where s.gestore_id is not null 
			 and o.id = s.ospedale_id) as assegnati, (
       select count(*) 
		   from sx_tabellone s 
		  where s.gestore_id is null 
		    and s.dasc is not null 
			 and o.id = s.ospedale_id) as non_assegnati, (
       select count(*) 
		   from sx_tabellone s 
		  where s.dasc is null 
			 and o.id = s.ospedale_id) as senza_dasc
  from sx_ospedali o;
