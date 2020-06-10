<?php
namespace App\Helpers;

class PaymentTermsHelper{
	
	private $list = null;

	public function __contruct(){

		$this->list = $this->list();
	}

	public function getTerms(){

		return $this->list();
	} 


	private function list(){

		return [ 
					['name'=> 'PIA', 'label'=> 'PIA', 'description' => 'Payment in advance' ], 
					['name'=> 'Net7', 'label'=> 'Net 7', 'description' => 'Payment seven days after invoice date' ], 
					['name'=> 'Net10', 'label'=> 'Net 10', 'description' => 'Payment ten days after invoice date' ], 
					['name'=> 'Net14', 'label'=> 'Net 14', 'description' => 'Payment 14 days after invoice date' ], 
					['name'=> 'Net30', 'label'=> 'Net 30', 'description' => 'Payment 30 days after invoice date' ], 
					['name'=> 'Net60', 'label'=> 'Net 60', 'description' => 'Payment 60 days after invoice date' ], 
					['name'=> 'Net90', 'label'=> 'Net 90', 'description' => 'Payment 90 days after invoice date' ], 
					['name'=> 'EOM', 'label'=> 'EOM', 'description' => 'End of month' ], 
					['name'=> '21MFI', 'label'=> '21 MFI', 'description' => '21st of the month following invoice date' ], 
					['name'=> '1per10Net30', 'label'=> '1 % 10 Net 30', 'description' => '1% discount if payment received within ten days otherwise payment 30 days after invoice date' ], 
					['name'=> 'COD', 'label'=> 'COD', 'description' => 'Cash on delivery' ], 
					['name'=> 'CashAccount', 'label'=> 'Cash Account', 'description' => 'Account conducted on a cash basis, noo credit' ], 
					['name'=> 'LetterOfCredit', 'label'=> 'Letter of credit', 'description' => 'A documentary credit confirmed by a bank, often used for export' ], 
					['name'=> 'BillOfExchange', 'label'=> 'Bill of exchange', 'description' => 'A promise to pay at a later date, usually supported by a bank' ], 
					['name'=> 'CND', 'label'=> 'CND', 'description' => 'Cash next delivery' ], 
					['name'=> 'CBS', 'label'=> 'CBS', 'description' => 'Cash before shipment' ], 
					['name'=> 'CIA', 'label'=> 'CIA', 'description' => 'Cash in advance' ], 
					['name'=> 'CWO', 'label'=> 'CWO', 'description' => 'Cash with order' ], 
					['name'=> '1MD', 'label'=> '1MD', "description' => 'Monthly credit payment of a full month's supply" ], 
					['name'=> '2MD', 'label'=> '2MD', 'description' => "Monthly credit payment of a full month's supply plus an extra calendar month" ], 
					['name'=> 'Contra', 'label'=> 'Contra', 'description' => 'Payment from the customer offset against the value of supplies purchased from the customer' ], 
					['name'=> 'StagePayment', 'label'=> 'Stage payment', 'description' => 'Payment of agreed amounts at stage' ],

				];

	}

	public function getTermByName($name){

		$terms = array_filter($this->list(), function($term, $k) use ($name) {
            return $term['name'] == $name;
        } , ARRAY_FILTER_USE_BOTH );

		if(count($terms) < 1) {
			return ['name' => '', 'label' => '', 'description' => ''];
		}
		$term = [];
		foreach ($terms as $value) {
			$term[] = $value;
		}
		return $term[0];
	}

}