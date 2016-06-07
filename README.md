Redbox Digital

Installing

By GIT:

    clone this repo
    disable compilation if you use that.
    copy the files from the repo into the base folder of your magento install
    clear your cache
    re-enable compilation

By Composer:

    disable compilation

    Update the following to sections in your composer file:

   "require": {
           "sanjaymodasia/redboxdigitallinkedin": "dev-master"
      },
   "repositories": [
      {
            "type": "vcs",
            "url": "https://github.com/sanjaymodasia/RedboxDigitalLinkedin"
        }
    ],

    Update composer: composer.phar update
    Clear cache
    re-enable compilation

Un-Installing

By Manually:
    Open file from app/etc/modules/RedboxDigital_Linkedin.xml and make the changes like below
    <pre>
    <config>
        <modules>
            <RedboxDigital_Linkedin>
                <active>false</active>
                <codePool>community</codePool>
            </RedboxDigital_Linkedin>
        </modules>
    </config>
    </pre>
By Composer:
    
    Remove following to sections from your composer file:

   "require": {
           "sanjaymodasia/redboxdigitallinkedin": "dev-master"
      },
   "repositories": [
      {
            "type": "vcs",
            "url": "https://github.com/sanjaymodasia/RedboxDigitalLinkedin"
        }
    ],

    Update composer: composer.phar update
    Clear cache
    re-enable compilation

Removing customer custom attribute and module setup from core_resource table
    
    if you want to uninstall module completely from your store then use below code to clear linkedin_profile attribute and module setup from core_resource table
    <pre>
    <?php
    error_reporting(E_ALL | E_STRICT);
    $mageFilename = 'app/Mage.php';
    require_once $mageFilename;
    Mage::setIsDeveloperMode(true);
    umask(0);
    Mage::app();
    Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));

    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');

    try {
        $custAttr = 'linkedin_profile';  // here enter your attribute name which you want to remove
       
        $setup->removeAttribute('customer', $custAttr);
        echo $custAttr." attribute is removed";
    }
        catch (Mage_Core_Exception $e) {
        $this->_fault('data_invalid', $e->getMessage());
    }

    $sql = "DELETE FROM 'core_resource' WHERE 'code' = 'redboxdigital_linkedin_setup';";
    $connection = Mage::getSingleton('core/resource')->getConnection('core_write');

    try {
        $connection->query($sql);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    ?>
    </pre>
    
Making module work in any other custom theme:
    After installing this moduel if you found module is not working the please check edit.phtml file by enabling "Template Path Hints" through admin => system => configuration => Developer ( under advance tab from left side ) =>select Current Configuration Scope:(current store) => Debug => Template Path Hints => yes and check the path 
    
    if edit.phtml comes from any of your custom theme then go to that phtml file and below code
    <pre>
    <!-- Linkedin attribute starts here -->
    <?php 
    $attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
                    ->setCodeFilter('linkedin_profile')
                    ->getFirstItem();
    ?>
    <?php if($attributeInfo): ?>
        <?php $isRequired = Mage::getStoreConfig('customer/redboxdigital_linkedin/required');?>
        <div class="fieldset">
            <h2 class="legend"><?php echo $this->__('Additional Information') ?></h2>
            <ul class="form-list">
                <li class="fields">
                   <div class="field">
                       <label for="<?php echo $attributeInfo->getAttributeCode(); ?>" <?php if($isRequired):?>class="required"><em>*</em> <?php  else :?>><?php endif;?><?php echo $this->__($attributeInfo->getFrontendLabel()); ?></label>
                       <div class="input-box">
                       <?php if($attributeInfo->getFrontendInput()== 'text'):?>
                           <input type="text" name="<?php echo $attributeInfo->getAttributeCode(); ?>" id="<?php echo $attributeInfo->getAttributeCode(); ?>" value="<?php echo $this->escapeHtml($this->getCustomer()->getLinkedinProfile()) ?>" title="<?php echo $this->__($attributeInfo->getFrontendLabel()); ?>" class="<?php if($isRequired): ?>required-entry<?php endif; ?> input-text <?php echo $attributeInfo->getfrontendClass(); ?>" />
                       <?php endif;?>
                       </div>
                   </div>
               </li>
            </ul>
        </div>
    <?php endif; ?>
    <!-- Linkedin attribute ends here -->
    </pre>