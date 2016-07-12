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
  'conciergerieSend'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/conciergeriesend/:id',
      'defaults' => array(
      'controller'=>'admin',
      'action'=>'conciergeriesend',
      'page'=>1
    )
  ),
  'downloadimageconciergerie'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/public/upload/images/conciergerie/:id/:ext',
      'defaults' => array(
      'controller'=>'admin',
      'action'=>'downloadimageconciergerie',
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
  'edit-invoice'=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/admin/invoice/:user_id/:invoice_id',
      'defaults' => array(
      'invoice_id'=>null,
      'controller'=>'admin',
      'action'=>'edit-invoice',
    )
  ),
  "cancel-next-invoice"=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/admin/cancel-next-invoice/:invoice_id',
      'defaults' => array(
      'controller'=>'admin',
      'action'=>'cancel-next-invoice',
    )
  ),
  "cancel-invoice"=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/admin/cancel-invoice/:invoice_id',
      'defaults' => array(
      'controller'=>'admin',
      'type'=>'invoice',
      'action'=>'cancel-invoice',
    )
  ),
  "show-contract"=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/user/show-contract/:invoice_id',
      'defaults' => array(
      'controller'=>'user',
      'type'=>'contract',
      'action'=>'show-contract',
    )
  ),
  "show-invoice"=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/user/show-invoice/:invoice_id',
      'defaults' => array(
      'controller'=>'user',
      'type'=>'invoice',
      'action'=>'show-contract',
    )
  ),
  "show-invoice-tattoobox"=>array(
    'type'   => 'Zend_Controller_Router_Route',
    'route'  => '/:lang/user/show-invoice-tattoobox/:code',
      'defaults' => array(
      'controller'=>'user',
      'action'=>'show-invoice-tattoobox',
    )
  ),


);
