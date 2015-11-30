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
