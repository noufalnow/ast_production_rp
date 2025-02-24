UPDATE mis_expense
SET exp_billdt = exp_billdt + INTERVAL '2000 years'
WHERE EXTRACT(YEAR FROM exp_billdt) < 100;


-- UPDATE mis_expense
-- SET exp_billdt = DATE_ADD(exp_billdt, INTERVAL 2000 YEAR)
-- WHERE YEAR(exp_billdt) < 100;


