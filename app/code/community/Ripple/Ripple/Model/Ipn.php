<?php
	class Ripple_Ripple_Model_Ipn extends Mage_Core_Model_Abstract
	
{
		function _construct()
		{
			$this->_init('Ripple/ipn');
			return parent::_construct();
		}

		function GetStatusReceived($quoteId, $statuses)
		{
			$collection = $this->getCollection()->AddFilter('quote_id', $quoteId);
			foreach($collection as $i)
			{
				if (in_array($i->getStatus(), $statuses))
				{
					return true;
				}
			}
					
			return false;
		}
		
		function GetQuotePaid($quoteId)
		{
			$ch = curl_init();
			curl_setopt_array($ch, array(
			CURLOPT_URL => 'http://' . Mage::getStoreConfig('payment/Ripple/Ripple_host') . ':' . Mage::getStoreConfig('payment/Ripple/Ripple_port'),
			CURLOPT_POSTFIELDS => json_encode(array('method' => 'account_tx', 'params' => array(array('account' => Mage::getStoreConfig('payment/Ripple/Ripple_gateway'), 'ledger_index_min' => -1, 'ledger_index_max' => -1)))),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC));
			$response = json_decode(curl_exec($ch));
			curl_close($ch);

			foreach ($response->result->transactions as $transaction)
			{
				// Where to find ?dt=number ?
				if (intval($transaction->tx->DestinationTag) == $quoteId)
				{
					return true;
				}
			}

			return $this->GetStatusReceived($quoteId, array('1'));
		}
	}
?>
