UPDATE "mis_collection_revenue" SET
"rev_revenue" = '1267.745'
WHERE "rev_id" = '421';

UPDATE "mis_collection_revenue" SET
"rev_revenue" = '3502.275'
WHERE "rev_id" = '526';


SELECT comp_id,
           COALESCE(bill_rev_amt, 0) - COALESCE(coll_net_amt, 0) AS balance_amount
   FROM core_company
   LEFT JOIN
     ( SELECT brev_comp_id,
              SUM(COALESCE(brev_revenue, 0)) AS bill_rev_amt
      FROM mis_bill_revenue
      JOIN mis_bill ON bill_id = brev_bill_id
      AND bill_cancellation_status = 0
      AND bill_app_status = 1
      AND mis_bill.deleted = 0
      WHERE mis_bill_revenue.deleted = '0'
      GROUP BY brev_comp_id ) AS bill_revenue ON bill_revenue.brev_comp_id = core_company.comp_id 

   LEFT JOIN
     ( SELECT rev_comp_id,
              SUM(COALESCE(rev_revenue, 0)) AS coll_rev_amt,
              SUM(COALESCE(rev_revenue_adjst, 0)) AS coll_dis_amt,
              SUM(COALESCE(rev_revenue_adjst, 0)) + SUM(COALESCE(rev_revenue, 0)) AS coll_net_amt
      FROM mis_collection_revenue
      JOIN mis_collection ON coll_id = rev_coll_id
      AND coll_app_status = 1
      AND coll_src_type = 1
      AND mis_collection.deleted = 0
      WHERE mis_collection_revenue.deleted = '0'
      GROUP BY rev_comp_id ) AS collection_revenue ON collection_revenue.rev_comp_id = core_company.comp_id ;


-- <<<<<<<<<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>>>>>>

--- Consolidated

SELECT -- Total Amount
 SUM(balance_amount) AS total_amount,
 SUM(CASE WHEN date_part('day', now() - bill_rev_date) BETWEEN 0 AND 60 THEN balance_amount ELSE 0 END) AS not_due,
 SUM(CASE WHEN date_part('day', now() - bill_rev_date) > 60 THEN balance_amount ELSE 0 END) AS due_amount,
 SUM(CASE WHEN date_part('day', now() - bill_rev_date) BETWEEN 61 AND 90 THEN balance_amount ELSE 0 END) AS "Due(0-30)",
 SUM(CASE WHEN date_part('day', now() - bill_rev_date) BETWEEN 91 AND 120 THEN balance_amount ELSE 0 END) AS "Due(30-60)",
 SUM(CASE WHEN date_part('day', now() - bill_rev_date) BETWEEN 121 AND 150 THEN balance_amount ELSE 0 END) AS "Due(60-90)",
 SUM(CASE WHEN date_part('day', now() - bill_rev_date) BETWEEN 151 AND 180 THEN balance_amount ELSE 0 END) AS "Due(90-120)",
 SUM(CASE WHEN date_part('day', now() - bill_rev_date) >180 THEN balance_amount ELSE 0 END) AS "Due(120 and More)"
FROM
  (SELECT mis_bill.*,
          COALESCE(bill_rev_amt, 0) - COALESCE(coll_net_amt, 0) AS balance_amount
   FROM mis_bill
   LEFT JOIN
     (SELECT brev_bill_id AS bill_id,
             SUM(COALESCE(brev_revenue, 0)) AS bill_rev_amt
      FROM mis_bill_revenue
      JOIN mis_bill ON bill_id = brev_bill_id
      AND bill_cancellation_status = 0
      AND bill_app_status = 1
      AND mis_bill.deleted = 0
      WHERE mis_bill_revenue.deleted = '0' -- and brev_comp_id IN (1)

      GROUP BY brev_bill_id) AS bill_revenue ON bill_revenue.bill_id = mis_bill.bill_id
   LEFT JOIN
     (SELECT rev_bill_id AS bill_id,
             SUM(COALESCE(rev_revenue, 0)) AS coll_rev_amt,
             SUM(COALESCE(rev_revenue_adjst, 0)) AS coll_dis_amt,
             SUM(COALESCE(rev_revenue_adjst, 0)) + SUM(COALESCE(rev_revenue, 0)) AS coll_net_amt
      FROM mis_collection_revenue
      JOIN mis_collection ON coll_id = rev_coll_id
      AND coll_app_status = 1
      AND coll_src_type = 1
      AND mis_collection.deleted = 0
      WHERE mis_collection_revenue.deleted = '0' -- and  rev_comp_id IN (1)

      GROUP BY rev_bill_id) AS collection_revenue ON collection_revenue.bill_id = mis_bill.bill_id
   WHERE bill_cancellation_status = 0
     AND bill_app_status = 1
     AND mis_bill.deleted = 0
   ORDER BY bill_id) mis_bill_comp
LEFT JOIN mis_customer AS cust ON cust.cust_id = mis_bill_comp.bill_customer_id
AND cust.deleted = 0;




SELECT bill_id,
       balance_amount,
       CASE
           WHEN date_part('day', now() - bill_rev_date) BETWEEN 0 AND 60 THEN 'not_due'
           WHEN date_part('day', now() - bill_rev_date) BETWEEN 61 AND 90 THEN 'Due(0-30)'
           WHEN date_part('day', now() - bill_rev_date) BETWEEN 91 AND 120 THEN 'Due(30-60)'
           WHEN date_part('day', now() - bill_rev_date) BETWEEN 121 AND 150 THEN 'Due(60-90)'
           WHEN date_part('day', now() - bill_rev_date) BETWEEN 151 AND 180 THEN 'Due(90-120)'
           WHEN date_part('day', now() - bill_rev_date) >180 THEN 'Due(120 and More)'
           WHEN date_part('day', now() - bill_rev_date) > 60 THEN 'due_amount'
       END AS due_category
FROM
  (SELECT mis_bill.*,
          COALESCE(bill_rev_amt, 0) - COALESCE(coll_net_amt, 0) AS balance_amount
   FROM mis_bill
   LEFT JOIN
     (SELECT brev_bill_id AS bill_id,
             SUM(COALESCE(brev_revenue, 0)) AS bill_rev_amt
      FROM mis_bill_revenue
      JOIN mis_bill ON bill_id = brev_bill_id
      AND bill_cancellation_status = 0
      AND bill_app_status = 1
      AND mis_bill.deleted = 0
      WHERE mis_bill_revenue.deleted = '0' -- and brev_comp_id IN (1)

      GROUP BY brev_bill_id) AS bill_revenue ON bill_revenue.bill_id = mis_bill.bill_id
   LEFT JOIN
     (SELECT rev_bill_id AS bill_id,
             SUM(COALESCE(rev_revenue, 0)) AS coll_rev_amt,
             SUM(COALESCE(rev_revenue_adjst, 0)) AS coll_dis_amt,
             SUM(COALESCE(rev_revenue_adjst, 0)) + SUM(COALESCE(rev_revenue, 0)) AS coll_net_amt
      FROM mis_collection_revenue
      JOIN mis_collection ON coll_id = rev_coll_id
      AND coll_app_status = 1
      AND coll_src_type = 1
      AND mis_collection.deleted = 0
      WHERE mis_collection_revenue.deleted = '0' -- and  rev_comp_id IN (1)

      GROUP BY rev_bill_id) AS collection_revenue ON collection_revenue.bill_id = mis_bill.bill_id
   WHERE bill_cancellation_status = 0
     AND bill_app_status = 1
     AND mis_bill.deleted = 0
   ORDER BY bill_id) mis_bill_comp
LEFT JOIN mis_customer AS cust ON cust.cust_id = mis_bill_comp.bill_customer_id
AND cust.deleted = 0
WHERE balance_amount > 0 ;  


-- Bill level 

  SELECT mis_bill.bill_id,
       COALESCE(bill_rev_amt, 0) - COALESCE(coll_net_amt, 0) AS balance_amount
FROM mis_bill
LEFT JOIN
  (SELECT brev_bill_id AS bill_id,
          SUM(COALESCE(brev_revenue, 0)) AS bill_rev_amt
   FROM mis_bill_revenue
   JOIN mis_bill ON bill_id = brev_bill_id
   AND bill_cancellation_status = 0
   AND bill_app_status = 1
   AND mis_bill.deleted = 0
   WHERE mis_bill_revenue.deleted = '0'
   GROUP BY brev_bill_id) AS bill_revenue ON bill_revenue.bill_id = mis_bill.bill_id
LEFT JOIN
  (SELECT rev_bill_id AS bill_id,
          SUM(COALESCE(rev_revenue, 0)) AS coll_rev_amt,
          SUM(COALESCE(rev_revenue_adjst, 0)) AS coll_dis_amt,
          SUM(COALESCE(rev_revenue_adjst, 0)) + SUM(COALESCE(rev_revenue, 0)) AS coll_net_amt
   FROM mis_collection_revenue
   JOIN mis_collection ON coll_id = rev_coll_id
   AND coll_app_status = 1
   AND coll_src_type = 1
   AND mis_collection.deleted = 0
   WHERE mis_collection_revenue.deleted = '0'
   GROUP BY rev_bill_id) AS collection_revenue ON collection_revenue.bill_id = mis_bill.bill_id
WHERE bill_cancellation_status = 0
  AND bill_app_status = 1
  AND mis_bill.deleted = 0
ORDER BY bill_id 