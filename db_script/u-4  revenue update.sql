ALTER TABLE "mis_bill_revenue"
ADD "brev_comp_id" bigint NULL;

ALTER TABLE "mis_collection_revenue"
ADD "rev_comp_id" bigint NULL;


ALTER TABLE mis_bill_revenue
ADD COLUMN brev_comp_id BIGINT NULL COMMENT 'Company ID for revenue';

ALTER TABLE mis_collection_revenue
ADD COLUMN rev_comp_id BIGINT NULL COMMENT 'Company ID for collection revenue';

# coll rev 697, bill rev 711,712,713,714


^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^@@


WITH collection_data AS
  ( SELECT 1 AS rev_type,
           -- colldet.cdet_id AS rev_group_id,
           billdet.bdet_id as rev_group_id,
           colldet.cdet_coll_id AS rev_coll_id,
           colldet.cdet_bill_id AS rev_bill_id,
           item.item_vehicle AS rev_vhl_id,
           SUM(CASE WHEN bill.bill_oribill_amt = 0 THEN 0 ELSE ROUND((colldet.cdet_amt_paid / bill.bill_oribill_amt) * (billdet.bdet_qty * billdet.bdet_amt), 3) END) AS rev_revenue,
           0 AS deleted,
           vhcl.vhl_company AS rev_comp_id,
           1 AS is_synched
   FROM mis_collection_det AS colldet
   JOIN mis_collection AS collns ON collns.coll_id = colldet.cdet_coll_id
   AND collns.deleted= 0
   AND collns.coll_app_status = 1 AND collns.coll_src_type = 1
   JOIN mis_bill AS bill ON bill.bill_id = colldet.cdet_bill_id
   AND bill.bill_app_status = 1
   AND bill.deleted = 0
   AND bill.bill_cancellation_status = 0
   LEFT JOIN mis_bill_det AS billdet ON billdet.bdet_bill_id = bill.bill_id
   AND billdet.deleted = 0
   JOIN
     (SELECT bdet_bill_id,
             MAX(bdet_update_sts) AS sts_max
      FROM mis_bill_det
      WHERE deleted = 0
      GROUP BY bdet_bill_id) AS max_status_bill ON max_status_bill.bdet_bill_id = billdet.bdet_bill_id
   AND max_status_bill.sts_max = billdet.bdet_update_sts
   LEFT JOIN mis_item AS item ON item.item_id = billdet.bdet_item
   AND item.item_type = 1
   AND item.deleted = 0
   LEFT JOIN mis_vehicle AS vhcl ON vhcl.vhl_id = item.item_vehicle
   AND vhcl.deleted = 0
   WHERE colldet.deleted = 0
     AND colldet.cdet_src_type = 1
     AND colldet.cdet_status = 2
   GROUP BY 
            billdet.bdet_id,
            colldet.cdet_coll_id,
            colldet.cdet_bill_id,
            item.item_vehicle,
            vhcl.vhl_company )
INSERT INTO mis_collection_revenue ( rev_type, rev_group_id, rev_coll_id, rev_bill_id, rev_vhl_id, rev_revenue, deleted, rev_comp_id, is_synched)
SELECT *
FROM collection_data;





WITH bill_data AS (
    SELECT 
        1 AS brev_type,
        mis_bill_det.bdet_id AS brev_group_id,
        mis_bill_det.bdet_bill_id AS brev_bill_id,
        item.item_vehicle AS brev_vhl_id,  
        SUM(mis_bill_det.bdet_qty * mis_bill_det.bdet_amt) AS brev_revenue,
        0 AS deleted,
        1 AS is_synched,
        vhcl.vhl_company AS brev_comp_id  
    FROM mis_bill_det

   JOIN mis_bill AS bill ON bill.bill_id = mis_bill_det.bdet_bill_id
   AND bill.bill_app_status = 1
   AND bill.deleted = 0
   AND bill.bill_cancellation_status = 0

    LEFT JOIN mis_item AS item 
        ON item.item_id = mis_bill_det.bdet_item
        AND item.item_type = 1
        AND item.deleted = 0
    LEFT JOIN mis_vehicle AS vhcl 
        ON vhcl.vhl_id = item.item_vehicle
        AND vhcl.deleted = 0
    JOIN (
        SELECT 
            bdet_bill_id,
            MAX(bdet_update_sts) AS sts_max
        FROM mis_bill_det
        WHERE deleted = 0
        GROUP BY bdet_bill_id
    ) AS max_status 
        ON max_status.bdet_bill_id = mis_bill_det.bdet_bill_id
        AND max_status.sts_max = mis_bill_det.bdet_update_sts
    WHERE mis_bill_det.deleted = 0
    GROUP BY 
        mis_bill_det.bdet_bill_id,
        mis_bill_det.bdet_id,
        item.item_vehicle,
        vhcl.vhl_company
)

INSERT INTO mis_bill_revenue (
    brev_type, 
    brev_group_id, 
    brev_bill_id, 
    brev_vhl_id, 
    brev_revenue, 
    deleted, 
    is_synched,
    brev_comp_id
)
SELECT * FROM bill_data;



@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@^^


# pgsql

WITH ranked_data AS (
    SELECT 
        ROW_NUMBER() OVER () AS brev_id,  -- Generate a running number
        1 AS brev_type,
        mis_bill_det.bdet_id AS brev_group_id,
        mis_bill_det.bdet_bill_id AS brev_bill_id,
        item.item_vehicle AS brev_vhl_id,  
        SUM(mis_bill_det.bdet_qty * mis_bill_det.bdet_amt) AS brev_revenue,
        0 AS deleted,
        vhcl.vhl_company AS brev_comp_id  
    FROM mis_bill_det
    LEFT JOIN mis_item AS item 
        ON item.item_id = mis_bill_det.bdet_item
        AND item.item_type = 1
        AND item.deleted = 0
    LEFT JOIN mis_vehicle AS vhcl 
        ON vhcl.vhl_id = item.item_vehicle
        AND vhcl.deleted = 0
    JOIN (
        SELECT 
            bdet_bill_id,
            MAX(bdet_update_sts) AS sts_max
        FROM mis_bill_det
        WHERE deleted = 0
        GROUP BY bdet_bill_id
    ) AS max_status 
        ON max_status.bdet_bill_id = mis_bill_det.bdet_bill_id
        AND max_status.sts_max = mis_bill_det.bdet_update_sts
    WHERE mis_bill_det.deleted = 0
    GROUP BY 
        mis_bill_det.bdet_bill_id,
        mis_bill_det.bdet_id,
        item.item_vehicle,
        vhcl.vhl_company
)

INSERT INTO mis_bill_revenue (
    brev_id, 
    brev_type, 
    brev_group_id, 
    brev_bill_id, 
    brev_vhl_id, 
    brev_revenue, 
    deleted, 
    brev_comp_id
)
SELECT * FROM ranked_data;


# MYSQL

INSERT INTO mis_bill_revenue (
    brev_id, 
    brev_type, 
    brev_group_id, 
    brev_bill_id, 
    brev_vhl_id, 
    brev_revenue, 
    deleted, 
    brev_comp_id
)
SELECT 
    @rownum := @rownum + 1 AS brev_id,  -- Running number generation
    '1' AS brev_type,
    mis_bill_det.bdet_id AS brev_group_id,
    mis_bill_det.bdet_bill_id AS brev_bill_id,
    item.item_vehicle AS brev_vhl_id,  
    SUM(mis_bill_det.bdet_qty * mis_bill_det.bdet_amt) AS brev_revenue,
    '0' AS deleted,
    vhcl.vhl_company AS brev_comp_id  
FROM mis_bill_det
LEFT JOIN mis_item AS item 
    ON item.item_id = mis_bill_det.bdet_item
    AND item.item_type = 1
    AND item.deleted = 0
LEFT JOIN mis_vehicle AS vhcl 
    ON vhcl.vhl_id = item.item_vehicle
    AND vhcl.deleted = 0
JOIN (
    SELECT 
        bdet_bill_id,
        MAX(bdet_update_sts) AS sts_max
    FROM mis_bill_det
    WHERE deleted = 0
    GROUP BY bdet_bill_id
) AS max_status 
    ON max_status.bdet_bill_id = mis_bill_det.bdet_bill_id
    AND max_status.sts_max = mis_bill_det.bdet_update_sts
CROSS JOIN (SELECT @rownum := 0) AS init  -- Initialize row number variable
WHERE mis_bill_det.deleted = 0
GROUP BY 
    mis_bill_det.bdet_bill_id,
    mis_bill_det.bdet_id,
    item.item_vehicle,  vhcl.vhl_company;
    
    
    
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


INSERT INTO mis_bill_revenue (
    brev_type, 
    brev_group_id, 
    brev_bill_id, 
    brev_vhl_id, 
    brev_revenue, 
    deleted, 
    brev_comp_id
)
SELECT 
    '1' AS brev_type,
    mis_bill_det.bdet_id AS brev_group_id,
    mis_bill_det.bdet_bill_id AS brev_bill_id,
    COALESCE(item.item_vehicle, 99999) AS brev_vhl_id,  -- If NULL, insert '99999'
    SUM(mis_bill_det.bdet_qty * mis_bill_det.bdet_amt) AS brev_revenue,
    '0' AS deleted,
    COALESCE(vhcl.vhl_company, 1) AS brev_comp_id  -- If NULL, insert '1'
FROM mis_bill_det
LEFT JOIN mis_item AS item 
    ON item.item_id = mis_bill_det.bdet_item
    AND item.item_type = 1
    AND item.deleted = 0
LEFT JOIN mis_vehicle AS vhcl 
    ON vhcl.vhl_id = item.item_vehicle
    AND vhcl.deleted = 0
JOIN (
    SELECT 
        bdet_bill_id,
        MAX(bdet_update_sts) AS sts_max
    FROM mis_bill_det
    WHERE deleted = 0
    GROUP BY bdet_bill_id
) AS max_status 
    ON max_status.bdet_bill_id = mis_bill_det.bdet_bill_id
    AND max_status.sts_max = mis_bill_det.bdet_update_sts
WHERE mis_bill_det.deleted = 0
GROUP BY 
    mis_bill_det.bdet_bill_id,
    mis_bill_det.bdet_id,
    item.item_vehicle,
    vhcl.vhl_company;









           
    
    



    
  


