<?php
namespace Opencart\Admin\Controller\Extension\Tmdavailabilitytimeslots\Module;
// Lib Include 
require_once(DIR_EXTENSION.'/tmdavailabilitytimeslots/system/library/tmd/system.php');
// Lib Include
use \Opencart\System\Helper as Helper;
class Timeslot extends \Opencart\System\Engine\Controller {

	private $error = [];

	public function index() {
		$this->load->language('extension/tmdavailabilitytimeslots/module/timeslot');
		
		$this->registry->set('tmd', new  \Tmdavailabilitytimeslots\System\Library\Tmd\System($this->registry));
		$keydata=array(
		'code'  =>'tmdkey_timeslot',
		'eid'   =>'Mzg5OTc=',
		'route' =>'extension/tmdavailabilitytimeslots/module/timeslot',
		);
		$timeslot=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		
		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdavailabilitytimeslots/module/timeslot', 'user_token=' . $this->session->data['user_token'])
		];

		 if(VERSION>='4.0.2.0'){
			
			$data['save'] = $this->url->link('extension/tmdavailabilitytimeslots/module/timeslot.save', 'user_token=' . $this->session->data['user_token']);
		}else{
			
			$data['save'] = $this->url->link('extension/tmdavailabilitytimeslots/module/timeslot|save', 'user_token=' . $this->session->data['user_token']);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		if (isset($this->request->post['module_timeslot_status'])) {
			$data['module_timeslot_status'] = $this->request->post['module_timeslot_status'];
		} else {
			$data['module_timeslot_status'] = $this->config->get('module_timeslot_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdavailabilitytimeslots/module/timeslot', $data));
	}


    public function save(): void {
		$this->load->language('extension/tmdavailabilitytimeslots/module/timeslot');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdavailabilitytimeslots/module/timeslot')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}
		  
	    $timeslot=$this->config->get('tmdkey_timeslot');
		if (empty(trim($timeslot))) {			
		$json['error'] ='Module will Work after add License key!';
		}

		
		
		if (!$json) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('module_timeslot', $this->request->post);
			
			$json['success'] = $this->language->get('text_success');
		   }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_timeslot',
			'eid'=>'Mzg5OTc=',
			'route'=>'extension/tmdavailabilitytimeslots/module/timeslot',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new  \Tmdavailabilitytimeslots\System\Library\Tmd\System($this->registry));
		
            $json=$this->tmd->matchkey($keydata);       
		}
    
	}
	
	public function install(): void {
	$this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');
	$this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->install();
	$this->load->model('setting/event');
		
		// Fix permissions
	$this->load->model('user/user_group');
	$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdavailabilitytimeslots/module/timeslot');
	$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdavailabilitytimeslots/module/timeslot');


	$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdavailabilitytimeslots/tmd/timeslot');
	$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdavailabilitytimeslots/tmd/timeslot');
		
		//menu event
	   if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot.menu';
		}else{
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot|menu';
		}
		$eventrequest=[
					'code'			=>'tmd_moduletimeslotmenu',
					'description'	=>'TMD Availability timeslot menu',
					'trigger'		=>'admin/view/common/column_left/before',
					'action'		=>$eventaction,
					'status'		=>'1',
					'sort_order'	=>'1',
				];
				
		if(VERSION=='4.0.0.0'){
	    $this->model_setting_event->addEvent('tmd_moduletimeslotmenu', 'TMD Availability timeslot menu', 'admin/view/common/column_left/before','extension/tmdavailabilitytimeslots/module/timeslot|menu', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}


       //admin product event
	   if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot.producttimeslot';
		}else{
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot|producttimeslot';
		}
		$eventrequest=[
					'code'			=>'tmd_moduletimeslotproduct',
					'description'	=>'TMD Availability timeslot admin product',
					'trigger'		=>'admin/view/catalog/product_form/before',
					'action'		=>$eventaction,
					'status'		=>'1',
					'sort_order'	=>'1',
				];
				
		if(VERSION=='4.0.0.0'){
	    $this->model_setting_event->addEvent('tmd_moduletimeslotproduct', 'TMD Availability timeslot admin product', 'admin/view/catalog/product_form/before','extension/tmdavailabilitytimeslots/module/timeslot|producttimeslot', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		} 

          //admin product model add
		  if(VERSION>='4.1.0.0'){
			 $trigger='admin/model/catalog/product.addProduct/after';
		 }
		 else{
			  $trigger='admin/model/catalog/product/addProduct/after';
		 }
		
	      if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot.cproductmodeladd';
		  }else{
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot|cproductmodeladd';
		  }    
		$eventrequest=[
					'code'=>'tmd_timeslotadminproductmodeladd',
					'description'=>'TMD timeslot admin product model add',
					'trigger'=>$trigger,
					'action'=>$eventaction,
					'status'=>'1',
					'sort_order'=>'1',
				];
				
		if(VERSION=='4.0.0.0'){
		$this->model_setting_event->addEvent('tmd_timeslotadminproductmodeladd', 'TMD timeslot admin product model add', 'admin/model/catalog/product|addProduct/before', 'extension/tmdavailabilitytimeslots/module/timeslot|cproductmodeladd', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
	     }


	      //admin product model edit
		  if(VERSION>='4.1.0.0'){
			 $trigger='admin/model/catalog/product.editProduct/after';
		 }
		 else{
			  $trigger='admin/model/catalog/product/editProduct/after';
		 }
		
	      if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot.cproductmodeledit';
		  }else{
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot|cproductmodeledit';
		  }    
		$eventrequest=[
					'code'=>'tmd_timeslotadminproductmodeledit',
					'description'=>'TMD timeslot admin product model edit',
					'trigger'=>$trigger,
					'action'=>$eventaction,
					'status'=>'1',
					'sort_order'=>'1',
				];
				
		if(VERSION=='4.0.0.0'){
		$this->model_setting_event->addEvent('tmd_timeslotadminproductmodeledit', 'TMD timeslot admin product model edit', 'admin/model/catalog/product|editProduct/before', 'extension/tmdavailabilitytimeslots/module/timeslot|cproductmodeledit', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
	     }



		//admin sale/order_info event
	   if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot.orderinfotimeslot';
		}else{
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot|orderinfotimeslot';
		}
		$eventrequest=[
					'code'			=>'tmd_moduletimeslotorderinfo',
					'description'	=>'TMD Availability timeslot admin order info',
					'trigger'		=>'admin/view/sale/order_info/before',
					'action'		=>$eventaction,
					'status'		=>'1',
					'sort_order'	=>'1',
				];
				
		if(VERSION=='4.0.0.0'){
	    $this->model_setting_event->addEvent('tmd_moduletimeslotorderinfo', 'TMD Availability timeslot admin order info', 'admin/view/sale/order_info/before','extension/tmdavailabilitytimeslots/module/timeslot|orderinfotimeslot', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}	

		//admin sale/order_history event
	   if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot.orderhistorytimeslot';
		}else{
			$eventaction='extension/tmdavailabilitytimeslots/module/timeslot|orderhistorytimeslot';
		}
		$eventrequest=[
					'code'			=>'tmd_moduletimeslotorderhistory',
					'description'	=>'TMD Availability timeslot admin order history',
					'trigger'		=>'admin/view/sale/order_history/before',
					'action'		=>$eventaction,
					'status'		=>'1',
					'sort_order'	=>'1',
				];
				
		if(VERSION=='4.0.0.0'){
	    $this->model_setting_event->addEvent('tmd_moduletimeslotorderhistory', 'TMD Availability timeslot admin order history', 'admin/view/sale/order_history/before','extension/tmdavailabilitytimeslots/module/timeslot|orderhistorytimeslot', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

      	// Front Product events
		if(VERSION>='4.0.2.0'){
            $eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot.productcontroller';
        }else{
            $eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot|productcontroller';
        }
        $eventrequest=[
                    'code'			=>'tmd_frontproducttimeslot',
                    'description'	=>'TMD Front Product Time slot',
                    'trigger'		=>'catalog/view/product/product/before',
                    'action'		=>$eventaction,
                    'status'		=>'1',
                    'sort_order'	=>'1',
                ];
                
        if(VERSION=='4.0.0.0'){
        $this->model_setting_event->addEvent('tmd_frontproducttimeslot', 'TMD Front Product Time slot', 'catalog/view/product/product/before', 'extension/tmdavailabilitytimeslots/tmd/timeslot|productcontroller', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

        //Front catalog account order_info (twig)
        
		if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot.catalogaccount';
		}else{
			$eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot|catalogaccount';
		}
		$eventrequest=[
					'code'			=>'tmd_moduletimeslotcatalogaccount',
					'description'   =>'TMD timeslot catalog account order info',
					'trigger'	    =>'catalog/view/account/order_info/before',
					'action'		=>$eventaction,
					'status'		=>'1',
					'sort_order'	=>'1',
				];
				
		if(VERSION=='4.0.0.0'){
	    $this->model_setting_event->addEvent('tmd_moduletimeslotcatalogaccount', 'TMD timeslot catalog account order info', 'catalog/view/account/order_info/before','extension/tmdavailabilitytimeslots/tmd/timeslot|catalogaccount', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

		//Front add to cart from category and other pages
		if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot.alltwigerrorcode';
		}else{
			$eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot|alltwigerrorcode';
		}
		$eventrequest=[
					'code'			=>'tmd_timeslot_catalog_thumb',
					'description'   =>'TMD timeslot catalog product thumb code',
					'trigger'	    =>'catalog/view/product/thumb/before',
					'action'		=>$eventaction,
					'status'		=>'1',
					'sort_order'	=>'1',
				];
				
		if(VERSION=='4.0.0.0'){
	    $this->model_setting_event->addEvent('tmd_timeslot_catalog_thumb', 'TMD timeslot catalog product thumb code', 'catalog/view/product/thumb/before','extension/tmdavailabilitytimeslots/tmd/timeslot|alltwigerrorcode', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

       // Add startup to catalog
            $startup_data = [
                'code'        => 'timeslot',
                'description' => 'timeslot extension',
                'action'      => 'catalog/extension/tmdavailabilitytimeslots/startup/availabletimeslot',
                'status'      => 1,
                'sort_order'  => 2
            ];

         // Add startup for admin
		$this->load->model('setting/startup');
		$this->model_setting_startup->addStartup($startup_data);

       		
		//Front checkout order addHistory (model)
		if(VERSION >='4.0.2.0'){
			$eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot.addOrderHistorytimeslot';
		}else{
			$eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot|addOrderHistorytimeslot';
		}

		  if(VERSION>='4.1.0.0'){
			 $trigger='catalog/model/checkout/order.addHistory/before';
		 }
		 else{
			  $trigger='catalog/model/checkout/order/addHistory/before';
		 }
		$eventrequest=[
					'code'=>'tmd_timeslot_orderhistory_model',
					'description'	=>'TMD timeslot orderhistory',
					'trigger'	    =>$trigger,
					'action'		=>$eventaction,
					'status'	    =>'1',
					'sort_order'	=>'1',
				];
		if(VERSION=='4.0.0.0'){
			$this->model_setting_event->addEvent('tmd_timeslot_orderhistory_model', 'TMD timeslot orderhistory', 'catalog/model/checkout/order/addHistory/before','extension/tmdavailabilitytimeslots/tmd/timeslot|addOrderHistorytimeslot', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

	
        // Front Delete order events 
        if(VERSION >='4.0.2.0'){
			$eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot.deleteOrder';
		}else{
			$eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot|deleteOrder';
		}
		 if(VERSION>='4.1.0.0'){
			 $trigger='catalog/model/checkout/order.deleteOrder/after';
		 }else{
			  $trigger='catalog/model/checkout/order/deleteOrder/after';
		 }
		$eventrequest=[
					'code'        => 'tmd_timeslot_deletecheckoutorder',
					'description' => 'TMD timeslot Delete Checkout Order',
					'trigger'     => $trigger,
					'action'      => $eventaction,
					'status'      => '1',
					'sort_order'  => '1',
				];
				
		
		if(VERSION=='4.0.0.0'){
		$this->model_setting_event->addEvent('tmd_timeslot_deletecheckoutorder', 'TMD timeslot Delete Checkout Order', 'catalog/model/checkout/order/deleteOrder/after','extension/tmdavailabilitytimeslots/tmd/timeslot|deleteOrder', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

        //sale api sale order event
        if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot.apisaleorder';
		}else{
			$eventaction='extension/tmdavailabilitytimeslots/tmd/timeslot|apisaleorder';
		}

		 if(VERSION>='4.1.0.0'){
				$eventtrigger='catalog/controller/api/sale/order.load/after';
		 }else{
			  $eventtrigger='catalog/controller/api/sale/order/load/after';
		 }
		
		$eventrequest=[
					'code'         =>'tmd_timeslotapisaleorder',
					'description'  =>'TMD API Sale Order',
					'trigger'      => $eventtrigger,
					'action'       => $eventaction,
					'status'       =>'1',
					'sort_order'   =>'1',
				];
		
		if(VERSION=='4.0.0.0'){
	     $this->model_setting_event->addEvent('tmd_timeslotapisaleorder', 'TMD API Sale Order', 'catalog/controller/api/sale/order|load/after','extension/tmdavailabilitytimeslots/tmd/timeslot|apisaleorder', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}
		
	}

	public function uninstall(): void {
		$this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');
		$this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->uninstall();

		$this->load->model('setting/setting');
		$this->model_setting_event->deleteEventByCode('tmd_moduletimeslotmenu');
		$this->model_setting_event->deleteEventByCode('tmd_moduletimeslotproduct');
		$this->model_setting_event->deleteEventByCode('tmd_timeslotadminproductmodeladd');
		$this->model_setting_event->deleteEventByCode('tmd_timeslotadminproductmodeledit');
		$this->model_setting_event->deleteEventByCode('tmd_moduletimeslotorderinfo');
		$this->model_setting_event->deleteEventByCode('tmd_moduletimeslotorderhistory');
		$this->model_setting_event->deleteEventByCode('tmd_frontproducttimeslot');
        $this->model_setting_event->deleteEventByCode('tmd_moduletimeslotcatalogaccount');
        $this->model_setting_event->deleteEventByCode('timeslot');
		$this->model_setting_event->deleteEventByCode('tmd_timeslot_orderhistory_model');
        $this->model_setting_event->deleteEventByCode('tmd_timeslot_deletecheckoutorder');
        $this->model_setting_event->deleteEventByCode('tmd_timeslotapisaleorder');

		// Fix permissions
		$this->load->model('user/user_group');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdavailabilitytimeslots/module/timeslot');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdavailabilitytimeslots/module/timeslot');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdavailabilitytimeslots/tmd/timeslot');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdavailabilitytimeslots/tmd/timeslot');
			
		
	   }

	public function menu(string&$route, array&$args, mixed&$output):void {
	   $modulestatus=$this->config->get('module_timeslot_status');
		if(!empty($modulestatus)){
			$this->load->language('extension/tmdavailabilitytimeslots/module/timeslot');
			
			$timeslot = [];
		
			if ($this->user->hasPermission('access', 'extension/tmdavailabilitytimeslots/tmd/timeslot')) {		
				$timeslot[] = [
					'name'	   => $this->language->get('text_timeslot'),
					'href'     => $this->url->link('extension/tmdavailabilitytimeslots/tmd/timeslot', 'user_token=' . $this->session->data['user_token']),
					'children' => []	
				];					
			}	
				
			if ($timeslot) {					
				$args['menus'][] = [
					'id'       => 'menu-timeslot',
					'icon'	   => 'fas fa fa-clock fw', 
					'name'	   => $this->language->get('text_timeslot'),
					'href'     => '',
					'children' => $timeslot
				];		
			}
		}
	}
    
	public function orderinfotimeslot(string&$route, array&$args, mixed&$output):void {
	   $modulestatus=$this->config->get('module_timeslot_status');
		if(!empty($modulestatus)){

          $this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');
          $this->load->language('extension/tmdavailabilitytimeslots/tmd/timeslotevents');
			  $args['VERSION']=VERSION;
			  $args['timeslotProducts']=[];
			  $resultsTimeslots = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getProducttimeslosst($this->request->get['order_id']);
			  foreach($resultsTimeslots as $resultsTimeslot){

				 $order_timeslots = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getOrderTimeslot($resultsTimeslot['order_product_id']);

				 $ordertimeslot=[];

			
				 foreach($order_timeslots as $resultsTime){
					$ordertimeslot[]=[
					  'ordertime_id'     =>$resultsTime['ordertime_id'],
					  'tmdday'           =>$resultsTime['tmdday']
   				    ];
			    }
							
				//// product info data get start
				$timeslotProductResults = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getProducttimeslott($resultsTimeslot['product_id']);

				 if(!empty($timeslotProductResults)){
					 $name        =$timeslotProductResults['name'];
					 $product_id  =$timeslotProductResults['product_id'];
					 
				 }
				//// product info data get end 
				 $args['timeslotProducts'][]=[
				  'ordertimeslot'        =>$ordertimeslot,
				   'name'                =>$name,
				   'ordertime_id'        =>$resultsTimeslot['ordertime_id'],
				   'product_id'          =>$product_id
				 
				 ];
				 
			 }

			 
		 
			 
			
		     /// timeslot end
		        $tmd_timeslot_slot_no  = $this->config->get('tmd_timeslot_slot_to');
				$language_id           = $this->config->get('config_language_id');
				$slot_to               = $tmd_timeslot_slot_no[$language_id]['to'];
				
			    $args['productInfos']  =  [];

				$timesloprodcutInfos   = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getOrdertimeslotProductdata($this->request->get['order_id']);
				foreach($timesloprodcutInfos as $timesloprodcutInfo){
					$resultsProductTimeslots = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getOrdertimeslotCustomer($timesloprodcutInfo['product_id'],$this->request->get['order_id']);
					$timeslotdata=[];
					foreach($resultsProductTimeslots as $timeslots){
					   $timeslotdata[]= [
						  'tmdday' => $timeslots['tmdday'].' '.$timeslots['tmdstart'].' '.$slot_to.' ' .$timeslots['endtmd']

					   ];
				   }
					$timeslotProductResults = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getProducttimeslott($timesloprodcutInfo['product_id']);
					 if(!empty($timeslotProductResults)){
						 $name        = $timeslotProductResults['name'];
						 $product_id  = $timeslotProductResults['product_id'];
						 
					 }
					$args['productInfos'][]=[
						  'timeslotdata' => $timeslotdata,
						  'name'         => $name
				    ];
				}  
         
			
			$template_buffer =$this->getTemplateBuffer($route,$output); 
	        $find    = '<li class="nav-item"><a href="#tab-additional" data-bs-toggle="tab" class="nav-link">{{ tab_additional }}</a></li>';
	        $replace = '<li class="nav-item"><a href="#tab-additional" data-bs-toggle="tab" class="nav-link">{{ tab_additional }}</a></li>'.'<li class="nav-item"><a href="#tab-timeslot" data-bs-toggle="tab" class="nav-link">{{ entry_timeslot }}</a></li>';
	        $output = str_replace($find, $replace, $template_buffer);


	        $template_buffer =$this->getTemplateBuffer($route,$output); 
	        $find = '<button type="submit" id="button-history" class="btn btn-primary"><i class="fa-solid fa-plus-circle"></i> {{ button_history_add }}</button>';
	        $replace= '<fieldset>
			<!----timeslot start ----->
				
				 <div class="row mb-3" id="timeslot">
                  <label class="col-sm-2 col-form-label" for="input-timeslot_option_id">{{ entry_timeslot }}</label>
                  <div class="col-sm-10">
				  <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-start">{{ entry_producttime }}</th>
                    <th class="text-start">{{ entry_timeslot }}</th>
                  </tr>
                </thead>
                <tbody>
                  {% set timesselected_row = 0 %}
				    {% for timeslotProduct in timeslotProducts %}
					<tr>
					<td>
					<label class="control-label" for="input-tmdday"> {{ timeslotProduct.name }}</label>
					</td>
					<td>
					<table class="table table-bordered">
					<tr>
						<td>
						<div class="row">
							<label class="col-sm-3 col-form-label" for="input-tmdday">{{ entry_day}}</label>
                           <div class="col-md-9">
						   	<input type="hidden" name="tmdtimeslot[{{timesselected_row}}][product_id]" value="{{ timeslotProduct.product_id }}"  />
						   	<input type="hidden" name="tmdtimeslot[{{timesselected_row}}][order_status_id]" value="" class="order_status_idtime"  />
							
							<input type="hidden" name="tmdtimeslot[{{timesselected_row}}][ordertime_id]" value="{{ timeslotProduct.ordertime_id }}" id="input-ordertime_id" />
							<select name="tmdtimeslot[{{timesselected_row}}][tmdday]" id="input-tmdday" class="form-select timeslotvalue" rel="{{timesselected_row}}">
							 <option value="0">{{ text_select }}</option>
					          {% for ordertime in timeslotProduct.ordertimeslot %}
							    <option value="{{ ordertime.ordertime_id }}">{{ ordertime.tmdday }}</option>
						     {% endfor %}
							</select>
							</div>
							</div>
						</td>
						<td>
							<div class="row">
						<label class="col-sm-4 col-form-label" for="input-tmdstart">{{ entry_start }}</label>
					      <div class="col-md-8">
							<select name="tmdtimeslot[{{timesselected_row}}][tmdstart]" id="input-tmdstart" class="form-select hour-input" maxlength="2">
							</select>
							</div>
							</div>
						</td>
						<td>
							<div class="row">
						<label class="col-sm-4 col-form-label" for="input-tmdstart">{{ entry_to }}</label>
					      <div class="col-md-8">
							<select name="tmdtimeslot[{{timesselected_row}}][endtmd]" id="input-endtmd" class="form-select hour-input" maxlength="2">
							</select>
							</div>
							</div>
						 </td>
					</tr>
					</table>
				  </td>
                </tr>
				{% set timesselected_row = timesselected_row + 1 %}
				{% endfor %}
				</tbody>
				</table>
				</div>
				</div>
				</fieldset>
                <div class="text-end">'.'<button type="submit" id="button-history" class="btn btn-primary"><i class="fa-solid fa-plus-circle"></i> {{ button_history_add }}</button>';
	        $output = str_replace($find, $replace, $template_buffer);

	        $template_buffer =$this->getTemplateBuffer($route,$output); 
	        $find    = '<div id="tab-additional" class="tab-pane">';
	        $replace = '<div class="tab-pane" id="tab-timeslot">
		   <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-start">{{ entry_producttime }}</th>
                    <th class="text-start">{{ entry_timeslot }}</th>
                  </tr>
                </thead>
                <tbody>
                {% for timeslotproduct in productInfos %}
                <tr>
                  <td>{{ timeslotproduct.name }}</td>
					<td>
						<table class="table table-bordered">
							{% set i=1 %}
							{% for timeslot in timeslotproduct.timeslotdata %}
							<tr {% if i==1 %}style="background:green;color:#FFF" {% endif %}>
								<td>{{ timeslot.tmdday }}</td>
							</tr>
							{% set i =i+1 %}
							{% endfor %}
						</table>
					</td>
					  <tr>
                {% endfor %}
                  </tbody>
              </table>
            </div>		   
            </div>'.'<div id="tab-additional" class="tab-pane">';
	        $output = str_replace($find, $replace, $template_buffer);

	        $template_buffer = $this->getTemplateBuffer($route,$output); 
	        if(VERSION>='4.0.2.0'){
	        $find    = "$('#form-history').on('submit', function(e) {";
	        $replace = "function addOrderTimeslot(){
				$.ajax({
					url:'index.php?route=extension/tmdavailabilitytimeslots/tmd/timeslot.timeslotinfo&user_token={{ user_token }}&order_id={{ order_id }}',
					type:'post',
					dataType:'json',
					data:$('#timeslot select,#timeslot hidden,#timeslot input'),
					success: function(json) {
					if (json['success']) {
					}
					},
					error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + ".'"\r\n"'." + xhr.statusText + ".'"\r\n"'." + xhr.responseText);
					}
				});
			};
        $(document).on('change', '.timeslotvalue', function(){
			var ordertime_id  = this.value; 
			var rel = $(this).attr('rel'); 
			var orde_statusid = $('select[name=".'"order_status_id"'."]').val();
			$.ajax({
			url:'index.php?route=extension/tmdavailabilitytimeslots/tmd/timeslot.timeslotget&user_token={{ user_token }}&ordertime_id='+ordertime_id,
			dataType: 'json',
				beforeSend: function() {
					
			},
			complete: function() {
				
			},
			success: function(json) {
			html = '<option value=".'""'.">{{ text_select }}</option>';
			if (json['timeslotloop'] && json['timeslotloop'] != '') {
			for (i = 0; i < json['timeslotloop'].length; i++) {
				html += '<option value="."' + json['timeslotloop'][i] + '"."';
				html += '>' + json['timeslotloop'][i] + '</option>';
			}
			}
			$('select[name=\'tmdtimeslot['+rel+'][tmdstart]\']').html(html);
			$('select[name=\'tmdtimeslot['+rel+'][endtmd]\']').html(html);
			$('.order_status_idtime').val(orde_statusid);

			}
			});
            });"."$('#form-history').on('submit', function(e) {";
              }else{
	         $find    = "$('#form-history').on('submit', function (e) {";
             $replace = "function addOrderTimeslot(){
				$.ajax({
					url:'index.php?route=extension/tmdavailabilitytimeslots/tmd/timeslot|timeslotinfo&user_token={{ user_token }}&order_id={{ order_id }}',
					type:'post',
					dataType:'json',
					data:$('#timeslot select,#timeslot hidden,#timeslot input'),
					success: function(json) {
					if (json['success']) {
					}
					},
					error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + ".'"\r\n"'." + xhr.statusText + ".'"\r\n"'." + xhr.responseText);
					}
				});
			};
        $(document).on('change', '.timeslotvalue', function(){
			var ordertime_id  = this.value; 
			var rel = $(this).attr('rel'); 
			var orde_statusid = $('select[name=".'"order_status_id"'."]').val();
			$.ajax({
			url:'index.php?route=extension/tmdavailabilitytimeslots/tmd/timeslot|timeslotget&user_token={{ user_token }}&ordertime_id='+ordertime_id,
			dataType: 'json',
				beforeSend: function() {
					
			},
			complete: function() {
				
			},
			success: function(json) {
			html = '<option value=".'""'.">{{ text_select }}</option>';
			if (json['timeslotloop'] && json['timeslotloop'] != '') {
			for (i = 0; i < json['timeslotloop'].length; i++) {
				html += '<option value="."' + json['timeslotloop'][i] + '"."';
				html += '>' + json['timeslotloop'][i] + '</option>';
			}
			}
			$('select[name=\'tmdtimeslot['+rel+'][tmdstart]\']').html(html);
			$('select[name=\'tmdtimeslot['+rel+'][endtmd]\']').html(html);
			$('.order_status_idtime').val(orde_statusid);

			}
			});
            });"."$('#form-history').on('submit', function (e) {";

	        }
	        $output = str_replace($find, $replace, $template_buffer);




            $template_buffer =$this->getTemplateBuffer($route,$output); 

            if(VERSION>='4.0.2.0'){
	        $find     = "$('#form-history').on('submit', function(e) {";
	        $replace  = "$('#form-history').on('submit', function(e) {".'addOrderTimeslot();';
	         }else{
            $find     = "$('#form-history').on('submit', function (e) {";
	        $replace  = "$('#form-history').on('submit', function (e) {".'addOrderTimeslot();';
	         }

	        $output   = str_replace($find, $replace, $template_buffer);


		}
	} 

	public function orderhistorytimeslot(string&$route, array&$args, mixed&$output):void {
	   $modulestatus=$this->config->get('module_timeslot_status');
		if(!empty($modulestatus)){
            $this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');
            $this->load->language('extension/tmdavailabilitytimeslots/tmd/timeslotevents');


            $template_buffer = $this->getTemplateBuffer($route,$output); 
	        $find     = '<td class="text-start">{{ column_comment }}</td>';
	        $replace  = '<td class="text-start">{{ column_comment }}</td>'.'<td class="text-left">{{ entry_timeslot }}</td>';
	        $output   = str_replace($find, $replace, $template_buffer);


	        $template_buffer = $this->getTemplateBuffer($route,$output); 
	        $find     = '<td class="text-start">{{ history.comment }}</td>';
	        $replace  = '<td class="text-start">{{ history.comment }}</td>'.'<td class="text-left">
				{% for timeslotstart in history.timeslotstarts %}
				</br>
				{{ timeslotstart.tmdday }}
				{% endfor %}
				</td>';
	        $output   = str_replace($find, $replace, $template_buffer);

		}
	}


	public function producttimeslot(string&$route, array&$args, mixed&$output):void {
	   $modulestatus=$this->config->get('module_timeslot_status');
		if(!empty($modulestatus)){
            $this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');
            $this->load->language('extension/tmdavailabilitytimeslots/tmd/timeslotevents');
            	/* time slot start */
				if(!empty($this->request->get['product_id'])){
				    $co_product_id=$this->request->get['product_id'];
				}else{
				    $co_product_id='';
				}
				
				
				$product_statu = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getProducttimeslotstart($co_product_id);
				if (isset($this->request->post['time_status'])) {
				$args['time_status'] = $this->request->post['time_status'];
				} elseif (!empty($product_statu)) {
				  $args['time_status'] = $product_statu['time_status'];
				} else {
				   $args['time_status'] = '';
				}

				if (isset($this->request->post['timeslot'])) {
				$timesselecteds = $this->request->post['timeslot'];
				} elseif (isset($this->request->get['product_id'])) {
				$timesselecteds = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getProductTimeslotValue($co_product_id);
				} else {
				$timesselecteds = [];
				}


				$args['timeslotresults'] = [];
				foreach ($timesselecteds as $value_results) {
				$args['timeslotresults'][] = [
				'time_id'                       => $value_results['time_id'],
				'timeslot_value_timedata'       => $value_results['timeslot_value_timedata'],
				'time_start'                    => $value_results['time_start'],
				'time_end'                      => $value_results['time_end'],
				'sort_order'                    => $value_results['sort_order'],
				];
				}
				/* time slot end  */

            $template_buffer = $this->getTemplateBuffer($route,$output); 
	        $find     = '<li class="nav-item"><a href="#tab-design" data-bs-toggle="tab" class="nav-link">{{ tab_design }}</a></li>';
	        $replace  = '<li class="nav-item"><a href="#tab-design" data-bs-toggle="tab" class="nav-link">{{ tab_design }}</a></li>'.'<li class="nav-item"><a href="#tab-timesselected" data-bs-toggle="tab" class="nav-link"> {{ entry_timeslot }} </a></li>';
	        $output   = str_replace($find, $replace, $template_buffer); 

	        $template_buffer = $this->getTemplateBuffer($route,$output); 
	        $find     = '<div id="tab-design" class="tab-pane">';
	        $replace  = '<div class="tab-pane" id="tab-timesselected">
		         <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="input-status">{{ entry_status }}</label>
                <div class="col-sm-10">
					<select name="time_status" id="input-status" class="form-select">
					{% if time_status %}
					<option value="1" selected="selected">{{ text_enabled }}</option>
					<option value="0">{{ text_disabled }}</option>
					{% else %}
					<option value="1">{{ text_enabled }}</option>
					<option value="0" selected="selected">{{ text_disabled }}</option>
					{% endif %}
					</select>
                </div>
              </div>
		  
			 <div class="table-responsive">
			<table id="timerslottable" class="table table-striped table-bordered table-hover">
			<thead>
			<tr>
			  <th class="text-left">{{ entry_day }}</th>
			  <th class="text-left">{{ entry_start }}</th>
			  <th class="text-left">{{ entry_end }}</th>
			  <th class="text-left"> {{ entry_sort_order }} </th>
			  <th class="text-left"> {{ text_acion }} </th>
			</tr>
			</thead>
			<tbody>
			 {% set timesselected_row = 0 %}
				{% for timesslot_info in timeslotresults %}
				<tr id="timerslot-row{{ timesselected_row }}">
				  <td class="text-left">
					{% for language in languages %}
					<div class="input-group"> <span class="input-group-text"><img src="{{ language.image }}" title="{{ language.name }}" /></span>
					  <input type="text" name="timeslot[{{ timesselected_row }}][days][{{ language.language_id }}][timeslot_time]" value="{{ timesslot_info.timeslot_value_timedata[language.language_id] ? timesslot_info.timeslot_value_timedata[language.language_id].timeslot_time }}" placeholder="Day" class="form-control" />
					</div>
					{% endfor %}</td>
					<td class="text-left"><div class="input-group"><input type="text" name="timeslot[{{ timesselected_row }}][time_start]" value="{{ timesslot_info.time_start }}" placeholder="{{ entry_start }}" data-date-format="HH:mm" id="input-value{{ timesselected_row }}" class="form-control hour-input" maxlength="2" /></div></td>
					<td class="text-left"><div class="input-group"><input type="text" name="timeslot[{{ timesselected_row }}][time_end]" value="{{ timesslot_info.time_end }}" placeholder="{{ entry_end }}" data-date-format="HH:mm" id="input-start{{ timesselected_row }}" class="form-control hour-input" maxlength="2"/></div></td>
		            <td class="text-left"><div class="input-group"><input type="text" name="timeslot[{{ timesselected_row }}][sort_order]" value="{{ timesslot_info.sort_order }}" placeholder="{{ entry_sort_order }}"  id="input-sort{{ timesselected_row }}" class="form-control" /></div></td>
					
					<td class="text-left"><button onclick="$('."'#timerslot-row{{ timesselected_row }}').remove();".'" data-toggle="tooltip" title="{{ button_remove }}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>

				</tr>
				{% set timesselected_row = timesselected_row + 1 %}
				{% endfor %}
			</tbody>
			<tfoot>
			<tr>
			  <td colspan="4"></td>
			  <td class="text-left"><button type="button" onclick="Addtmd_timeslot_category_timer();" data-toggle="tooltip" title="{{ button_time_add }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
			</tr>
			</tfoot>
			</table>
			</div>

		   </div>'.'<div id="tab-design" class="tab-pane">';
	        $output   = str_replace($find, $replace, $template_buffer);

             $template_buffer = $this->getTemplateBuffer($route,$output); 
	        $find     = '{{ footer }}';
	        $replace  = '<script type="text/javascript"><!--
	        var timesselected_row = {{ timesselected_row }};
				function Addtmd_timeslot_category_timer() {
				html = '."'<tr id=".'"timerslot-row'."' + timesselected_row + '".'">'."';
				html += ' <td class=".'"text-left">'."';
				{% for language in languages %}
				html += '<div class=".'"input-group"><span class="input-group-text"><img src="{{ language.image }}" title="{{ language.name }}" /></span><input name="timeslot['."' + timesselected_row + '][days][{{ language.language_id }}][timeslot_time]".'" rows="5" placeholder="{{entry_day}}" class="form-control"></div>'."';
				{% endfor %}
				html += '  </td>';		
				html += '<td class=".'"text-left"><div class="input-group"><input type="text" name="timeslot['."' + timesselected_row + '][time_start]".'" value="" placeholder="{{ entry_start }}" data-date-format="HH:mm" id="input-value'."' + timesselected_row + '".'" class="form-control hour-input" maxlength="2" /></div></td>'."';
				html += '<td class=".'"text-left"><div class="input-group"><input type="text" name="timeslot['."' + timesselected_row + '][time_end]".'" value="" placeholder="{{ entry_end }}" data-date-format="HH:mm" id="input-start'."' + timesselected_row + '".'" class="form-control hour-input" maxlength="2" /></div></td>'."';
				html += '<td class=".'"text-left"><div class="input-group"><input type="text" name="timeslot['."' + timesselected_row + '][sort_order]".'" value="" placeholder="{{ entry_sort_order }}"  id="input-sort'."' + timesselected_row + '".'" class="form-control" /></div></td>'."';
				html += '<td class=".'"text-left"><button type="button" onclick="$('."\'#timerslot-row' + timesselected_row + '\').remove();".'" data-toggle="tooltip" title="{{ button_remove }}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>'."';

				html += '</tr>';
				$('#timerslottable tbody').append(html);
				timesselected_row++;
				}</script>

				<script src='".'https://code.jquery.com/jquery-3.6.0.min.js'."'></script>
				<script>
				$(document).on('keyup', '.hour-input', function() {
				    let value = $(this).val();
				    // Allow only numeric values
				    value = value.replace(/[^0-9]/g, '');

				    // Restrict the value to a maximum of 24
				    if (parseInt(value, 10) > 24) {
				        value = '24';
				    } else if (value.length > 2) {
				        value = value.slice(0, 2); // Limit to 2 digits
				    }

				    // Update the input field with the validated value
				    $(this).val(value);
				});
				</script>".'{{ footer }}';
	        $output   = str_replace($find, $replace, $template_buffer); 
		}
	}
	

public function cproductmodeladd(string &$route, array &$args, mixed &$output): void {	
    $modulestatus=$this->config->get('module_timeslot_status');
    if(!empty($modulestatus)){
      
    	if (isset($args[0]['time_status'])) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot SET product_id = '" . (int)$output . "',time_status = '" .(int)$args[0]['time_status'] . "'");
			}

			if (isset($args[0]['timeslot'])) {
			foreach ($args[0]['timeslot'] as $timess) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_timesday SET product_id = '" . (int)$output . "',time_start = '" . $this->db->escape($timess['time_start']) . "',time_end = '" . $this->db->escape($timess['time_end']) . "',sort_order = '" . (int)$timess['sort_order'] . "'");
				$time_id = $this->db->getLastId();
				 foreach ($timess['days'] as $language_id => $timeslot_value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_timesvalue SET time_id = '" . (int)$time_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$output . "', timeslot_time = '" . $this->db->escape($timeslot_value['timeslot_time']) . "'");
				}
			}
			}

    }
 }
public function cproductmodeledit(string &$route, array &$args, mixed &$output): void {	
    $modulestatus=$this->config->get('module_timeslot_status');
    if(!empty($modulestatus)){

            if(!empty($args[0])){
               $product_id = $args[0];
            }else{
               $product_id =0;
            }
        
    	/* time slot start */
			$timeslot_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot WHERE product_id = '" . $args[0] . "'");
			if(!empty($timeslot_query->row)){
				$this->db->query("UPDATE " . DB_PREFIX . "timeslot SET  product_id = '" . (int)$product_id . "',time_status = '" .(int)$args[1]['time_status'] . "' WHERE product_id = '" . (int)$product_id . "'");
			}else{
				$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot SET product_id = '" . (int)$product_id . "',time_status = '" .(int)$args[1]['time_status'] . "'");
			}
		
	    $this->db->query("DELETE FROM " . DB_PREFIX . "timeslot_timesday WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "timeslot_timesvalue WHERE product_id = '" . (int)$product_id . "'");
		if (isset( $args[1]['timeslot'])) {
		foreach ( $args[1]['timeslot'] as $timess) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_timesday SET product_id = '" . (int)$product_id . "',time_start = '" . $this->db->escape($timess['time_start']) . "',time_end = '" . $this->db->escape($timess['time_end']) . "',sort_order = '" . (int)$timess['sort_order'] . "'");
			$time_id = $this->db->getLastId();
			 foreach ($timess['days'] as $language_id => $timeslot_value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_timesvalue SET time_id = '" . (int)$time_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$product_id . "',timeslot_time = '" . $this->db->escape($timeslot_value['timeslot_time']) . "'");
			}
		}
		}
    }
}

	protected function getTemplateBuffer($route, $event_template_buffer) {
		// if there already is a modified template from view/*/before events use that one
		if ($event_template_buffer) {
			return $event_template_buffer;
		}

		// load the template file (possibly modified by ocmod and vqmod) into a string buffer
		
			if ($this->config->get('config_theme') == 'default') {
				$theme = $this->config->get('theme_default_directory');
			} else {
				$theme = $this->config->get('config_theme');
			}
			  $dir_template = DIR_TEMPLATE ;
	
	  $template_file = $dir_template . $route . '.twig';
		if (file_exists( $template_file ) && is_file( $template_file )) {
			
			return file_get_contents( $template_file );
		}
		if ($this->isAdmin()) {
			trigger_error("Cannot find template file for route '$route'");
			exit;
		}
		
		$dir_template = DIR_TEMPLATE . 'default/template/';
		$template_file = $dir_template . $route . '.twig';
		if (file_exists( $template_file ) && is_file( $template_file )) {
			
			return file_get_contents( $template_file );
		}
		trigger_error("Cannot find template file for route '$route'");
		exit;
	}


}