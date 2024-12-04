<?php
namespace Opencart\Admin\Controller\Extension\Tmdavailabilitytimeslots\Tmd;
use \Opencart\System\Helper as Helper;
class Timeslot extends \Opencart\System\Engine\Controller {
	
	private $error = [];

	public function index() {
	  	$this->load->language('extension/tmdavailabilitytimeslots/tmd/timeslot');
			
		$this->document->setTitle($this->language->get('heading_title1'));
		
		$this->load->model('setting/setting');
		
		$url='';
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		
		if (isset($this->request->post['tmd_timeslot_status'])) {
			$data['tmd_timeslot_status'] = $this->request->post['tmd_timeslot_status'];
		} else {
			$data['tmd_timeslot_status'] = $this->config->get('tmd_timeslot_status');
		}


        // new code

		if (isset($this->request->post['tmd_timeslot_errormsgshow_status'])) {
			$data['tmd_timeslot_errormsgshow_status'] = $this->request->post['tmd_timeslot_errormsgshow_status'];
		} else {
			$data['tmd_timeslot_errormsgshow_status'] = $this->config->get('tmd_timeslot_errormsgshow_status');
		}

        // new code
		
		if (isset($this->request->post['tmd_timeslot_product_timer'])) {
			$products = $this->request->post['tmd_timeslot_product_timer'];
		} elseif($this->config->get('tmd_timeslot_product_timer')) {
			$products = $this->config->get('tmd_timeslot_product_timer');
		}else{
			$products=[];
		}

		$this->load->model('catalog/product');
		$data['products'] = [];		
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['products'][] = [
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				];
			}
		}
		
		
       if (isset($this->request->post['tmd_timeslot_category_timer'])) {
			$categorys = $this->request->post['tmd_timeslot_category_timer'];
		} elseif($this->config->get('tmd_timeslot_category_timer')) {
			$categorys = $this->config->get('tmd_timeslot_category_timer');
		}else{
			$categorys=[];
		}
	
		$this->load->model('catalog/category');
	
		$data['categorys_results'] = [];
			foreach ($categorys as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);
			if ($category_info) {
				$data['categorys_results'][] = [
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']

				];
			}
		}
		
		
		if (isset($this->request->post['tmd_timeslot_slot_no'])) {
		$timsslotInfos = $this->request->post['tmd_timeslot_slot_no'];
		} elseif($this->config->get('tmd_timeslot_slot_no')) {
		$timsslotInfos = $this->config->get('tmd_timeslot_slot_no');
		}else{
		$timsslotInfos=[];
		}
		
		$tmd_timeslot_slot_nos = [];
		foreach ($timsslotInfos as $language_id=>$result) {
		$tmd_timeslot_slot_nos[$language_id] = array('on' => $result['on']);
		}
		$data['tmd_timeslot_slot_no']=$tmd_timeslot_slot_nos;
		 		
		$data['tmd_timeslot_title'] = $this->config->get('tmd_timeslot_title');
		$data['tmd_timeslot_availability'] = $this->config->get('tmd_timeslot_availability');
		
		
		if (isset($this->request->post['tmd_timeslot_slot_from'])) {
		$tmd_timeslot_slotfroms = $this->request->post['tmd_timeslot_slot_from'];
		} elseif($this->config->get('tmd_timeslot_slot_from')) {
		$tmd_timeslot_slotfroms = $this->config->get('tmd_timeslot_slot_from');
		}else{
		$tmd_timeslot_slotfroms=[];
		}
		
	
		
		$tmd_timeslotslotfroms = [];
		foreach ($tmd_timeslot_slotfroms as $language_id=>$result) {
		$tmd_timeslotslotfroms[$language_id] = array('from' => $result['from']);
		}
		$data['tmd_timeslot_slot_froms']=$tmd_timeslotslotfroms;

		
		
		
		
		if (isset($this->request->post['tmd_timeslot_slot_to'])) {
		$tmd_timeslotslottos = $this->request->post['tmd_timeslot_slot_to'];
		} elseif($this->config->get('tmd_timeslot_slot_to')) {
		$tmd_timeslotslottos = $this->config->get('tmd_timeslot_slot_to');
		}else{
		$tmd_timeslotslottos=[];
		}
		
		
		$tmd_timeslotslotto = [];
		foreach ($tmd_timeslotslottos as $language_id=>$result) {
		$tmd_timeslotslotto[$language_id] = array('to' => $result['to']);
		}
		$data['tmd_timeslot_slot_to']=$tmd_timeslotslotto;

		
		
		
		if (isset($this->request->post['tmd_timeslot_slot_timer'])) {
		$timesselecteds = $this->request->post['tmd_timeslot_slot_timer'];
		} elseif($this->config->get('tmd_timeslot_slot_timer')) {
		$timesselecteds = $this->config->get('tmd_timeslot_slot_timer');
		}else{
		$timesselecteds=[];
		}
		
			
	  $data['timesslot_infos'] = [];
		foreach ($timesselecteds as $value_results) {
		 $timeslot_value_timedata=[];
		  foreach ($value_results['days'] as $language_id => $day) {
			$timeslot_value_timedata[$language_id]=array('timeslot_time'=>$day['timeslot_time']);
		  
		  }
		  
			$data['timesslot_infos'][] = [
				'timeslot_value_timedata'       => $timeslot_value_timedata,
				'time_start'                     => $value_results['time_start'],
				'time_end'                      => $value_results['time_end'],
			];
		}
		
		
		
		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/tmdavailabilitytimeslots/tmd/timeslot', 'user_token=' . $this->session->data['user_token'])
			];
		} else {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/tmdavailabilitytimeslots/tmd/timeslot', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'])
			];
		}


		$data['save'] = $this->url->link('extension/tmdavailabilitytimeslots/tmd/timeslot|save', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];
		$data['VERSION']    = VERSION;

		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdavailabilitytimeslots/tmd/timeslot_form', $data));
	}
    
     public function save(): void {
		$this->load->language('extension/tmdavailabilitytimeslots/tmd/timeslot');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdavailabilitytimeslots/tmd/timeslot')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		  }

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}
		
		if (!$json) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('tmd_timeslot', $this->request->post);
			
			$json['success'] = $this->language->get('text_success');
		   }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	    }

        ///Events
      	public function timeslotinfo() {
				$this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');
				if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
				} else {
				$order_id = 0;
				}


				if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
				$this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->addOrderTimeslot($order_id,$this->request->post);
				$json['success'] = $this->language->get('text_success');

				}
				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($json));
				}
		  
		
		public function timeslotget() {
			$json = [];
			$this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');
			if (isset($this->request->get['ordertime_id'])) {
				$ordertime_id = $this->request->get['ordertime_id'];
			} else {
				$ordertime_id = 0;
			}
			
		  $timeslots = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getTimeslotdata($ordertime_id);
			$timeslots  = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getTimeslotdata($ordertime_id);
			    $starts = $timeslots['tmdstart'];
				$ends   = $timeslots['endtmd'];
				$json['timeslotloop']=[];
				$start = $starts.':00';
				$end   = $ends.':00';
				$tStart = strtotime($start);
				$tEnd = strtotime($end);
				$tNow = $tStart;
				if(!empty($tNow <= $tEnd)){
					while($tNow <= $tEnd){
				$json['timeslotloop'][]= date("H:i",$tNow)."\n";
					$tNow = strtotime('+30 minutes',$tNow);
				}
				}else{
					  while($tEnd <= $tNow){
					  $json['timeslotloop'][]= date("H:i",$tEnd)."\n";
					   $tEnd = strtotime('+30 minutes',$tEnd);
					}
				}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}


     }
