<?php

class ControllerCheckoutKarbo extends Controller { 

  public function index(){
    $order_trig = true;
    if (isset($this->session->data['order_id'])){
      $this->cart->clear();

      unset($this->session->data['shipping_method']);
      unset($this->session->data['shipping_methods']);
      unset($this->session->data['payment_method']);
      unset($this->session->data['payment_methods']);
      unset($this->session->data['guest']);
      unset($this->session->data['comment']);
      unset($this->session->data['order_id']);	
      unset($this->session->data['coupon']);
      unset($this->session->data['reward']);
      unset($this->session->data['voucher']);
      unset($this->session->data['vouchers']);
      unset($this->session->data['totals']);
      $order_trig = false;
    }

    $this->language->load('checkout/karbo');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->data['breadcrumbs'] = array(); 
    $this->data['breadcrumbs'][] = array(
      'href' => $this->url->link('common/home'),
      'text' => $this->language->get('text_home'),
      'separator' => false
    );
    $this->data['breadcrumbs'][] = array(
      'href' => $this->url->link('checkout/cart'),
      'text' => $this->language->get('text_basket'),
      'separator' => $this->language->get('text_separator')
    );
    $this->data['breadcrumbs'][] = array(
      'href' => $this->url->link('checkout/checkout', '', 'SSL'),
      'text' => $this->language->get('text_checkout'),
      'separator' => $this->language->get('text_separator')
    );
    $this->data['breadcrumbs'][] = array(
      'href' => $this->url->link('checkout/karbo', '', 'SSL'),
      'text' => $this->language->get('text_payment'),
      'separator' => $this->language->get('text_separator')
    );

    $this->data['heading_title'] = $this->language->get('heading_title');
    $this->data['karbo_text_payment_id'] = $this->language->get('text_payment_id');
    $this->data['karbo_text_address'] = $this->language->get('text_address');
    $this->data['text_instruction'] = $this->language->get('text_instruction');
    $this->data['text_description'] = $this->language->get('text_description');
    $this->data['text_payment'] = $this->language->get('text_payment_info');
    $this->data['text_amount'] = $this->language->get('text_amount');
    $this->data['text_payment_wait'] = $this->language->get('text_payment_wait');
    $this->data['text_payment_unconf'] = $this->language->get('text_payment_unconf');
    $this->data['text_payment_conf'] = $this->language->get('text_payment_conf');

    $this->data['karbo_payment_id'] = '';
    $this->data['karbo_address'] = '';
    $this->data['text_total'] = '';
    $this->data['text_store_name'] = '';
    $this->data['karbo_link'] = '';
    $this->data['karbo_qr_link'] = '';

    $this->data['button_continue'] = $this->language->get('button_continue');
    $this->data['continue'] = $this->url->link('checkout/success');

    if ($this->customer->isLogged()){
    }

    if (isset($this->session->data['karbo_payment_id'])){
      $this->data['karbo_payment_id'] = $this->session->data['karbo_payment_id'];
    }
    if (isset($this->session->data['karbo_wallet_address'])){
      $this->data['karbo_address'] = $this->session->data['karbo_wallet_address'];
    }
    if (isset($this->session->data['karbo_total'])){
      $this->data['text_total'] = strval($this->session->data['karbo_total']);
      $this->data['text_total_ext'] = sprintf("%01.2f", ($this->session->data['karbo_total']));
    }
    if (isset($this->session->data['karbo_store_name'])){
      $this->data['text_store_name'] = $this->session->data['karbo_store_name'];
    }

    $this->data['karbo_link']  = 'karbowanec:' . $this->data['karbo_address'];
    $this->data['karbo_link'] .= '?amount=' . $this->data['text_total'];
    $this->data['karbo_link'] .= '&payment_id=' . $this->data['karbo_payment_id'];
    $this->data['karbo_link'] .= '&label=' . $this->data['text_store_name'];

    $karbo_qr_data  = 'karbowanec:' . $this->data['karbo_address'];
    $karbo_qr_data .= '?amount=' . $this->data['text_total'];
    $karbo_qr_data .= '&payment_id=' . $this->data['karbo_payment_id'];
    $this->data['karbo_qr_link']  = 'https://chart.googleapis.com/chart?cht=qr';
    $this->data['karbo_qr_link'] .= '&chl=' . urlencode($karbo_qr_data);
    $this->data['karbo_qr_link'] .= '&chs=200x200&choe=UTF=8&chld=L';

    if ($order_trig){
      unset ($this->session->data['karbo_wallet_address']);
      unset ($this->session->data['karbo_payment_id']);
      unset ($this->session->data['karbo_order_id']);
      unset ($this->session->data['karbo_total']);
      unset ($this->session->data['karbo_store_name']);
      $this->redirect($this->url->link('common/home'));
    }

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/karbo.tpl')){
      $this->template = $this->config->get('config_template') . '/template/checkout/karbo.tpl';
      } else {
      $this->template = 'default/template/checkout/karbo.tpl';
    }

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/karbo.css')){
      $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/karbo.css');
      } else {
      $this->document->addStyle('catalog/view/theme/default/stylesheet/karbo.css');
    }

    $this->document->addScript('catalog/view/javascript/karbo.js');

    $this->children = array(
      'common/column_left',
      'common/column_right',
      'common/content_top',
      'common/content_bottom',
      'common/footer',
      'common/header'			
    );

    $this->response->setOutput($this->render());
  }

}

?>
