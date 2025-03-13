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

SELECT -- Total Amount
 SUM(bill_credit_amt) AS total_amount,
 -- Total Due Amount (Past Due)

 SUM(CASE WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) >= 0 THEN bill_credit_amt ELSE 0 END) AS due_amount,
 SUM(CASE WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) BETWEEN 1 AND 30 THEN bill_credit_amt ELSE 0 END) AS due_plus,
 SUM(CASE WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) BETWEEN 31 AND 60 THEN bill_credit_amt ELSE 0 END) AS due_plus_30,
 SUM(CASE WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) BETWEEN 61 AND 90 THEN bill_credit_amt ELSE 0 END) AS due_plus_60,
 SUM(CASE WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) BETWEEN 91 AND 120 THEN bill_credit_amt ELSE 0 END) AS due_plus_90,
 SUM(CASE WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) >= 121 THEN bill_credit_amt ELSE 0 END) AS due_plus_120_more
FROM mis_bill
LEFT JOIN mis_customer AS cust ON cust.cust_id = mis_bill.bill_customer_id
AND cust.deleted = 0
WHERE mis_bill.bill_cancellation_status = 0
  AND mis_bill.bill_app_status = 1
  AND mis_bill.bill_pstatus = 2
  AND mis_bill.deleted = 0;


SELECT 
    bill_id,
    bill_credit_amt,
    CASE
        WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) > 121 
            THEN 'due_plus_120_more'
        WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) BETWEEN 91 AND 120 
            THEN 'due_plus_90'
        WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) BETWEEN 61 AND 90 
            THEN 'due_plus_60'
        WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) BETWEEN 31 AND 60 
            THEN 'due_plus_30'
        WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) BETWEEN 1 AND 30 
            THEN 'due_plus'
        WHEN date_part('day', now() - (bill_rev_date + INTERVAL '60' DAY)) >= 0 
            THEN 'due_amount'
        ELSE 'not_due'
    END AS due_category
FROM mis_bill
LEFT JOIN mis_customer AS cust 
    ON cust.cust_id = mis_bill.bill_customer_id
    AND cust.deleted = 0
WHERE mis_bill.bill_cancellation_status = 0
  AND mis_bill.bill_app_status = 1
  AND mis_bill.bill_pstatus = 2
  AND mis_bill.deleted = 0;    


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