<?php
/**
 * Manage Linkdin Attribute setup
 * 
 * @category    RedboxDigital
 * @package     RedboxDigital_Linkedin
 * @author      RedboxDigital
 */

$installer = $this;
$installer->startSetup();
$entity = $installer->getEntityTypeId('customer');

$installer->addAttribute($entity, 'linkedin_profile', array(
        'type' => 'text',
        'label' => 'Linkedin Profile',
        'input' => 'text',
        'visible' => TRUE,
        'required' => FALSE,
        'default_value' => '',
        'adminhtml_only' => '0',
        'frontend_class'	=> 'validate-url',
));

$forms = array(
    'adminhtml_customer',
    'customer_account_create',
    'customer_account_edit',
    'checkout_register',
    'adminhtml_checkout'
);

$attribute = Mage::getSingleton('eav/config')->getAttribute($installer->getEntityTypeId('customer'), 'linkedin_profile');
$attribute->setData('used_in_forms', $forms)
        ->setData("is_used_for_customer_segment", true)
        ->setData("is_system", 0);
        
$attribute->save();

$installer->endSetup();