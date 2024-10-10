<?php

class vmanController extends mvc
{

    public function addAction()
    {
        $this->view->response('ajax');
        include __DIR__ . '/../admin/!model/vehicleman.php';
        $formRender = true;
        $form = new form();
        $vehicleman = new vehicleman();

        $form->addElement('vman_code', 'Manufacturer  Code', 'text', 'required|alpha_space');
        $form->addElement('vman_name', 'Manufacturer  Name ', 'text', 'required|alpha_space');

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $data = array(
                        'vman_code' => $valid['vman_code'],
                        'vman_name' => $valid['vman_name']
                    );
                    $vmanId = $vehicleman->add($data);

                    if ($vmanId) {

                        $feedback = 'Vehicle manufacturer added successfully';
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
        include __DIR__ . '/../admin/!model/vehicleman.php';
        $formRender = true;
        $vehicleman = new vehicleman();
        $form = new form();

        $ref = filter_input(INPUT_GET, 'ref', FILTER_UNSAFE_RAW);

        $vmanId = $this->view->decode($this->view->param['ref']);

        if (! $vmanId)
            die('tampered');

        $form->addElement('vman_code', 'Manufacturer  Code', 'text', 'required|alpha_space');
        $form->addElement('vman_name', 'Manufacturer  Name ', 'text', 'required|alpha_space');

        $vmanDetails = $vehicleman->getVhlmanDetById($vmanId);

        if ($_POST) {
            if (! isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                die('---'); // exit script outputting json data
            } else {

                $valid = $form->vaidate($_POST, $_FILES);
                $valid = $valid[0];
                if ($valid == true) {

                    $data = array(
                        'vman_code' => $valid['vman_code'],
                        'vman_name' => $valid['vman_name']
                    );

                    $modifyVhlman = $vehicleman->modify($data, $vmanDetails['vman_id']);

                    $feedback = 'Vehicle manufacturer Updated successfully';
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
            $form->vman_code->setValue($vmanDetails['vman_code']);
            $form->vman_name->setValue($vmanDetails['vman_name']);
        }

        $this->view->form = $form;
    }

    public function listAction()
    {
        include __DIR__ . '/../admin/!model/vehicleman.php';

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

        $vmanObj = new vehicleman();

        // s($where);

        $manList = $vmanObj->getVhlmanPaginate(@$where);

        $this->view->manList = $manList;
        $this->view->form = $form;
        $this->view->vmanObj = $vmanObj;
        $this->view->filter_class = $filter_class;
    }

}
