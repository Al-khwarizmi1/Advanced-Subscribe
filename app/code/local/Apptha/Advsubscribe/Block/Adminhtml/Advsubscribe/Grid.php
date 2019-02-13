<?php

class Apptha_Advsubscribe_Block_Adminhtml_Advsubscribe_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('advsubscribeGrid');
      $this->setDefaultSort('advsubscribe_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('advsubscribe/advsubscribe')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('advsubscribe_id', array(
          'header'    => Mage::helper('advsubscribe')->__('ID'),
          'align'     =>'center',
         
          'width'     => '50px',
          'index'     => 'advsubscribe_id',
      ));

      $this->addColumn('email_id', array(
          'header'    => Mage::helper('advsubscribe')->__('Email Id'),
         	'align'     =>'left',
			'width'     => '250px',	
          'index'     => 'email_id',
      ));

	 
      $this->addColumn('categori_id ', array(
			'header'    => Mage::helper('advsubscribe')->__('Category IDs'),
			'width'     => '50px',
      			'align'     =>'center',
      
			'index'     => 'categori_id',
      ));
	  $this->addColumn('status', array(
			'header'    => Mage::helper('advsubscribe')->__('Status'),
			'width'     => '50px',
	  'align'     =>'center',
			'index'     => 'status',
      ));
      $this->addColumn('follower', array(
			'header'    => Mage::helper('advsubscribe')->__('Is Follower'),
			'width'     => '50px',
	  		'align'     =>'center',
			'index'     => 'follower',
      ));
      
      $this->addColumn('created_time', array(
			'header'    => Mage::helper('advsubscribe')->__('Created On'),
			'width'     => '100px',
			'index'     => 'created_time',
      ));
      $this->addColumn('update_time', array(
			'header'    => Mage::helper('advsubscribe')->__('Modified On'),
			'width'     => '100px',
			'index'     => 'update_time',
      ));
      

   /*   $this->addColumn('status', array(
          'header'    => Mage::helper('advsubscribe')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	*/  
     
		
		$this->addExportType('*/*/exportCsv', Mage::helper('advsubscribe')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('advsubscribe')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('advsubscribe_id');
        $this->getMassactionBlock()->setFormFieldName('advsubscribe');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('advsubscribe')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('advsubscribe')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('advsubscribe/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));

       /* $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('advsubscribe')->__('Change status'),
         *///    'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
          /*   'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('advsubscribe')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        */
        return $this;
    }

  public function getRowUrl($row)
  {
      //return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}