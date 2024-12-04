<?php
class Controllerextensiontimeslot extends Controller {
	private $error = array();

	public function index() {
	  $this->load->language('extension/timeslot');
		$this->document->setTitle($this->language->get('heading_title1'));
		$this->load->model('setting/setting');

		$url = '';

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		
			$this->model_setting_setting->editSetting('tmd_timeslot', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/timeslot', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}		 

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
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
		
		
		if (isset($this->request->post['tmd_timeslot_product_timer'])) {
			$products = $this->request->post['tmd_timeslot_product_timer'];
		} elseif($this->config->get('tmd_timeslot_product_timer')) {
			$products = $this->config->get('tmd_timeslot_product_timer');
		}else{
			$products=array();
		}

		$this->load->model('catalog/product');
		$data['products'] = array();		
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}
		
		
       if (isset($this->request->post['tmd_timeslot_category_timer'])) {
			$categorys = $this->request->post['tmd_timeslot_category_timer'];
		} elseif($this->config->get('tmd_timeslot_category_timer')) {
			$categorys = $this->config->get('tmd_timeslot_category_timer');
		}else{
			$categorys = array();
		}
	
		$this->load->model('catalog/category');
		$data['categorys_results'] = array();
			foreach ($categorys as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);
			if ($category_info) {
				$data['categorys_results'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']

				);
			}
		}

		if (isset($this->request->post['tmd_timeslot_title'])) {
			$data['tmd_timeslot_title'] = $this->request->post['tmd_timeslot_title'];
		} else {
			$data['tmd_timeslot_title'] = $this->config->get('tmd_timeslot_title');
		}

		if (isset($this->request->post['tmd_timeslot_availability'])) {
			$data['tmd_timeslot_availability'] = $this->request->post['tmd_timeslot_availability'];
		} else {
			$data['tmd_timeslot_availability'] = $this->config->get('tmd_timeslot_availability');
		}

		if (isset($this->request->post['tmd_timeslot_slot_no'])) {
		$timsslotInfos = $this->request->post['tmd_timeslot_slot_no'];
		} elseif($this->config->get('tmd_timeslot_slot_no')) {
		$timsslotInfos = $this->config->get('tmd_timeslot_slot_no');
		}else{
		$timsslotInfos=array();
		}
		
		$tmd_timeslot_slot_nos = array();
		foreach ($timsslotInfos as $language_id=>$result) {
		$tmd_timeslot_slot_nos[$language_id] = array('on' => $result['on']);
		}
		$data['tmd_timeslot_slot_no'] = $tmd_timeslot_slot_nos;
		 		
		if (isset($this->request->post['tmd_timeslot_slot_from'])) {
		$tmd_timeslot_slotfroms = $this->request->post['tmd_timeslot_slot_from'];
		} elseif($this->config->get('tmd_timeslot_slot_from')) {
		$tmd_timeslot_slotfroms = $this->config->get('tmd_timeslot_slot_from');
		}else{
		$tmd_timeslot_slotfroms=array();
		}
		
	
		
		$tmd_timeslotslotfroms = array();
		foreach ($tmd_timeslot_slotfroms as $language_id=>$result) {
		$tmd_timeslotslotfroms[$language_id] = array('from' => $result['from']);
		}
		$data['tmd_timeslot_slot_froms']=$tmd_timeslotslotfroms;

		
	
		if (isset($this->request->post['tmd_timeslot_slot_to'])) {
		$tmd_timeslotslottos = $this->request->post['tmd_timeslot_slot_to'];
		} elseif($this->config->get('tmd_timeslot_slot_to')) {
		$tmd_timeslotslottos = $this->config->get('tmd_timeslot_slot_to');
		}else{
		$tmd_timeslotslottos=array();
		}
		
		$tmd_timeslotslotto = array();
		foreach ($tmd_timeslotslottos as $language_id=>$result) {
		$tmd_timeslotslotto[$language_id] = array('to' => $result['to']);
		}
		$data['tmd_timeslot_slot_to']=$tmd_timeslotslotto;

		if (isset($this->request->post['tmd_timeslot_slot_timer'])) {
		$timesselecteds = $this->request->post['tmd_timeslot_slot_timer'];
		} elseif($this->config->get('tmd_timeslot_slot_timer')) {
		$timesselecteds = $this->config->get('tmd_timeslot_slot_timer');
		}else{
		$timesselecteds=array();
		}


		if (isset($this->request->post['tmd_timeslot_errormsgshow_status'])) {
			$data['tmd_timeslot_errormsgshow_status'] = $this->request->post['tmd_timeslot_errormsgshow_status'];
		} elseif (isset($this->request->post['tmd_timeslot_errormsgshow_status'])) {
			$data['tmd_timeslot_errormsgshow_status'] = $this->request->post['tmd_timeslot_errormsgshow_status'];
		} else {
			$data['tmd_timeslot_errormsgshow_status'] = $this->config->get('tmd_timeslot_errormsgshow_status');
		}


		
	    $data['timesslot_infos'] = array();
		foreach ($timesselecteds as $value_results) {
		 $timeslot_value_timedata=array();
		  foreach ($value_results['days'] as $language_id => $day) {
			$timeslot_value_timedata[$language_id]=array('timeslot_time'=>$day['timeslot_time']);
		  }
			$data['timesslot_infos'][] = array(
				'timeslot_value_timedata'  => $timeslot_value_timedata,
				'time_start'               => $value_results['time_start'],
				'time_end'                 => $value_results['time_end'],
			);
		}
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/timeslot', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/timeslot', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}
	

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/timeslot', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/timeslot', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/timeslot_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/featured')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		

		return !$this->error;
	}
}
