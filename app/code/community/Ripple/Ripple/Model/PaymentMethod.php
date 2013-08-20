<?php
	/**
	* Our test CC module adapter
	*/
	class Ripple_Ripple_Model_PaymentMethod extends Mage_Payment_Model_Method_Abstract
	{
		/**
		* unique internal payment method identifier
		*
		* @var string [a-z0-9_]
		*/
		protected $_code = 'Ripple';
	 
		/**
		 * Here are examples of flags that will determine functionality availability
		 * of this module to be used by frontend and backend.
		 *
		 * @see all flags and their defaults in Mage_Payment_Model_Method_Abstract
		 *
		 * It is possible to have a custom dynamic logic by overloading
		 * public function can* for each flag respectively
		 */
		 
		/**
		 * Is this payment method a gateway (online auth/charge) ?
		 */
		protected $_isGateway               = true;
	 
		/**
		 * Can authorize online?
		 */
		protected $_canAuthorize            = true;
	 
		/**
		 * Can capture funds online?
		 */
		protected $_canCapture              = false;
	 
		/**
		 * Can capture partial amounts online?
		 */
		protected $_canCapturePartial       = false;
	 
		/**
		 * Can refund online?
		 */
		protected $_canRefund               = false;
	 
		/**
		 * Can void transactions online?
		 */
		protected $_canVoid                 = false;
	 
		/**
		 * Can use this payment method in administration panel?
		 */
		protected $_canUseInternal          = true;
	 
		/**
		 * Can show this payment method as an option on checkout payment page?
		 */
		protected $_canUseCheckout          = true;
	 
		/**
		 * Is this payment method suitable for multi-shipping checkout?
		 */
		protected $_canUseForMultishipping  = true;
	 
		/**
		 * Can save credit card information for future processing?
		 */
		protected $_canSaveCc = false;
		
		//protected $_formBlockType = 'Ripple/form';
		//protected $_infoBlockType = 'Ripple/info';
		
		public function canUseCheckout()
		{		
			$ripple_host = Mage::getStoreConfig('payment/Ripple/Ripple_host');
			if (!$ripple_host or !strlen($ripple_host))
			{
				Mage::log('Ripple/Ripple: Host not entered');
				return false;
			}

			$ripple_port = Mage::getStoreConfig('payment/Ripple/Ripple_port');
			if (!$ripple_port or !strlen($ripple_port))
			{
				Mage::log('Ripple/Ripple: Port not entered');
				return false;
			}
			
			return $this->_canUseCheckout;
		}

		public function authorize(Varien_Object $payment, $amount) 
		{
			$quoteId = $payment->getOrder()->getQuoteId();
			$ipn = Mage::getModel('Ripple/ipn');
			if (!$ipn->GetQuotePaid($quoteId))
			{
				Mage::throwException("Order not paid for. Please pay first and then Place your Order.");
			}

			return $this;
		}
	}
?>