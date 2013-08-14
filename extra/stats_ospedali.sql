create or replace view stats_ospedali as
select o.sigla, o.gruppo, (
       select count(*) 
		   from sx_tabellone s 
		  where s.gestore_id is not null 
			 and o.id = s.ospedale_id) as totali, (
       select count(*) 
		   from sx_tabellone s 
		  where s.gestore_id is not null 
		    and s.priorita_id = 1
			 and o.id = s.ospedale_id) as normali, (
       select count(*) 
		   from sx_tabellone s 
		  where s.gestore_id is not null 
		    and s.priorita_id = 2
			 and o.id = s.ospedale_id) as attenzione, (
       select count(*) 
		   from sx_tabellone s 
		  where s.gestore_id is not null 
		    and s.priorita_id = 3
			 and o.id = s.ospedale_id) as alta, (
       select count(*) 
		   from sx_tabellone s 
		  where s.gestore_id is not null 
		    and s.priorita_id = 4
			 and o.id = s.ospedale_id) as definiti, (
       select count(*) 
		   from sx_tabellone s 
		  where s.gestore_id is not null 
		    and s.priorita_id = 5
			 and o.id = s.ospedale_id) as nuovi, (
       select count(*) 
		   from sx_tabellone s 
		  where s.gestore_id is not null 
		    and s.priorita_id = 6
			 and o.id = s.ospedale_id) as riattivati, (
       select count(*) 
		   from sx_tabellone s 
		  where s.gestore_id is not null 
		    and s.priorita_id = 7
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
