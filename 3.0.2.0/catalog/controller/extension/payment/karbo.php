<?php

class ControllerExtensionPaymentKarbo extends Controller {

  public function index(){
    $this->load->library('karbo');
    $this->load->model('extension/payment/karbo');
    $this->load->model('checkout/order');
    $this->load->language('extension/payment/karbo');
	
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    $this->session->data['karbo_wallet_address'] = $this->config->get('payment_karbo_wallet_address');
    $this->session->data['karbo_payment_id'] = Karbo::genPaymentId();
    $this->session->data['karbo_order_id'] = $this->session->data['order_id'];
    $this->session->data['karbo_total'] = floatval($order_info['total']);
    $this->session->data['karbo_store_name'] = str_replace(' ', '%20', $order_info['store_name']);

    $data = array();

    return $this->load->view('extension/payment/karbo', $data);
  }

  public function confirm(){
    $json = array();
		
    if ($this->session->data['payment_method']['code'] == 'karbo'){
      $this->load->model('checkout/order');
      $this->load->model('extension/payment/karbo');
      $this->load->language('extension/payment/karbo');

      $comment  = $this->session->data['karbo_wallet_address'] . '<br />';
      $comment .= $this->session->data['karbo_payment_id'];

      $this->model_extension_payment_karbo->addKarboOrder($this->session->data['order_id'], $this->session->data['karbo_payment_id']);
      $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_karbo_order_status_id'), $comment, true);
		
      $json['redirect'] = $this->url->link('extension/checkout/karbo');
    }
	
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));		
  }

  public function api(){
    $this->load->library('karbo');
    $this->language->load('extension/checkout/karbo');
    $karbo = new Karbo($this->config->get('payment_karbo_wallet_address'),
                       $this->config->get('payment_karbo_wallet_host'),
                       $this->config->get('payment_karbo_wallet_port'),
                       $this->config->get('payment_karbo_wallet_ssl'),
                       $this->config->get('payment_karbo_wallet_type'));
    $karbo->setTxConf($this->config->get('payment_karbo_wallet_tx_conf'));
    $status = array();
    $payment = array();
    if (isset($this->request->get['karbo_payment_id']) and isset($this->session->data['karbo_payment_id'])){
      if ($this->request->get['karbo_payment_id'] == $this->session->data['karbo_payment_id']){
        $status = $karbo->getStatus();
        if ($status['status']){
          $payment = $karbo->getStatusPayment($this->session->data['karbo_payment_id']);
          $args['status'] = true;
          $args['tx_conf'] = $this->config->get('payment_karbo_wallet_tx_conf');
          $args['lang']['text_payment_wait'] = $this->language->get('text_payment_wait');
          $args['lang']['text_payment_unconf'] = $this->language->get('text_payment_unconf');
          $args['lang']['text_payment_conf'] = $this->language->get('text_payment_conf');
          $args['payment']['tx_conf'] = $payment['tx_conf'];
          echo json_encode($args);
        }
      }
    }
  }

  public function cron(){
    $this->load->model('extension/payment/karbo');
    if ($this->config->get('payment_karbo_status')){
      $this->model_extension_payment_karbo->KarboConfirmPayment();
      echo 'Karbo init ...';
      } else {
      echo 'Karbo disable!';
    }
  }

}

?>