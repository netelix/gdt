<?php

class OrganizationController extends Uop_Controller_Action
{
  protected $resource_table = 'organizations';
  public function init()
  {
    /* Initialize action controller here */
  }

  public function indexAction()
  {
    // action body
  }

  public function showAction()
  {
    // action body
  }

  public function editAction()
  {
    $this->view->resource = $resource = $this->_findResource(array('privilege'=>'edit'));
    $this->view->form = $form = App::form('organization_edit', $resource); 
  }

}



