<?php

class vhtypeController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/vehicletype.php';
        $formRender = true;
        $form = new form();
        $vehicletype = new vehicletype();

        $form->addElement('vhtype_code', 'Type Code', 'text', 'required|alpha_space');
        $form->addElement('vhtype_name', 'Vhltype Name ', 'text', 'required|alpha_space');

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $data = array(
                        'type_code' => $valid['vhtype_code'],
                        'type_name' => $valid['vhtype_name']
                    );
                    $vhtypeId = $vehicletype->add($data);

                    if ($vhtypeId) {

                        $feedback = 'Vehicle type added successfully';
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

        $this->view->form = $form;
    }

    public function editAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/vehicletype.php';
        $formRender = true;
        $vehicletype = new vehicletype();
        $form = new form();

        $ref = filter_input(INPUT_GET, 'ref', FILTER_UNSAFE_RAW);

        $vhtypeId = $this->view->decode($this->view->param['ref']);

        if (! $vhtypeId)
            die('tampered');

        $form->addElement('vhtype_code', 'Type Code', 'text', 'required|alpha_space');
        $form->addElement('vhtype_name', 'Vhltype Name ', 'text', 'required|alpha_space');

        $vhtypeDetails = $vehicletype->getVhltypeDetById($vhtypeId);

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $data = array(
                        'type_code' => $valid['vhtype_code'],
                        'type_name' => $valid['vhtype_name']
                    );

                    $modifyVhltype = $vehicletype->modify($data, $vhtypeDetails['type_id']);

                    $feedback = 'Vehicle type Updated successfully';
                    $this->view->NoViewRender = true;
                    $success = array(
                        'feedback' => $feedback
                    );
                    $_SESSION['feedback'] = $feedback;
                    $success = json_encode($success);
                    die($success);
                }
            }
        } else {
            $form->vhtype_code->setValue($vhtypeDetails['type_code']);
            $form->vhtype_name->setValue($vhtypeDetails['type_name']);
        }

        $this->view->form = $form;
    }

    public function listAction()
    {
        include __DIR__ . '/../admin/!model/vehicletype.php';

        $form = new form();

        $form->addElement('f_code', 'Code', 'text', 'alpha_space');
        $form->addElement('f_name', 'Name', 'text', 'alpha_space');

        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        }

        $filter_class = 'btn-primary';
        if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {
                $where = array(
                    'f_code' => @$valid['f_code'],
                    'f_name' => @$valid['f_name']
                );
            }
            $filter_class = 'btn-info';
        }

        $vhtypeObj = new vehicletype();

        // s($where);

        $typeList = $vhtypeObj->getVhltypePaginate(@$where);

        $this->view->typeList = $typeList;
        $this->view->form = $form;
        $this->view->vhtypeObj = $vhtypeObj;
        $this->view->filter_class = $filter_class;
    }

}
