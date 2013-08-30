CREATE OR REPLACE VIEW stats_definiti AS
SELECT IF(g.sigla IS NULL, 'Vecchi', g.sigla) as sigla,
       (SELECT COUNT(*) 
		    FROM cal_eventi e
		    LEFT JOIN cal_eventi_sx es on es.id = e.id
			WHERE e.tipo_id = 38
			  AND e.titolo = 'definita'
			  AND e.data_ora > date_format(NOW() - INTERVAL 1 MONTH, '%Y-%m-%d') 
			  AND es.scheda_id IN (SELECT t.id
			                         FROM sx_tabellone t
											WHERE t.gestore_id = g.id)) as ultimo_mese_tutto,
       (SELECT COUNT(*) 
		    FROM cal_eventi e
		    LEFT JOIN cal_eventi_sx es on es.id = e.id
			WHERE e.tipo_id = 38
			  AND e.titolo = 'definita'
			  AND e.note NOT LIKE '%riattivato%'
			  AND e.data_ora > date_format(NOW() - INTERVAL 1 MONTH, '%Y-%m-%d') 
			  AND es.scheda_id IN (SELECT t.id
			                         FROM sx_tabellone t
											WHERE t.gestore_id = g.id)) as ultimo_mese,
       (SELECT e.data_ora
		    FROM cal_eventi e
		    LEFT JOIN cal_eventi_sx es on es.id = e.id
			WHERE e.tipo_id = 38
			  AND e.titolo = 'definita'
			  AND es.scheda_id IN (SELECT t.id
			                         FROM sx_tabellone t
											WHERE t.gestore_id = g.id)
			ORDER BY e.data_ora DESC
			LIMIT 1) as ultimo_definito_tutto,
       (SELECT e.data_ora
		    FROM cal_eventi e
		    LEFT JOIN cal_eventi_sx es on es.id = e.id
			WHERE e.tipo_id = 38
			  AND e.titolo = 'definita'
			  AND e.note NOT LIKE '%riattivato%'
			  AND es.scheda_id IN (SELECT t.id
			                         FROM sx_tabellone t
											WHERE t.gestore_id = g.id)
			ORDER BY e.data_ora DESC
			LIMIT 1) as ultimo_definito,
		 datediff(now(), (SELECT e.data_ora
                		     FROM cal_eventi e
		                    LEFT JOIN cal_eventi_sx es on es.id = e.id
			                WHERE e.tipo_id = 38
			                  AND e.titolo = 'definita'
			                  AND es.scheda_id IN (SELECT t.id
			                                         FROM sx_tabellone t
							              				    WHERE t.gestore_id = g.id)
		                 	 ORDER BY e.data_ora DESC
			                LIMIT 1)) as giorni_tutto,
		 datediff(now(), (SELECT e.data_ora
                		     FROM cal_eventi e
		                    LEFT JOIN cal_eventi_sx es on es.id = e.id
			                WHERE e.tipo_id = 38
			                  AND e.titolo = 'definita'
			                  AND e.note NOT LIKE '%riattivato%'
			                  AND es.scheda_id IN (SELECT t.id
			                                         FROM sx_tabellone t
							              				    WHERE t.gestore_id = g.id)
			                ORDER BY e.data_ora DESC
			               LIMIT 1)) as giorni,
       count(*) as totali
  FROM sx_tabellone s
  LEFT JOIN acl_gestori g ON s.gestore_id = g.id
 WHERE s.priorita_id = 4
 GROUP BY s.gestore_id
 ORDER BY sigla;
