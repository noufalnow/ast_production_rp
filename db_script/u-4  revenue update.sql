ALTER TABLE "mis_bill_revenue"
ADD "brev_comp_id" bigint NULL;

ALTER TABLE "mis_collection_revenue"
ADD "rev_comp_id" bigint NULL;


ALTER TABLE mis_bill_revenue
ADD COLUMN brev_comp_id BIGINT NULL COMMENT 'Company ID for revenue';

ALTER TABLE mis_collection_revenue
ADD COLUMN rev_comp_id BIGINT NULL COMMENT 'Company ID for collection revenue';

# coll rev 697, bill rev 711,712,713,714


###################### TEMP ##############################
    
    
    SELECT SUM(bill_credit_amt) AS credit_amt
FROM mis_bill
WHERE mis_bill.bill_app_status = 1
  AND mis_bill.bill_pstatus = 2
  AND mis_bill.deleted = 0
  AND mis_bill.bill_cancellation_status = 0;


SELECT TO_CHAR(coll.coll_paydate, 'YYYY-MM') AS year_month,
       SUM(cdet_amt_paid) AS paid_amount
FROM mis_collection AS coll
LEFT JOIN mis_collection_det AS coldet ON coll.coll_id = coldet.cdet_coll_id
AND coldet.deleted = 0
AND coldet.cdet_src_type = 1
WHERE coll.coll_src_type = 2
  AND coll.coll_app_status = 1
  AND coll.deleted = 0
GROUP BY TO_CHAR(coll.coll_paydate, 'YYYY-MM');




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





SELECT sum(brev_revenue) AS revenue,
       brev_bill_id,
       vhl_company
FROM mis_bill
JOIN mis_bill_revenue AS brev ON brev.brev_bill_id = bill_id
AND brev.deleted= 0
JOIN mis_vehicle AS veh ON veh.veh_id = brev.brev_vhl_id
AND veh.deleted = 0

WHERE mis_bill.bill_cancellation_status = 0
  AND mis_bill.bill_app_status = 1
  AND mis_bill.bill_pstatus = 2
  AND mis_bill.deleted = 0;


GROUP BY brev_bill_id,
         vhl_company
FROM mis_bill




CREATE VIEW view_company_bill AS



SELECT brev.brev_bill_id,
       veh.vhl_company,
       SUM(brev.brev_revenue) AS comp_bill_amt,
       SUM(brev.brev_revenue) - SUM(colrev.rev_revenue) AS comp_bal_amt
FROM mis_bill
JOIN mis_bill_revenue AS brev ON brev.brev_bill_id = mis_bill.bill_id
AND brev.deleted = 0
LEFT JOIN mis_collection_revenue AS colrev ON colrev.rev_bill_id = mis_bill.bill_id
AND colrev.deleted = 0
JOIN mis_vehicle AS veh ON veh.vhl_id = brev.brev_vhl_id
AND veh.deleted = 0
WHERE mis_bill.bill_cancellation_status = 0
  AND mis_bill.bill_app_status = 1
  AND mis_bill.bill_pstatus = 2
  AND mis_bill.deleted = 0
GROUP BY brev.brev_bill_id,
         veh.vhl_company;



SELECT 
    DATE_FORMAT(coll.coll_paydate, '%Y-%m') AS 'year_month',
    SUM(colrev.rev_revenue) AS received_amount
FROM mis_collection AS coll
JOIN mis_collection_det AS coldet 
    ON coll.coll_id = coldet.cdet_coll_id
    AND coldet.deleted = 0
    AND coldet.cdet_src_type = 1
JOIN mis_collection_revenue AS colrev 
    ON colrev.rev_coll_id = coll.coll_id
    AND colrev.deleted = 0
WHERE coll.coll_src_type = 1
  AND coll.coll_app_status = 1
  AND coll.deleted = 0
GROUP BY DATE_FORMAT(coll.coll_paydate, '%Y-%m');    


select bdet_item,bdet_qty,bdet_amt from 
mis_bill_det
left join 


#########################################################################











           
    
    



    
  


