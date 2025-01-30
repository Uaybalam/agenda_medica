<?php
/**
* @route:payment
*/
class Payment_Controller extends APP_User_Controller
{
	function __construct()
	{	
		parent::__construct();
		$this->validate_access(['manager','admin','billing','root']);
	}
	/**
	 * @route:{get}data
	 */
	function data()
	{
		$dataSet = $label_value = $cashData = $insuranceData = [];

		$option         = $this->input->get('option');
		$label          = $this->input->get('label');
		
		$cashQuery      = $this->_get_cash( $option, $label );
		$insuranceQuery = $this->_get_insurance( $option, $label );

		if( $option === 'DAY')
		{	
			$this->template->json( [
				'encounters_of_day' => array_merge( $cashQuery , $insuranceQuery ),
			], 'JSON_PRESERVE_ZERO_FRACTION');
		}
		else
		{
			

			foreach ($cashQuery as $value) {
				$dataSet[$value->created_at_filter]['cash']      = $value->total;
				$dataSet[$value->created_at_filter]['insurance'] = 0;
			}
			foreach ($insuranceQuery as $value) {
				if(!isset($dataSet[$value->created_at_filter]['cash']))
				{
					$dataSet[$value->created_at_filter]['cash'] = 0;
				}
				$dataSet[$value->created_at_filter]['insurance'] = $value->total;;
			}
			
			ksort($dataSet);

			foreach ($dataSet as $key => $value) {
				
				if( $option === 'YEAR' )
				{
					$label_value[] = date("F", strtotime($key.'/01'));
				}
				else if( $option === 'MONTH' )
				{
					$label_value[] = date("l d", strtotime($key));
				}
				else
				{
					$label_value[] = $key;
				}

				$cashData[]      = $value['cash'];
				$insuranceData[] = $value['insurance'];
			}

			$this->template->json( [
				'labels' => array_keys($dataSet),
				'label_value' => $label_value,
				'cashData' => $cashData,
				'insuranceData' => $insuranceData,
			], 'JSON_PRESERVE_ZERO_FRACTION');
		}
		
		
	}

	/**
	 * @route:{get}__avoid__
	 */
	function index()
	{
		$this->template
			->set_title('Payments chart')
			->body([
				'ng-app' => 'app_payment_chart',
				'ng-controller' => 'ctrl_payment_chart',
				'ng-init' => 'initialize()'
			])
			->modal('payment/modal.payment.by_encounter',[
				'title' => 'Encounters of day <span>{{ default.encounters_of_day }}<span>',
				'size' => 'modal-md'
			])
			->js('Chart.min','/assets/vendor/node_modules/chart.js/dist/')
			->js('payment/linechart')
			->render('payment/view.panel.payment.linechart' );
	}

	private function _get_cash( $option, $label )
	{
		$selectQueryCash[] 	= 'SUM(encounter_invoice.paid) as total';
		
		if( $option === 'ALL_YEARS' )
		{
			$selectQueryCash[] = "DATE_FORMAT(checked_out.created_at,'%Y') as created_at_filter";
		}
		else if( $option === 'DAY' )
		{
			$selectQueryCash[] = "'SELF PAY' as method";
			$selectQueryCash[] = "encounter.id as encounter_id";
			$selectQueryCash[] = "encounter.id as created_at_filter";
			$this->db->where(["DATE_FORMAT(checked_out.created_at,'%Y/%m/%d')" => $label]);
		}
		else if( $option === 'MONTH' )
		{
			$selectQueryCash[] = "DATE_FORMAT(checked_out.created_at,'%Y/%m/%d') as created_at_filter";
			$this->db->where(["DATE_FORMAT(checked_out.created_at,'%Y/%m')" => $label]);
		}
		else if( $option === 'YEAR' )
		{
			$selectQueryCash[] = "DATE_FORMAT(checked_out.created_at,'%Y/%m') as created_at_filter";
			$this->db->where(["DATE_FORMAT(checked_out.created_at,'%Y')" => $label]);
		}
		else
		{
			return [];
		}

		$this->db->select($selectQueryCash)
			->from('encounter')
			->join('checked_out', 'encounter.checked_out_id = checked_out.id')
			->join('encounter_invoice', 'encounter.id = encounter_invoice.encounter_id' )
			->group_by("created_at_filter")
			->order_by("created_at_filter ASC")
		;
		
		return $this->db->get()->result();
	}
	
	private function _get_insurance( $option , $label )
	{
		
		$selectQueryBilling[] 	= 'SUM(billing.total_paid) as total';
		
		if( $option === 'ALL_YEARS' )
		{
			$selectQueryBilling[] = "DATE_FORMAT(billing.create_at,'%Y') as created_at_filter";
		}
		else if( $option === 'DAY' )
		{
			$selectQueryBilling[] = "'INSURANCE' as method";
			$selectQueryBilling[] = "billing.encounter_id as encounter_id";
			$selectQueryBilling[] = "billing.encounter_id as created_at_filter";
			$this->db->where(["DATE_FORMAT(billing.create_at,'%Y/%m/%d')" => $label]);
		}
		else if( $option === 'MONTH' )
 		{
 			$selectQueryBilling[] = "DATE_FORMAT(billing.create_at,'%Y/%m/%d') as created_at_filter";
			$this->db->where(["DATE_FORMAT(billing.create_at,'%Y/%m')" => $label]);
		}
		else if( $option === 'YEAR' )
		{
			$selectQueryBilling[] = "DATE_FORMAT(billing.create_at,'%Y/%m') as created_at_filter";
			$this->db->where(["DATE_FORMAT(billing.create_at,'%Y')" => $label]);
		}
		else
		{
			return [];
		}

		$this->db->select($selectQueryBilling)
			->from('billing')
			->group_by("created_at_filter")
			->order_by("created_at_filter ASC")
		;

		return $this->db->get()->result();
	}
}