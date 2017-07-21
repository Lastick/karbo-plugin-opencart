<?php

class ControllerPaymentKarbo extends Controller {

  private $error = array(); 

  public function index(){
    $this->language->load('payment/karbo');
    $this->document->setTitle($this->language->get('heading_title'));
    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
      $this->model_setting_setting->editSetting('karbo', $this->request->post);
      $this->session->data['success'] = $this->language->get('text_success');
      $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }

    $this->data['heading_title'] = $this->language->get('heading_title');
    $this->data['text_all_zones'] = $this->language->get('text_all_zones');
    $this->data['text_none'] = $this->language->get('text_none');
    $this->data['wallet_address'] = $this->language->get('wallet_address');
    $this->data['wallet_type'] = $this->language->get('wallet_type');
    $this->data['wallet_type_simplewallet'] = $this->language->get('wallet_type_simplewallet');
    $this->data['wallet_type_walletd'] = $this->language->get('wallet_type_walletd');
    $this->data['wallet_type_gateway'] = $this->language->get('wallet_type_gateway');
    $this->data['wallet_host'] = $this->language->get('wallet_host');
    $this->data['wallet_port'] = $this->language->get('wallet_port');
    $this->data['wallet_ssl'] = $this->language->get('wallet_ssl');
    $this->data['karbo_text_yes'] = $this->language->get('karbo_yes');
    $this->data['karbo_text_no'] = $this->language->get('karbo_no');
    $this->data['karbo_text_enable'] = $this->language->get('karbo_enable');
    $this->data['karbo_text_disable'] = $this->language->get('karbo_disable');
    $this->data['karbo_text_order_status'] = $this->language->get('karbo_order_status');
    $this->data['karbo_text_order_payment_status'] = $this->language->get('karbo_order_payment_status');
    $this->data['karbo_text_status'] = $this->language->get('karbo_status');
    $this->data['karbo_text_sort_order'] = $this->language->get('karbo_sort_order');
    $this->data['karbo_text_geo_zone'] = $this->language->get('karbo_geo_zone');
    $this->data['button_save'] = $this->language->get('button_save');
    $this->data['button_cancel'] = $this->language->get('button_cancel');

    if (isset($this->error['warning'])){
      $this->data['error_warning'] = $this->error['warning'];
      } else {
      $this->data['error_warning'] = '';
    }

    $this->data['breadcrumbs'] = array();
    $this->data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
    );
    $this->data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_payment'),
      'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),       		
      'separator' => ' :: '
    );
    $this->data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('payment/karbo', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );

    $this->data['action'] = $this->url->link('payment/karbo', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');	

    $this->data['karbo_wallet_address'] = $this->config->get('karbo_wallet_address');
    $this->data['karbo_wallet_type'] = $this->config->get('karbo_wallet_type');
    $this->data['karbo_wallet_host'] = $this->config->get('karbo_wallet_host');
    $this->data['karbo_wallet_port'] = $this->config->get('karbo_wallet_port');
    $this->data['karbo_wallet_ssl'] = $this->config->get('karbo_wallet_ssl');
    $this->data['karbo_order_status_id'] = $this->config->get('karbo_order_status_id');
    $this->data['karbo_order_payment_status_id'] = $this->config->get('karbo_order_payment_status_id');
    $this->data['karbo_geo_zone_id'] = $this->config->get('karbo_geo_zone_id');
    $this->data['karbo_status'] = $this->config->get('karbo_status');
    $this->data['karbo_sort_order'] = $this->config->get('karbo_sort_order');

    $this->load->model('localisation/order_status');
    $this->data['karbo_order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
    $this->load->model('localisation/geo_zone');
    $this->data['karbo_geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    $this->template = 'payment/karbo.tpl';
    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->response->setOutput($this->render());
  }

  public function install(){
    $this->load->model('payment/karbo');
    $this->load->model('setting/setting');
    $settings['karbo_wallet_address']		= '';
    $settings['karbo_wallet_type']		= '0';
    $settings['karbo_wallet_host']		= '127.0.0.1';
    $settings['karbo_wallet_port']		= '15000';
    $settings['karbo_wallet_ssl']		= '0';
    $settings['karbo_order_status_id']		= '1';
    $settings['karbo_order_payment_status_id']	= '2';
    $settings['karbo_geo_zone_id']		= '0';
    $settings['karbo_status']			= '0';
    $settings['karbo_sort_order']		= '0';
    $this->model_payment_karbo->install();
    $this->model_setting_setting->editSetting('karbo', $settings);
  }

  public function uninstall(){
    $this->load->model('payment/karbo');
    $this->load->model('setting/setting');
    $this->model_setting_setting->deleteSetting('karbo');
    $this->model_payment_karbo->uninstall();
  }

  protected function validate(){
    if (!$this->user->hasPermission('modify', 'payment/karbo')){
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (!$this->error){
      return true;
      } else {
      return false;
    }
  }

}

?>
