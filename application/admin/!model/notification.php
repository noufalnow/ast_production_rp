<?php

class notification extends db_table
{

    protected $_table = "mis_notification";

    protected $_pkey = "notif_id";

    public function add($data)
    {
        return parent::insert($data);
    }

    public function modify($data, $cond)
    {
        return parent::update($data, $cond);
    }

    public function getNotificationStatus()
    {
        $this->query("SELECT * FROM mis_notification");

        $this->_where[] = "(date_part('month', (notif_month)) = date_part('month', (Now()))
                               AND date_part('year', (notif_month)) = date_part('year', (Now())))";

        return parent::fetchRow($cond);
    }

    public function getNotificationReport($cond = array())
    {
        $this->query("SELECT
                       upd_note,
                       upd_title,   
                       CASE
                           WHEN upd_type = 1 THEN 'Employee'
                           WHEN upd_type = 2 THEN 'Property'
                           WHEN upd_type = 3 THEN 'Vehicle'
                           WHEN upd_type = 4 THEN 'Invoice'
                           WHEN upd_type = 5 THEN 'Company'
                       END AS txt_type,
                       CASE
                           WHEN upd_type = 1 THEN emp_fname ||' '||emp_mname||' '||emp_lname
                           WHEN upd_type = 2 THEN prop_fileno
                           WHEN upd_type = 3 THEN vhl_no
                           WHEN upd_type = 4 THEN 'AST/00' || bill_id::text
                           WHEN upd_type = 5 THEN upd_title
                       END AS ref_name,
                       user_fname ||' '|| user_lname AS user_name,
                                          to_char(upd_dttime,'DD/MM/YYYY') AS upd_dttime,
                                          to_char(upd_enddttime,'DD/MM/YYYY') AS upd_enddttime,
                                          to_char(core_updates.t_created,'DD/MM/YYYY HH24:MI:SS') AS dt_created
                FROM core_updates
                LEFT JOIN core_users AS users ON users.user_id = upd_reported
                AND users.deleted = 0
                LEFT JOIN mis_employee AS employee ON employee.emp_id = upd_type_refid
                AND employee.deleted = 0
                AND upd_type = 1
                LEFT JOIN mis_property AS poperty ON poperty.prop_id = upd_type_refid
                AND poperty.deleted = 0
                AND upd_type = 2
                LEFT JOIN mis_vehicle AS vehicle ON vehicle.vhl_id = upd_type_refid
                AND vehicle.deleted = 0
                AND upd_type = 3
                LEFT JOIN mis_bill AS bill ON bill.bill_id = upd_type_refid
                AND bill.deleted = 0
                AND upd_type = 4
                AND bill.bill_cancellation_status = 0
                WHERE (date_part('month', (upd_enddttime)) = date_part('month', (Now()))
                       AND date_part('year', (upd_enddttime)) = date_part('year', (Now())))
                AND upd_remainder = 1
                AND upd_status = 1;");

        $return['upd_list'] = parent::fetchQuery($cond);

        $this->query("SELECT doc_ref_type,
                       doc_type,
                       comp_name,
                       CASE
                           WHEN doc_ref_type = 1
                                AND doc_type = 1 THEN 'CR CERTIFICATE'
                           WHEN doc_ref_type = 1
                                AND doc_type = 2 THEN 'CR ID'
                           WHEN doc_ref_type = 1
                                AND doc_type = 3 THEN 'SIGNATORY'
                           WHEN doc_ref_type = 1
                                AND doc_type = 4 THEN 'ID CARD 1'
                           WHEN doc_ref_type = 1
                                AND doc_type = 5 THEN 'ID CARD 2'
                           WHEN doc_ref_type = 1
                                AND doc_type = 61 THEN 'PINK CERTIFICATE 1'
                           WHEN doc_ref_type = 1
                                AND doc_type = 62 THEN 'PINK CERTIFICATE 2'
                           WHEN doc_ref_type = 1
                                AND doc_type = 63 THEN 'PINK CERTIFICATE 3'
                           WHEN doc_ref_type = 1
                                AND doc_type = 64 THEN 'PINK CERTIFICATE 4'
                           WHEN doc_ref_type = 1
                                AND doc_type = 65 THEN 'PINK CERTIFICATE 5'
                           WHEN doc_type = 2001 THEN 'Employee Documents'
                           WHEN doc_type = 2002 THEN 'Property Documents'
                           WHEN doc_type = 2003 THEN 'Vehicle Documents'
                           WHEN doc_type = 2004 THEN 'WO Agreement Documents'
                           WHEN doc_type = 2006 THEN 'Building Documents'
                           WHEN doc_type = 2007 THEN 'Company Documents'
                       END AS doc_type_name,
                       doc_no ,
                       doc_desc ,
                       doc_remarks,
                       to_char(doc_issue_date, 'DD/MM/YYYY') AS start_date,
                       to_char(doc_expiry_date, 'DD/MM/YYYY') AS end_date
                FROM mis_documents

                LEFT JOIN core_company AS comp ON comp.comp_id = mis_documents.doc_ref_id
                AND comp.deleted = 0

                WHERE (doc_ref_type = 1 OR (doc_ref_type=2000  AND doc_type IN (2001,2002,2003,2004,2006,2007)))
                  AND (date_part('month', (doc_expiry_date)) = date_part('month', (Now()))
                       AND date_part('year', (doc_expiry_date)) = date_part('year', (Now())))
                  AND doc_remainder = 1");

        $return['all_docslist'] = parent::fetchQuery($cond);

        $this->query("SELECT emp_fileno ,
                           emp_mobileno ,
                           emp_fname ||' '||emp_mname||' '||emp_lname as emp_name,
                           emp_nationality ,
                           comp_disp_name ,
                           dept_name ,
                           desig_name ,
                           doc_type_name ,
                           doc_no,
                           doc_desc ,
                           doc_remarks,
                           doc_issue_date ,
                           doc_expiry_date
                    FROM mis_employee
                    LEFT JOIN core_comp_department AS comdept ON comdept.cmpdept_id = mis_employee.emp_comp_dept
                    AND comdept.deleted = 0
                    LEFT JOIN core_company AS comp ON comp.comp_id = comdept.cmpdept_comp_id
                    AND comp.deleted = 0
                    LEFT JOIN core_department AS dept ON dept.dept_id = comdept.cmpdept_dept_id
                    AND dept.deleted = 0
                    LEFT JOIN core_designation AS desig ON desig.desig_id = mis_employee.emp_desig
                    AND desig.deleted = 0
                    INNER JOIN
                      (SELECT doc_id,
                              doc_type,
                              doc_ref_type,
                              doc_ref_id,
                              CASE
                                  WHEN doc_type = 1 THEN 'Passport'
                                  WHEN doc_type = 2 THEN 'Resident ID'
                                  WHEN doc_type = 3 THEN 'Visa'
                                  WHEN doc_type = 4 THEN 'License'
                                  WHEN doc_type = 5 THEN 'Insurance'
                                  WHEN doc_type = 6 THEN 'PDO License'
                                  WHEN doc_type = 7 THEN 'PDO Passport'
                                  WHEN doc_type = 8 THEN 'H2S Card'
                                  WHEN doc_type = 9 THEN 'OXY Passport'
                                  WHEN doc_type = 10 THEN 'OXY License'
                                  WHEN doc_type = 11 THEN 'OXY H2S'
                                  WHEN doc_type = 12 THEN 'Work Contract'
                              END AS doc_type_name,
                              doc_no,
                              doc_desc,
                              doc_remarks,
                              to_char(doc_issue_date, 'DD/MM/YYYY') AS doc_issue_date,
                              to_char(doc_expiry_date, 'DD/MM/YYYY') AS doc_expiry_date,
                              doc_expiry_date AS doc_expiry_month
                       FROM
                         (SELECT max(doc_id) AS mdoc_id
                          FROM mis_documents
                          WHERE doc_ref_type = 2
                            AND deleted = 0
                          GROUP BY doc_type,
                                   doc_ref_type,
                                   doc_ref_id) max_group
                       LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
                       AND docs.deleted = 0) AS empdocs ON empdocs.doc_ref_id = mis_employee.emp_id
                    WHERE mis_employee.deleted = 0
                      AND mis_employee.emp_status = 1

                       AND (date_part('month', (doc_expiry_month)) = date_part('month', (Now()))
                       AND date_part('year', (doc_expiry_month)) = date_part('year', (Now())))  

                    ORDER BY doc_type DESC,
                             doc_expiry_month ASC");

        $return['emp_docslist'] = parent::fetchQuery($cond);

        $this->query("SELECT doc_type_name ,
                           doc_no ,
                           doc_remarks,
                           tnt_phone ,
                           tnt_tele,
                           doc_issue_date ,
                           doc_expiry_date ,
                           agr_amount,
                           bld_name ,
                           prop_fileno ,
                           tnt_full_name
                    FROM mis_property
                    LEFT JOIN mis_building AS build ON build.bld_id = mis_property.prop_building
                    AND build.deleted = 0
                    INNER JOIN
                      (SELECT doc_type,
                              doc_ref_id,
                              doc_ref_type,
                              CASE
                                  WHEN doc_type = 201 THEN 'Agreement'
                                  WHEN doc_type = 202 THEN 'Fire Safety Certificate'
                                  WHEN doc_type = 203 THEN 'Building Insurance'
                              END AS doc_type_name,
                              doc_no,
                              doc_desc,
                              doc_remarks,
                              agr_mobile,
                              to_char(doc_apply_date, 'DD/MM/YYYY') AS doc_apply_date,
                              to_char(doc_issue_date, 'DD/MM/YYYY') AS doc_issue_date,
                              to_char(doc_expiry_date, 'DD/MM/YYYY') AS doc_expiry_date,
                              doc_expiry_date AS doc_expiry_month,
                              agr_amount,
                              agr_tnt_id
                       FROM
                         (SELECT max(doc_id) AS mdoc_id
                          FROM mis_documents
                          WHERE doc_ref_type = 3
                            AND deleted = 0
                          GROUP BY doc_type,
                                   doc_ref_type,
                                   doc_ref_id) max_group
                       LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
                       AND docs.deleted = 0) AS propdocs ON propdocs.doc_ref_id = mis_property.prop_id
                    LEFT JOIN mis_tenants AS tenants ON tenants.tnt_id = propdocs.agr_tnt_id
                    AND tenants.deleted = 0
                    WHERE mis_property.deleted = 0
                      AND mis_property.prop_status = 2

                       AND (date_part('month', (doc_expiry_month)) = date_part('month', (Now()))
                       AND date_part('year', (doc_expiry_month)) = date_part('year', (Now()))) 

                    ORDER BY doc_type,
                             prop_building,
                             prop_cat DESC,
                             doc_expiry_month ASC");
        $return['prop_docslist'] = parent::fetchQuery($cond);

        $this->query("SELECT doc_type_name,
                           vhl_no,
                           vhl_model,
                           vhl_remarks,
                           comp_disp_name,
                           type_name,
                           doc_no,
                           doc_desc,
                           doc_remarks,
                           doc_issue_date ,
                           doc_expiry_date
                    FROM mis_vehicle
                    LEFT JOIN core_company AS comp ON comp.comp_id = mis_vehicle.vhl_company
                    AND comp.deleted = 0
                    LEFT JOIN mis_vehicle_type AS TYPE ON TYPE.type_id = mis_vehicle.vhl_type
                    AND TYPE.deleted = 0
                    INNER JOIN
                      (SELECT doc_id,
                              doc_type,
                              doc_ref_type,
                              CASE
                                  WHEN doc_type = 301 THEN 'Mulkia'
                                  WHEN doc_type = 302 THEN 'PDO'
                                  WHEN doc_type = 303 THEN 'Fitness'
                                  WHEN doc_type = 304 THEN 'IVMS'
                                  WHEN doc_type = 305 THEN 'Insurance'
                                  WHEN doc_type = 306 THEN 'Municipality Certificate'
                              END AS doc_type_name,
                              doc_ref_id,
                              doc_no,
                              doc_desc,
                              doc_remarks,
                              to_char(doc_apply_date, 'DD/MM/YYYY') AS doc_apply_date,
                              to_char(doc_issue_date, 'DD/MM/YYYY') AS doc_issue_date,
                              to_char(doc_expiry_date, 'DD/MM/YYYY') AS doc_expiry_date,
                              doc_expiry_date AS doc_expiry_month
                       FROM
                         (SELECT max(doc_id) AS mdoc_id
                          FROM mis_documents
                          WHERE doc_ref_type = 4
                            AND deleted = 0
                          GROUP BY doc_type,
                                   doc_ref_type,
                                   doc_ref_id) max_group
                       LEFT JOIN mis_documents AS docs ON docs.doc_id = max_group.mdoc_id
                       AND docs.deleted = 0) AS propdocs ON propdocs.doc_ref_id = mis_vehicle.vhl_id
                    WHERE mis_vehicle.deleted = 0

                       AND (date_part('month', (doc_expiry_month)) = date_part('month', (Now()))
                       AND date_part('year', (doc_expiry_month)) = date_part('year', (Now()))) 

                    ORDER BY doc_type,
                             vhl_no DESC,
                             doc_expiry_month ASC");
        $return['vhl_docslist'] = parent::fetchQuery($cond);

        return $return;
        // a($updList,$allDocsList,$empDocsList,$propDocsList,$vhlDocsList);
    }
}