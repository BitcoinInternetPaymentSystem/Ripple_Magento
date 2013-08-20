<?php
	class Ripple_Ripple_Block_Iframe extends Mage_Checkout_Block_Onepage_Payment
	{
		protected function _construct()
		{      
			$this->setTemplate('Ripple/iframe.phtml');
			parent::_construct();
		}

		public function GetPaymentInfo()
		{
			if (Mage::registry('customer_save_observer_executed'))
			{
				return $this; //this method has already been executed once in this request (see comment below)
			}

			$quote = $this->getQuote();
			$quoteId = $quote->getId();

			$paymentinfo = '<h3>' . Mage::getStoreConfig('payment/Ripple/Ripple_gateway') . '?dt=' . $quoteId . '</h3>';

			// Qr code
			$qr = base64_encode(file_get_contents('https://chart.googleapis.com/chart?chs=128x128&cht=qr&chl=' . $paymentinfo . '&chld=L|1&choe=UTF-8'));

			$paymentinfo .= '<br /><img src="data:image/png;base64,' . $qr . '" alt="" />';

			Mage::register('customer_save_observer_executed',true); 

			return $paymentinfo;
		}
	}
?>