<?php

class companyController extends mvc
{

    public function statementAction()
    {
        $this->view->response('window');
        require_once __DIR__ . '/../admin/!model/vehicle.php';

        $form = new form();
        $form->addElement('f_model', 'Model ', 'text', '');
        $form->addElement('f_vhlno', 'Vehicle No ', 'text', '');
        $form->addElement('f_monthpick', 'Select Month ', 'text', '', '', array(
            '' => 'readonly'
        ));

        require_once __DIR__ . '/../admin/!model/company.php';
        $compModelObj = new company();
        $this->view->compList  = $compList = $compModelObj->getCompanyNamePair();
        $form->addElement('f_company', 'Company', 'select', '', array(
            'options' => $compList
        ));

        require_once __DIR__ . '/../admin/!model/vehicletype.php';
        $vhlTypeModelObj = new vehicletype();
        $typeList = $vhlTypeModelObj->getVehiclePair();

        $form->addElement('f_type', 'Vehice Type', 'select', '', array(
            'options' => $typeList
        ));

        if (isset($_GET) && $_GET['clear'] == 'All') {
            $form->reset();
            unset($_GET);
        }
        $where = [];
        $filter_class = 'btn-primary';
        if (is_array($_GET) && count(array_filter($_GET)) > 0) {
            $valid = $form->vaidate($_GET);
            $valid = $valid[0];
            if ($valid == true) {
                $where = array(
                    'f_company' => @$valid['f_company'],
                    'f_monthpick' => @$valid['f_monthpick']
                );
            }
            $filter_class = 'btn-info';
        }

        
        require_once __DIR__ . '/../admin/!model/property.php';
        $propertyObj = new property(); 
        $this->view->propertyObj = $propertyObj;
        
        
        $this->view->financialRevanew = $propertyObj->getFinancialRevenue($where);
        $this->view->financialExpense = $propertyObj->getFinancialExpense($where);
        
        
        if (empty(@$valid['f_monthpick'])) 
            @$valid['f_monthpick'] = date('m/Y');
        
        $this->view->f_monthval =  @$valid['f_monthpick'];

        $date = DateTime::createFromFormat('m/Y', @$valid['f_monthpick']);
        
        // Format the date as 'M, Y' (e.g., 'Sep, 2024')
        $this->view->f_monthpick =  $date->format('M, Y');
        

        
        require_once __DIR__ . '/../admin/!model/collection.php';
        $collectionObj = new collection();
        $this->view->collectionObj = $collectionObj;
        
        
        require_once __DIR__ . '/../admin/!model/expense.php';
        $expenseObj = new expense();
        $this->view->expenseObj = $expenseObj;


        $this->view->filter_class = $filter_class;
        $this->view->vehicleList = $vehicleList;
        $this->view->form = $form;
    }

}