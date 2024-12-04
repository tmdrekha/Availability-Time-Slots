<?php
namespace Opencart\Catalog\Controller\Extension\Tmdavailabilitytimeslots\Tmd;
use \Opencart\System\Helper as Helper;
class Timeslot extends \Opencart\System\Engine\Controller {
	public function index() {
		 $data['timeslots'] = [];
		 
		 $data['tmdtimeslot_status'] =$this->config->get('tmd_timeslot_status');

		$this->load->language('extension/tmdavailabilitytimeslots/tmd/timeslot');
        $this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');

			if (isset($this->request->get['product_id'])) {
				$product_id = (int)$this->request->get['product_id'];
			} else {
				$product_id = 0;
			}

			if (isset($this->request->get['path'])) {
				$path = '';
				$parts = explode('_', (string)$this->request->get['path']);
				$category_id = (int)array_pop($parts);
			} else {
				$category_id = 0;
			}

			$tmd_timeslot_title = $this->config->get('tmd_timeslot_title');
		
		$heading_title  = $tmd_timeslot_title[$this->config->get('config_language_id')]['title'];
		
		if(!empty($heading_title)){
			$data['text_timeslot']=$tmd_timeslot_title[$this->config->get('config_language_id')]['title'];
		}else{
			$data['text_timeslot']='';
		}

		$tmd_timeslot_slot_to = $this->config->get('tmd_timeslot_slot_to');
		$text_to  = $tmd_timeslot_slot_to[$this->config->get('config_language_id')]['to'];
		
		if(!empty($text_to)){
			$data['text_to']=$tmd_timeslot_slot_to[$this->config->get('config_language_id')]['to'];
		}else{
			$data['text_to']=$this->language->get('text_slotto');
		}

		$tmd_timeslot_slot_from = $this->config->get('tmd_timeslot_slot_from');
		$text_from  = $tmd_timeslot_slot_from[$this->config->get('config_language_id')]['from'];
		
		if(!empty($text_from)){
			$data['text_from']=$tmd_timeslot_slot_from[$this->config->get('config_language_id')]['from'];
		}else{
			$data['text_from']=$this->language->get('text_slotfrom');
		}

		if (isset($this->request->get['path'])) {
				$data['path_id'] = (int)$this->request->get['path'];
			} else {
				$data['path_id'] = 0;
			}

		
		if(empty($category_id)){
			$category_id =$this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getCategory($product_id);
		}
		
		$this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');
		$config_status        = $this->config->get('tmd_timeslot_status');
		$statusvalue          = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getProducttimeslotstatus($product_id);


			if(!empty($statusvalue['time_status'])){
				$data['timeslots']=[];
				$this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');
				$timeslotvalues = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getTimeslotValue($product_id);

				foreach($timeslotvalues as $timeslotvalue){
				$timeslottimedata=[];
				foreach ($timeslotvalue['timeslot_value_timedata'] as $language_id => $day) {
					$config_language_id = $this->config->get('config_language_id');
					if(!empty($config_language_id == $language_id)){
						$timeslottimedata[$language_id]=array('timeslot_time'=>$day['timeslot_time']);
					    }				
				     }  
					$data['timeslots'][]=[
					 'times'               =>$timeslottimedata,
					 'time_start'          =>$timeslotvalue['time_start'],
					 'time_end'            =>$timeslotvalue['time_end'],
					];
				}   
		      

			}elseif(!empty($config_status)){
				$config_pro_timmer='';
				$product_timmer=[];

				$tmd_timeslot_product_timer = $this->config->get('tmd_timeslot_product_timer');

				if(!empty($tmd_timeslot_product_timer)){
				foreach ($tmd_timeslot_product_timer as  $timer_product_id) {
					$product_timmer[$timer_product_id]=$timer_product_id;
				 }
			   }
			
				$config_cate_timmer='';
				$category_timmer=[];
				$tmd_timeslot_category_timer =  $this->config->get('tmd_timeslot_category_timer');
				if(!empty($tmd_timeslot_category_timer)){
				foreach ($tmd_timeslot_category_timer as  $timer_category_id) {
					$category_timmer[$timer_category_id]=$timer_category_id;
				  }
				}
			
		   	if (in_array($product_id,$product_timmer)){ 
			    $tmd_timeslot_slot_timer          =  $this->config->get('tmd_timeslot_slot_timer');
						foreach ($tmd_timeslot_slot_timer as $value_results) {
							$timeslot_value_timedata=[];
								foreach ($value_results['days'] as $language_id => $day) {
								$config_language_id = $this->config->get('config_language_id');
									if(!empty($config_language_id == $language_id)){
									$timeslot_value_timedata[$language_id]=array('timeslot_time'=>$day['timeslot_time']);
								}
							}
					
							$data['timeslots'][] = [
								'times'                        => $timeslot_value_timedata,
								'time_start'                   => $value_results['time_start'],
								'time_end'                     => $value_results['time_end'],
							];
		        }  
		       }    
  
				if(in_array($category_id,$category_timmer)){ 
					if(!empty($category_id)){
					$tmd_timeslot_slot_timer          =  $this->config->get('tmd_timeslot_slot_timer');
					foreach ($tmd_timeslot_slot_timer as $value_results) {
						$timeslot_value_timedata=[];
						foreach ($value_results['days'] as $language_id => $day) {
							$config_language_id = $this->config->get('config_language_id');
								if(!empty($config_language_id == $language_id)){
								$timeslot_value_timedata[$language_id]=array('timeslot_time'=>$day['timeslot_time']);
								
								}
						
				    }
						$data['timeslots'][] = [
							'times'                          => $timeslot_value_timedata,
							'time_start'                     => $value_results['time_start'],
							'time_end'                       => $value_results['time_end'],
			       ];
			      }
		        
					}
		    }
	  }
			return $this->load->view('extension/tmdavailabilitytimeslots/tmd/timeslot', $data);
	}

public function productcontroller(string &$route, array &$args, mixed &$output): void {
	$modulestatus=$this->config->get('module_timeslot_status');
		if(!empty($modulestatus)){
			$this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');

	    if(!empty($args['product_id'])){
		     $co_product_id = $args['product_id'];
		  }else{
		     $co_product_id = '';
		  }		  
		
			$this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');
			$statusvalue              =$this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getProducttimeslotstatus($co_product_id);
			$config_status            =$this->config->get('tmd_timeslot_status');
			
			if(!empty($statusvalue)){
			$args['timeslotdata'] = $this->load->controller('extension/tmdavailabilitytimeslots/tmd/timeslot');
			}elseif($config_status){
			$args['timeslotdata'] = $this->load->controller('extension/tmdavailabilitytimeslots/tmd/timeslot');
			}else{
			$args['timeslotdata']='';
	    }			 
			
			$template_buffer = $this->getTemplateBuffer($route,$output);		
			$find='<button type="submit" id="button-cart" class="btn btn-primary btn-lg btn-block">{{ button_cart }}</button>';
			$replace='{{ timeslotdata }}'.'<button type="submit" id="button-cart" class="btn btn-primary btn-lg btn-block">{{ button_cart }}</button>';
		 	$output = str_replace( $find, $replace, $template_buffer );
			 
			if(VERSION>='4.0.2.0')	{
				$template_buffer = $this->getTemplateBuffer($route,$output);		
				$find='index.php?route=checkout/cart.add&language={{ language }}';
				$replace='index.php?route=extension/tmdavailabilitytimeslots/tmd/timeslot.addvalidate&language={{ language }}';
				$output = str_replace( $find, $replace, $template_buffer );
			 
			}else{
				$template_buffer = $this->getTemplateBuffer($route,$output);	
			
				$find='index.php?route=checkout/cart|add&language={{ language }}';
				$replace='extension/tmdavailabilitytimeslots/tmd/timeslot|addvalidate&language={{ language }}';
				$output = str_replace( $find, $replace, $template_buffer );
			}
		 
		  $template_buffer = $this->getTemplateBuffer($route,$output);		
			$find="index.php?route=checkout/cart{{ constant('JOURNAL3_ROUTE_SEPARATOR') }}add{% if journal3_is_oc4 %}&language={{ language }}{% endif %}";
			$replace="index.php?route=extension/tmdavailabilitytimeslots/tmd/timeslot.addvalidate&language={{ language }}";
			$output = str_replace( $find, $replace, $template_buffer );
			
			$template_buffer = $this->getTemplateBuffer($route,$output);	
			$find='#product .button-group-page';
			$replace='.timeslot input,.timeslot select,#product .button-group-page';
			$output = str_replace( $find, $replace, $template_buffer );

			$template_buffer = $this->getTemplateBuffer($route,$output); 
	 		$find     = "if (json['error']) {";
	    $replace  = "if (json['error1']) {
          for (key in json['error1']) {
              $('#input-timeslotoption-'+key).addClass('is-invalid');
              $('#error-timeslotoption-'+key).html(json['error1'][key]).addClass('d-block');
          }
      }

      if (json['error2']) {
          for (key in json['error2']) {
              $('#error-timeslotoption1-'+key).html(json['error2'][key]).addClass('d-block');
              $('#input-timeslotoption1-'+key).addClass('is-invalid');
          }
      }
      "."if (json['error']) {";
  		$output   = str_replace($find, $replace, $template_buffer);
	 
		}
  }


  public function addvalidate(): void {
		$this->load->language('checkout/cart');
		$this->load->language('extension/tmdavailabilitytimeslots/tmd/timeslot');


		$json = [];

		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['path'])) {
			$path_id = (int)$this->request->post['path'];
		} else {
			$path_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = (int)$this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		if (isset($this->request->post['option'])) {
			$option = array_filter($this->request->post['option']);
		} else {
			$option = [];
		}

		if (isset($this->request->post['subscription_plan_id'])) {
			$subscription_plan_id = (int)$this->request->post['subscription_plan_id'];
		} else {
			$subscription_plan_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			// If variant get master product
			if ($product_info['master_id']) {
				$product_id = $product_info['master_id'];
			}

			// Only use values in the override
			if (isset($product_info['override']['variant'])) {
				$override = $product_info['override']['variant'];
			} else {
				$override = [];
			}

			// Merge variant code with options
			foreach ($product_info['variant'] as $key => $value) {
				if (array_key_exists($key, $override)) {
					$option[$key] = $value;
				}
			}

			// Validate options
			$product_options = $this->model_catalog_product->getOptions($product_id);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option_' . $product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}


			// new code
			$errormsgshow_status = $this->config->get('tmd_timeslot_errormsgshow_status');
			if(!empty($errormsgshow_status)){
						$option_timeslots = [];
				$this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');
				$product_timeslotoptions = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getProducttimeslotstatus($product_id);
				if(!empty($product_timeslotoptions)){
					if (isset($this->request->post['option']['timeslots'])) {
						$option_timeslots = array_filter($this->request->post['option']['timeslots']);
					} else {
						$option_timeslots = [0];
					}
				}else{
					$timesloterror = $this->config->get('tmd_timeslot_product_timer');

					if (!$timesloterror || !is_array($timesloterror)) {
					    $timesloterror = [];
					}

					if (in_array($product_id, $timesloterror)) {	
					    $option_timeslots = isset($this->request->post['option']['timeslots']) 
					        ? array_filter($this->request->post['option']['timeslots']) 
					        : [0];
					}


					$categoryerror = $this->config->get('tmd_timeslot_category_timer');

					if (!is_array($categoryerror)) {
					    $categoryerror = explode(',', $categoryerror); 
					}

					if (isset($this->request->post['path_id']) && in_array($this->request->post['path_id'], $categoryerror)) {
					    $option_timeslots = isset($this->request->post['option']['timeslots']) 
					        ? array_filter($this->request->post['option']['timeslots']) 
					        : [0];
					}

				}
				if(!empty($option_timeslots)){
					foreach ($option_timeslots as $key => $timeslots) {
						if(empty($option['timeslots'][$key]['stat'])){
							$json['error1'][$key] = $this->language->get('error_required1');
						}

						if(empty($option['timeslots'][$key]['end'])){
							$json['error2'][$key] = $this->language->get('error_required1');
						}
					}				
				}
			}	// new code

			// Validate subscription products
			$subscriptions = $this->model_catalog_product->getSubscriptions($product_id);

			if ($subscriptions) {
				$subscription_plan_ids = [];

				foreach ($subscriptions as $subscription) {
					$subscription_plan_ids[] = $subscription['subscription_plan_id'];
				}

				if (!in_array($subscription_plan_id, $subscription_plan_ids)) {
					$json['error']['subscription'] = $this->language->get('error_subscription');
				}
			}
		} else {
			$json['error']['warning'] = $this->language->get('error_product');
		}

	if (!$json) {
		$this->cart->add($product_id, $quantity, $option, $subscription_plan_id);

		$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product_id), $product_info['name'], $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language')));

		// Unset all shipping and payment methods
		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);
	} else {
		$json['redirect'] = $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product_id, true);
	}

	$this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput(json_encode($json));
}
 
  public function alltwigerrorcode(string &$route, array &$args, mixed &$output): void {
		$modulestatus=$this->config->get('module_timeslot_status');
		if(!empty($modulestatus)){
			$path = '';
			if (isset($this->request->get['path'])) {
				$parts = explode('_', $this->request->get['path']);

				$category_id = (int)array_pop($parts);

				foreach ($parts as $path_id) {
					if (!$path) {
						$path = (int)$path_id;
					} else {
						$path .= '_' . (int)$path_id;
					}
				}
			}
			if (!empty($path)) {
				$path = '&path='.$path;
			}elseif (isset($this->request->get['path'])) {
				$path = '&path='.(string)$this->request->get['path'];
			} else {
				$path = '';
			}

			if(VERSION >='4.0.2.0'){
				$args['add_to_cart'] = $this->url->link('extension/tmdavailabilitytimeslots/tmd/timeslot.addvalidate', 'language=' . $this->config->get('config_language').$path);		
			}else{
				$args['add_to_cart'] = $this->url->link('extension/tmdavailabilitytimeslots/tmd/timeslot|addvalidate', 'language=' . $this->config->get('config_language').$path);				
			}
		}
  }

  public function catalogaccount(string &$route, array &$args, mixed &$output): void {
			$modulestatus=$this->config->get('module_timeslot_status');
			if(!empty($modulestatus)){

			$this->load->model('account/subscription');
			$this->load->model('catalog/product');
			$this->load->model('tool/upload');

			// Products
			$this->load->model('account/order');

			$order_info = $this->model_account_order->getOrder($args['order_id']);

			$args['products'] = [];

			$products = $this->model_account_order->getProducts($args['order_id']);


			foreach ($products as $product) {
				$option_data = [];

				$options = $this->model_account_order->getOptions($args['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					if(VERSION>='4.0.2.0'){
		        $option_data[] = [
							'name'  => $option['name'],
							'value' => (oc_strlen($value) > 500 ? oc_substr($value, 0, 500) . '..' : $value)
						];

	        }else{
						$option_data[] = [
							'name'  => $option['name'],
							'value' => (Helper\Utf8\strlen($value) > 500 ? Helper\Utf8\substr($value, 0, 500) . '..' : $value)
						];
				  }
				}

				$description = '';

				$subscription_info = $this->model_account_order->getSubscription($args['order_id'], $product['order_product_id']);

				if ($subscription_info) {
					if ($subscription_info['trial_status']) {
						$trial_price = $this->currency->format($subscription_info['trial_price'] + ($this->config->get('config_tax') ? $subscription_info['trial_tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
						$trial_cycle = $subscription_info['trial_cycle'];
						$trial_frequency = $this->language->get('text_' . $subscription_info['trial_frequency']);
						$trial_duration = $subscription_info['trial_duration'];

						$description .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
					}

					$price = $this->currency->format($subscription_info['price'] + ($this->config->get('config_tax') ? $subscription_info['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
					$cycle = $subscription_info['cycle'];
					$frequency = $this->language->get('text_' . $subscription_info['frequency']);
					$duration = $subscription_info['duration'];

					if ($subscription_info['duration']) {
						$description .= sprintf($this->language->get('text_subscription_duration'), $price, $cycle, $frequency, $duration);
					} else {
						$description .= sprintf($this->language->get('text_subscription_cancel'), $price, $cycle, $frequency);
					}
				}

				$subscription_info = $this->model_account_subscription->getSubscriptionByOrderProductId($args['order_id'], $product['order_product_id']);

				if ($subscription_info) {
					$subscription = $this->url->link('account/subscription.info', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&subscription_id=' . $subscription_info['subscription_id']);
				} else {
					$subscription = '';
				}

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order.reorder', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&order_id=' . $args['order_id'] . '&order_product_id=' . $product['order_product_id']);
				} else {
					$reorder = '';
				}

				$args['products'][] = [
					'name'                     => $product['name'],
					'model'                    => $product['model'],
					'option'                   => $option_data,
					'subscription'             => $subscription,
					'subscription_description' => $description,
					'quantity'                 => $product['quantity'],
					'price'                    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'                    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'href'                     => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product['product_id']),
					'reorder'                  => $reorder,
					'return'                   => $this->url->link('account/returns.add', 'language=' . $this->config->get('config_language') . '&order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'])
				];
			}

        $this->load->language('extension/tmdavailabilitytimeslots/tmd/timeslot');   
			  $this->load->model('extension/tmdavailabilitytimeslots/tmd/timeslot');

				$args['productInfos']    = [];

				$tmd_timeslot_slot_no    = $this->config->get('tmd_timeslot_slot_to');
				$language_id             = $this->config->get('config_language_id');
				$slot_to                 = $tmd_timeslot_slot_no[$language_id]['to'];
				
				$timesloprodcutInfos = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getOrdertimeslotProductdata($this->request->get['order_id']);
				foreach($timesloprodcutInfos as $timesloprodcutInfo){
					$resultsProductTimeslots = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getOrdertimeslotCustomer($timesloprodcutInfo['product_id'],$this->request->get['order_id']);

					$timeslotdata     = [];
					foreach($resultsProductTimeslots as $timeslots){
					   $timeslotdata[]=[
						  'tmdday' =>$timeslots['tmdday'].' '.$timeslots['tmdstart'].' '.$slot_to.' ' .$timeslots['endtmd']
					   ];
				   }

					$timeslotProductResults = $this->model_extension_tmdavailabilitytimeslots_tmd_timeslot->getProducttimeslot($timesloprodcutInfo['product_id']);
					 if(!empty($timeslotProductResults)){
						 $name        = $timeslotProductResults['name'];
						 $product_id  =  $timeslotProductResults['product_id'];
					 }
					 
					 
					$args['productInfos'][]=[
						  'timeslotdata' => $timeslotdata,
						  'name'         => $name
					];
				}  

        $template_buffer = $this->getTemplateBuffer($route,$output);	
            if(VERSION>='4.0.2.0')	{
			$find='<div id="history">{{ history }}</div>';
            $replace='<div id="history">{{ history }}</div>'.'<div class="tab-pane">
			<h3>{{ entry_timeslot }}</h3>
			<div class="table-responsive">
			  <table class="table table-bordered">
				<thead>
				  <tr>
					<td>{{ entry_producttime }}</td>
					<td>{{ entry_timeslot }}</td>
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
							{% set i=i+1 %}
							{% endfor %}
						</table>
					</td>
					  <tr>
				{% endfor %}
				  </tbody>
			  </table>
			</div>		   
			</div>';
		}else{
           $find='<div class="d-inline-block pt-2 pd-2 w-100">';
		   $replace='<div class="tab-pane">
			<h3>{{ entry_timeslot }}</h3>
			<div class="table-responsive">
			  <table class="table table-bordered">
				<thead>
				  <tr>
					<td>{{ entry_producttime }}</td>
					<td>{{ entry_timeslot }}</td>
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
							{% set i=i+1 %}
							{% endfor %}
						</table>
					</td>
					  <tr>
				{% endfor %}
				  </tbody>
			  </table>
			</div>		   
			</div>'.'<div class="d-inline-block pt-2 pd-2 w-100">';
		}
			$output = str_replace( $find, $replace, $template_buffer );

		  }
	}

  public function addOrderHistorytimeslot(string&$route, array&$args):void {
	 $modulestatus=$this->config->get('module_timeslot_status');
	   if(!empty($modulestatus)){
      
		if (isset($args[0])) {
			$order_id = $args[0];
		} else {
			$order_id = 0;
		}

		$cartoptions = $this->cart->getProducts();
      foreach($cartoptions as $key => $optionvalue){
        if(!empty($optionvalue['option'])) {
	        foreach($optionvalue['option'] as $key => $value){
	        	$query = $this->db->query("SELECT order_product_id FROM " . DB_PREFIX . "order_product WHERE  order_id = '" . (int)$order_id . "' and  product_id = '" . (int)$optionvalue['product_id'] . "'")->row;
			  
        
	                 if(!empty($query['order_product_id'])){
                       $order_product_id = $query['order_product_id'];
                     }

                    /// time time slot start 
				   if($value['type'] =='timeslot'){

					if(!empty($value['tmdday'])){
						$tmdday=$value['tmdday'];
					}
					if(!empty($value['tmdend'])){
						$tmdend=$value['tmdend'];
					}
					if(!empty($value['tmdstat'])){
						$tmdstat=$value['tmdstat'];
					}

					$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_ordertimeslot SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "',tmdstart = '" . (int)$tmdstat. "',endtmd = '" . (int)$tmdend . "', tmdday= '" .  $this->db->escape($tmdday). "',date_added = NOW()");
					}else{
						$tmdday='';
						$tmdend='';
						$tmdstat='';
					}
					/// time time slot end 

            }

          }
       }

        /*email start */
					$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `status` = '0' WHERE action = 'mail/order'");			
					if (isset($args[1])) {
						$order_status_id = $args[1];
					} else {
						$order_status_id = 0;
					}

					if (isset($args[2])) {
						$comment = $args[2];
					} else {
						$comment = '';
					}

					if (isset($args[3])) {
						$notify = $args[3];
					} else {
						$notify = '';
					}

					// We need to grab the old order status ID
					$order_info = $this->model_checkout_order->getOrder($order_id);

					if ($order_info) {
						// If order status is 0 then becomes greater than 0 send main html email
						if (!$order_info['order_status_id'] && $order_status_id) {
							$this->add($order_info, $order_status_id, $comment, $notify);
						}

						// If order status is not 0 then send update text email
						if ($order_info['order_status_id'] && $order_status_id && $notify) {
							$this->edit($order_info, $order_status_id, $comment, $notify);
						}
					}
	         /*email end */

       }else{
			    $this->db->query("UPDATE `" . DB_PREFIX . "event` SET `status` = '1' WHERE action = 'mail/order'");			
				}

     }


    /*email start*/
    public function add(array $order_info, int $order_status_id, string $comment, bool $notify): void {
		// Check for any downloadable products
		$download_status = false;

		$order_products = $this->model_checkout_order->getProducts($order_info['order_id']);

		foreach ($order_products as $order_product) {
			// Check if there are any linked downloads
			$product_download_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_to_download` WHERE `product_id` = '" . (int)$order_product['product_id'] . "'");

			if ($product_download_query->row['total']) {
				$download_status = true;
			}
		}

		$this->load->model('setting/store');
		$store_info = $this->model_setting_store->getStore($order_info['store_id']);
		if ($store_info) {
			$this->load->model('setting/setting');

			$store_logo = html_entity_decode($this->model_setting_setting->getValue('config_logo', $store_info['store_id']), ENT_QUOTES, 'UTF-8');
			$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');
			$store_url = $store_info['url'];
		} else {
			$store_logo = html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8');
			$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
			$store_url = HTTP_SERVER;
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($order_info['language_id']);

		if ($language_info) {
			$language_code = $language_info['code'];
		} else {
			$language_code = $this->config->get('config_language');
		}

		// Load the language for any mails using a different country code and prefixing it so it does not pollute the main data pool.
		$this->language->load($language_code, 'mail', $language_code);
		$this->language->load('mail/order_add', 'mail', $language_code);

		// Add language vars to the template folder
		$results = $this->language->all('mail');

		foreach ($results as $key => $value) {
			$data[$key] = $value;
		}

		$subject = sprintf($this->language->get('mail_text_subject'), $store_name, $order_info['order_id']);

		$this->load->model('tool/image');

		if (is_file(DIR_IMAGE . $store_logo)) {
			$data['logo'] = $store_url . 'image/' . $store_logo;
		} else {
			$data['logo'] = '';
		}

		$data['title'] = sprintf($this->language->get('mail_text_subject'), $store_name, $order_info['order_id']);

		$data['text_greeting'] = sprintf($this->language->get('mail_text_greeting'), $order_info['store_name']);

		$data['store'] = $store_name;
		$data['store_url'] = $order_info['store_url'];

		$data['customer_id'] = $order_info['customer_id'];
		$data['link'] = $order_info['store_url'] . 'index.php?route=account/order|info&order_id=' . $order_info['order_id'];

		if ($download_status) {
			$data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
		} else {
			$data['download'] = '';
		}

		$data['order_id'] = $order_info['order_id'];
		if(VERSION>='4.0.2.0'){
		$data['date_added']      = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
		$data['payment_method']  = $order_info['payment_method']['name'];
		$data['shipping_method'] = $order_info['shipping_method']['name'];
	     }else{
		$data['date_added']      = date($this->language->get('mail_date_format_short'), strtotime($order_info['date_added']));
		$data['payment_method']  = $order_info['payment_method'];
		$data['shipping_method'] = $order_info['shipping_method'];
	     }
		$data['email'] = $order_info['email'];
		$data['telephone'] = $order_info['telephone'];
		$data['ip'] = $order_info['ip'];

		$order_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$order_info['language_id'] . "'");

		if ($order_status_query->num_rows) {
			$data['order_status'] = $order_status_query->row['name'];
		} else {
			$data['order_status'] = '';
		}

		if ($comment && $notify) {
			$data['comment'] = nl2br($comment);
		} else {
			$data['comment'] = '';
		}

		if ($order_info['payment_address_format']) {
			$format = $order_info['payment_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = [
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{zone_code}',
			'{country}'
		];

		$replace = [
			'firstname' => $order_info['payment_firstname'],
			'lastname'  => $order_info['payment_lastname'],
			'company'   => $order_info['payment_company'],
			'address_1' => $order_info['payment_address_1'],
			'address_2' => $order_info['payment_address_2'],
			'city'      => $order_info['payment_city'],
			'postcode'  => $order_info['payment_postcode'],
			'zone'      => $order_info['payment_zone'],
			'zone_code' => $order_info['payment_zone_code'],
			'country'   => $order_info['payment_country']
		];

		$data['payment_address'] = str_replace(["\r\n", "\r", "\n"], '<br/>', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br/>', trim(str_replace($find, $replace, $format))));

		if ($order_info['shipping_address_format']) {
			$format = $order_info['shipping_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = [
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{zone_code}',
			'{country}'
		];

		$replace = [
			'firstname' => $order_info['shipping_firstname'],
			'lastname'  => $order_info['shipping_lastname'],
			'company'   => $order_info['shipping_company'],
			'address_1' => $order_info['shipping_address_1'],
			'address_2' => $order_info['shipping_address_2'],
			'city'      => $order_info['shipping_city'],
			'postcode'  => $order_info['shipping_postcode'],
			'zone'      => $order_info['shipping_zone'],
			'zone_code' => $order_info['shipping_zone_code'],
			'country'   => $order_info['shipping_country']
		];

		$data['shipping_address'] = str_replace(["\r\n", "\r", "\n"], '<br/>', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br/>', trim(str_replace($find, $replace, $format))));

		$this->load->model('tool/upload');

		// Products
		$data['products'] = [];

		foreach ($order_products as $order_product) {
			$option_data = [];

			$order_options = $this->model_checkout_order->getOptions($order_info['order_id'], $order_product['order_product_id']);

			foreach ($order_options as $order_option) {
				if ($order_option['type'] != 'file') {
					$value = $order_option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($order_option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}
                
        if(VERSION>='4.0.2.0'){
	        $option_data[] = [
						'name'  => $order_option['name'],
						'value' => (oc_strlen($value) > 500 ? oc_substr($value, 0, 500) . '..' : $value)
					];

        }else{
					$option_data[] = [
						'name'  => $order_option['name'],
						'value' => (Helper\Utf8\strlen($value) > 500 ? Helper\Utf8\substr($value, 0, 500) . '..' : $value)
					];
			  }
			}

			$data['products'][] = [
				'name'     => $order_product['name'],
				'model'    => $order_product['model'],
				'option'   => $option_data,
				'quantity' => $order_product['quantity'],
				'price'    => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    => $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
			];
		}

		// Vouchers
		$data['vouchers'] = [];

		$order_vouchers = $this->model_checkout_order->getVouchers($order_info['order_id']);

		foreach ($order_vouchers as $order_voucher) {
			$data['vouchers'][] = [
				'description' => $order_voucher['description'],
				'amount'      => $this->currency->format($order_voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
			];
		}

		// Order Totals
		$data['totals'] = [];

		$order_totals = $this->model_checkout_order->getTotals($order_info['order_id']);

		foreach ($order_totals as $order_total) {
			$data['totals'][] = [
				'title' => $order_total['title'],
				'text'  => $this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value']),
			];
		}

		$this->load->model('setting/setting');

		$from = $this->model_setting_setting->getValue('config_email', $order_info['store_id']);

		if (!$from) {
			$from = $this->config->get('config_email');
		}

		if(VERSION>='4.0.2.0'){
       if ($this->config->get('config_mail_engine')) {
			$mail_option = [
				'parameter'     => $this->config->get('config_mail_parameter'),
				'smtp_hostname' => $this->config->get('config_mail_smtp_hostname'),
				'smtp_username' => $this->config->get('config_mail_smtp_username'),
				'smtp_password' => html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'),
				'smtp_port'     => $this->config->get('config_mail_smtp_port'),
				'smtp_timeout'  => $this->config->get('config_mail_smtp_timeout')
			];

			$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'), $mail_option);
			$mail->setTo($order_info['email']);
			$mail->setFrom($from);
			$mail->setSender($store_name);
			$mail->setSubject($subject);
			$mail->setHtml($this->load->view('mail/order_invoice', $data));
			$mail->send();
		}
   }else{

		if ($this->config->get('config_mail_engine')) {
			$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($order_info['email']);
			$mail->setFrom($from);
			$mail->setSender($store_name);
			$mail->setSubject($subject);
			$mail->setHtml($this->load->view('mail/order_invoice', $data));
			$mail->send();
		  }
		}
	}


  public function edit(array $order_info, int $order_status_id, string $comment, bool $notify): void {
		$this->load->model('setting/store');
		$store_info = $this->model_setting_store->getStore($order_info['store_id']);

		if ($store_info) {
			$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');
			$store_url = $store_info['url'];
		} else {
			$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
			$store_url = HTTP_SERVER;
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($order_info['language_id']);

		if ($language_info) {
			$language_code = $language_info['code'];
		} else {
			$language_code = $this->config->get('config_language');
		}

		// Load the language for any mails using a different country code and prefixing it so it does not pollute the main data pool.
		$this->language->load($language_code, 'mail', $language_code);
		$this->language->load('mail/order_edit', 'mail', $language_code);

		// Add language vars to the template folder
		$results = $this->language->all('mail');

		foreach ($results as $key => $value) {
			$data[$key] = $value;
		}

		$subject = sprintf($this->language->get('mail_text_subject'), $store_name, $order_info['order_id']);

		$data['order_id']   = $order_info['order_id'];

		if(VERSION>='4.0.2.0'){
        $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
		}else{
		$data['date_added'] = date($this->language->get('mail_date_format_short'), strtotime($order_info['date_added']));
		}

		$order_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$order_info['language_id'] . "'");

		if ($order_status_query->num_rows) {
			$data['order_status'] = $order_status_query->row['name'];
		} else {
			$data['order_status'] = '';
		}

    if(VERSION>='4.0.2.3'){
			if ($order_info['customer_id']) {
				$data['link'] = $order_info['store_url'] . 'index.php?route=account/order.info&order_id=' . $order_info['order_id'];
			} else {
				$data['link'] = '';
			}
        }else{
        	if ($order_info['customer_id']) {
				$data['link'] = $order_info['store_url'] . 'index.php?route=account/order|info&order_id=' . $order_info['order_id'];
			} else {
				$data['link'] = '';
			}
        }
           /*timeslot email start*/
           $this->load->language('extension/tmdavailabilitytimeslots/tmd/timeslot');
            $tmd_timeslot_slot_no = $this->config->get('tmd_timeslot_slot_no');
				$language_id          = $this->config->get('config_language_id');
				if(!empty($tmd_timeslot_slot_no[$language_id]['on'])){
				$data['slot_no']      = $tmd_timeslot_slot_no[$language_id]['on'];
			     }else{
				$data['slot_no']      = $this->language->get('text_sloton');
			   }
              
			  
			   $tmd_timeslot_slot_no = $this->config->get('tmd_timeslot_slot_from');
				$language_id          = $this->config->get('config_language_id');
				if(!empty($tmd_timeslot_slot_no[$language_id]['from'])){
				$data['slot_from']      = $tmd_timeslot_slot_no[$language_id]['from'];
			     }else{
				$data['slot_from']      = $this->language->get('text_slotfrom');
			   }

			   $tmd_timeslot_slot_no = $this->config->get('tmd_timeslot_slot_to');
				$language_id          = $this->config->get('config_language_id');
				if(!empty($tmd_timeslot_slot_no[$language_id]['to'])){
				$data['slot_to']      = $tmd_timeslot_slot_no[$language_id]['to'];
			     }else{
				$data['slot_to']      = $this->language->get('text_slotto');
			   }
        
           
			$data['timeslots'] = [];
			$ordertimeslot_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_ordertimeslotcustomer WHERE order_status_id = '" . (int)$order_status_id . "' and order_id ='".$order_info['order_id']."' order by orderctime_id  DESC limit 0,1");
		
  
			if(!empty($ordertimeslot_status_query->rows)){
			foreach($ordertimeslot_status_query->rows as $statusquery ){
				$ordertimeslot_prody_query =$this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$statusquery['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				if (!empty($ordertimeslot_prody_query->row['name'])) {
					$pname = html_entity_decode($ordertimeslot_prody_query->row['name']);
				} else {
					$pname = '';
				}
				$data['timeslots'][]=[
				 'tmdday'    =>$statusquery['tmdday'],	
				 'tmdstart'  =>$statusquery['tmdstart'],	
				 'endtmd'    =>$statusquery['endtmd'],	
				 'pname'     =>$pname,
				];
			  }
			}
      /*timeslot email end*/
		$data['comment'] = strip_tags($comment);

		$data['store'] = $store_name;
		$data['store_url'] = $store_url;

		$this->load->model('setting/setting');

		$from = $this->model_setting_setting->getValue('config_email', $order_info['store_id']);

		if (!$from) {
			$from = $this->config->get('config_email');
		}

   if(VERSION>='4.0.2.0'){
      if ($this->config->get('config_mail_engine')) {
			$mail_option = [
				'parameter'     => $this->config->get('config_mail_parameter'),
				'smtp_hostname' => $this->config->get('config_mail_smtp_hostname'),
				'smtp_username' => $this->config->get('config_mail_smtp_username'),
				'smtp_password' => html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'),
				'smtp_port'     => $this->config->get('config_mail_smtp_port'),
				'smtp_timeout'  => $this->config->get('config_mail_smtp_timeout')
			];

			$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'), $mail_option);
			$mail->setTo($order_info['email']);
			$mail->setFrom($from);
			$mail->setSender($store_name);
			$mail->setSubject($subject);
			$mail->setHtml($this->load->view('extension/tmdavailabilitytimeslots/tmd/order_history', $data));
			$mail->send();
		}
   }else{

		if ($this->config->get('config_mail_engine')) {
			$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($order_info['email']);
			$mail->setFrom($from);
			$mail->setSender($store_name);
			$mail->setSubject($subject);
			$mail->setHtml($this->load->view('extension/tmdavailabilitytimeslots/tmd/order_history', $data));
			$mail->send();
		  }


     }

	}

    /*email end*/

  public function deleteOrder(string &$route, array &$args): void  {
		$modulestatus=$this->config->get('module_timeslot_status');
		if(!empty($modulestatus)){
			$this->db->query("DELETE FROM " . DB_PREFIX . "timeslot_ordertimeslot WHERE order_id = '" . (int)$args[0] . "'");				
	
   	}	
  }


 public function apisaleorder(&$route, array &$args, mixed &$output): void {  
      $modulestatus=$this->config->get('module_timeslot_status');
         if(!empty($modulestatus)){
          
     if (isset($this->request->get['order_id'])) {
      $order_id = (int)$this->request->get['order_id'];
	   $this->cart->clear();  
       $this->load->model('checkout/order');
     $products = $this->model_checkout_order->getProducts($order_id);

     foreach ($products as $product) {
        $option_data = [];

        $options = $this->model_checkout_order->getOptions($order_id, $product['order_product_id']);
   
        foreach ($options as $option) {
          if ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
            $option_data[$option['product_option_id']] = $option['value'];
          } elseif ($option['type'] == 'select' || $option['type'] == 'radio') {
            $option_data[$option['product_option_id']] = $option['product_option_value_id'];
          } elseif ($option['type'] == 'checkbox') {
            $option_data[$option['product_option_id']][] = $option['value'];
          }else{
            $option_data[$option['type']] = $option['value'];
          }
       
        }
        $this->cart->add($product['product_id'], $product['quantity'], $option_data);
      }

         }
		 }
	}


  protected function getTemplateBuffer( $route, $event_template_buffer ) {
      // if there already is a modified template from view/*/before events use that one
      if ($event_template_buffer) {
          return $event_template_buffer;
      }

      // load the template file (possibly modified by ocmod and vqmod) into a string buffer
      $dir_template = DIR_TEMPLATE ;
      
      if ($this->config->get('config_theme') == 'default') {
          $theme = $this->config->get('theme_default_directory');
           $template_file = $dir_template . $route . '.twig';
      } else {
          $theme = $this->config->get('config_theme');
           
      }
      
      $template_file = $dir_template . $route . '.twig';
      
      if ($this->config->get('config_theme') == 'journal_3') {
          $route=str_replace('journal3/','journal3/template/',$route);
            $template_file = str_replace('template','theme',$dir_template) . $route . '.twig';    
      }
          

    if (file_exists( $template_file ) && is_file( $template_file )) {
        
        return file_get_contents( $template_file );
    }
  }
}