<?php

class Ripple_Ripple_Model_Resource_Ipn_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{ 
	protected function _construct()
    {
        $this->_init('Ripple/ipn');
    }
}

?>