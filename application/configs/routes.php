<?
return array(
  'galery'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/galery/:page',
      'defaults' => array(
      'controller'=>'location',
      'action'=>'galery',
      'page'=>1
    )
  ),
  'conventions'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/conventions',
      'defaults' => array(
      'controller'=>'location',
      'action'=>'conventions',
      'page'=>1
    )
  ),
  'creations'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/creation',
      'defaults' => array(
      'controller'=>'index',
      'action'=>'creation',
      'page'=>1
    )
  ),
  'untattoo'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/detatouage',
      'defaults' => array(
      'controller'=>'index',
      'action'=>'untattoo',
      'page'=>1
    )
  ),
  'conciergerie'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/conseil',
      'defaults' => array(
      'controller'=>'index',
      'action'=>'conciergerie',
      'page'=>1
    )
  ),
  'updateName'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/updatename',
      'defaults' => array(
      'controller'=>'admin',
      'action'=>'updateName',
      'page'=>1
    )
  ),    
  'galery-focus'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/galery-focus/:id',
      'defaults' => array(
      'controller'=>'location',
      'action'=>'galery',
      'page'=>1
    )
  ),
  'galery-with-filters'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/galery/f/:filters/:page',
      'defaults' => array(
      'controller'=>'location',
      'action'=>'galery',
      'page'=>1
    )
  ),
  'claim-tattoobox'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/edit-org-tattoobox/:id',
      'defaults' => array(
      'controller'=>'organization',
      'action'=>'claim-tattoobox',
    )
  ),
);
