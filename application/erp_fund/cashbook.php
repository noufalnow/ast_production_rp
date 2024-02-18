<?php

class cashbookController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        require_once __DIR__ . '/../admin/!model/item.php';
        $formRender = true;
        $form = new form();

        $form->addElement('debitdt', 'Date', 'text', 'date|required', '', array(
            'class' => 'date_picker'
        ));
        $form->addElement('amount', 'Debit', 'float', 'required|numeric');
        $form->addElement('note', 'Note', 'textarea', 'required');

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $billdt = DateTime::createFromFormat(DF_DD, $valid['debitdt']);
                    $billdt = date_format($billdt, DFS_DB);

                    require_once __DIR__ . '/../admin/!model/cashbook.php';
                    $cashBookObj = new cashbook();
                    $cbData = array(
                        'cb_type' => CASH_BOOK_PER,
                        'cb_type_ref' => USER_ID,
                        'cb_debit' => $valid['amount'],
                        'cb_date' => $billdt,
                        'cb_debit_note' => $valid['note']
                    );
                    $debit = $cashBookObj->add($cbData);

                    if ($debit) {

                        $feedback = 'Debit details added successfully';
                        $this->view->NoViewRender = true;
                        $success = array(
                            'feedback' => $feedback
                        );
                        $_SESSION['feedback'] = $feedback;
                        $success = json_encode($success);
                        die($success);
                    }
                }
            }
        }

         $this->view ->form =  $form;
    }

    public function cashreportAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/cashbook.php';
        $cashBookObj = new cashbook();
        $form = new form();

        $form->addElement('f_code', 'Code', 'text', 'alpha_space');
        $form->addElement('f_name', 'Name', 'text', 'alpha_space');
        $form->addElement('f_remarks', 'Description', 'text', 'alpha_space');
        $form->addElement('f_price', 'Price', 'text', 'alpha_space');

        $decRefId = $this->view->decode($this->view->param['ref']);

        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        }

        if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {
                $where = array(
                    'f_code' => @$valid['f_code'],
                    'f_name' => @$valid['f_name'],
                    'f_remarks' => @$valid['f_remarks'],
                    'f_price' => @$valid['f_price']
                );
            }
        }

        // s($where);
        $where['cb_type_ref_ex'] = 1999;
        $where['cb_type_ref'] = $decRefId;

        $cashBookList = $cashBookObj->getCashBooksReport(@$where);
        $this->view->cashBookList = $cashBookList;
    }


    public function listAction()
    {
        require_once __DIR__ . '/../admin/!model/cashbook.php';
        $cashBookObj = new cashbook();

        $where['cb_type_ref'] = USER_ID;
        $cashBookList = $cashBookObj->getCashBooksPaginate(@$where);
        $offset = $cashBookObj->_voffset;

        $this->view->cashBookObj = $cashBookObj;
        $this->view->cashBookList = $cashBookList;
        $this->view->offset = $offset;
    }

}
