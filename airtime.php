<?php
  private function PerformAirtime($network_provider,$phone,$amount){
			/* Function to perform Airtime transaction
			*/

			$client = new nusoap_client('https://arizonaadmin.mobifinng.com/WebService/iTopUp/reseller_itopup.server.php?wsdl', 'wsdl');
			
			$LoginID= '17484861';
			$PROTECTED_KEY = '14348223';
			$RequestId = '2987'; #Unique random number

			$rand = date("mdsh");
			$rand .= mt_rand(1111,9999);
			$rand = substr($rand,0,12);
			$RequestId = $rand;

			#define the networks array along with their batch Id's
			$networks = array('mtn' => 13, 'etisalat'=> 2, 'airtel'=>1, 'visafone'=>3, 'glo'=>6);
			$BatchId = $networks[$network_provider];
			$SystemServiceID = '2';

			$ReferalNumber = $phone;
			#$FromANI = '<any number you choose for identification>';
			$FromANI = $rand;
			$Amount = (string)$amount*100; #This converts say our 200 naira to 20000..API requirements

			$Email = "";
			$str= $LoginID.'|'.$RequestId.'|'.$BatchId.'|'.$SystemServiceID.'|'.$ReferalNumber.'|'.$Amount.'|'.$FromANI.'|'.$Email.'|'.$PROTECTED_KEY;
			$checksum = md5(sha1($str));

			$origtext = array("LoginId"=>$LoginID,"RequestId" =>$RequestId,"BatchId" =>$BatchId,"SystemServiceID" =>$SystemServiceID,"ReferalNumber" => $ReferalNumber,"Amount" =>$Amount, "FromANI" => $FromANI, "Email"=>$Email,"Checksum" => $checksum);

			$result = $client->call('FlexiRecharge', array($origtext), 'http://soapinterop.org/xsd', '', false, true);
			
			if(($client->fault))
			{
				return false;
			}
			else
			{
				$err = $client->getError();
				if ($err)
				{
					return false;
				}
				else 
				{
					return true;
					/*work with the result. array*/
				}
			}
		}
    
    ?>
