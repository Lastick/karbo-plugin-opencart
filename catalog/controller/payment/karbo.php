<?php

class ControllerPaymentKarbo extends Controller {

  protected function index(){
    $this->load->library('karbo');
    $this->load->model('payment/karbo');
    $this->load->model('checkout/order');

    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    $this->language->load('payment/karbo');
    $this->data['text_information'] = $this->language->get('text_information');
    $this->data['text_description'] = $this->language->get('text_description');
    $this->data['button_confirm'] = $this->language->get('button_confirm');
    $this->data['continue'] = $this->url->link('checkout/karbo');

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/karbo.tpl')){
      $this->template = $this->config->get('config_template') . '/template/payment/karbo.tpl';
      } else {
      $this->template = 'default/template/payment/karbo.tpl';
    }

    $this->session->data['karbo_wallet_address'] = $this->config->get('karbo_wallet_address');
    $this->session->data['karbo_payment_id'] = Karbo::genPaymentId();
    $this->session->data['karbo_order_id'] = $this->session->data['order_id'];
    $this->session->data['karbo_total'] = floatval($order_info['total']);
    $this->session->data['karbo_store_name'] = str_replace(' ', '%20', $order_info['store_name']);

    $this->render();
  }

  public function confirm(){
    $this->load->model('checkout/order');
    $this->load->model('payment/karbo');
    $comment  = $this->session->data['karbo_wallet_address'] . '<br />';
    $comment .= $this->session->data['karbo_payment_id'];
    $this->model_payment_karbo->addKarboOrder($this->session->data['order_id'], $this->session->data['karbo_payment_id']);
    $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('karbo_order_status_id'), $comment, true);
  }

  public function cron(){
    $this->load->model('payment/karbo');
    if ($this->config->get('karbo_status')){
      $this->model_payment_karbo->KarboConfirmPayment();
      echo 'Karbo init ...';
      } else {
      echo 'Karbo disable!';
    }
  }

}

?>
