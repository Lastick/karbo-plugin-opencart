<?php

class ControllerExtensionCheckoutKarbo extends Controller {

  public function index(){
    $order_trig = false;
    if (isset($this->session->data['order_id'])){
      $this->cart->clear();
      $order_trig = true;

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
    }
    //$order_trig = true;

    $this->load->language('extension/checkout/karbo');

    $this->document->setTitle($this->language->get('heading_title'));

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/home')
    );
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_basket'),
      'href' => $this->url->link('checkout/cart')
    );
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_checkout'),
      'href' => $this->url->link('checkout/checkout', '', true)
    );
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_payment'),
      'href' => $this->url->link('extension/checkout/karbo')
    );

    $data['karbo_payment_id'] = '';
    $data['karbo_address'] = '';
    $data['text_total'] = '';
    $data['text_store_name'] = '';
    $data['karbo_link'] = '';
    $data['karbo_qr_link'] = '';

    if ($this->customer->isLogged()){
    }

    if (isset($this->session->data['karbo_payment_id'])){
      $data['karbo_payment_id'] = $this->session->data['karbo_payment_id'];
    }
    if (isset($this->session->data['karbo_wallet_address'])){
      $data['karbo_address'] = $this->session->data['karbo_wallet_address'];
    }
    if (isset($this->session->data['karbo_total'])){
      $data['text_total'] = strval($this->session->data['karbo_total']);
      $data['text_total_ext'] = sprintf("%01.4f", ($this->session->data['karbo_total']));
    }
    if (isset($this->session->data['karbo_store_name'])){
      $data['text_store_name'] = $this->session->data['karbo_store_name'];
    }
	
    $data['karbo_link']  = 'karbowanec:' . $data['karbo_address'];
    $data['karbo_link'] .= '?amount=' . $data['text_total'];
    $data['karbo_link'] .= '&payment_id=' . $data['karbo_payment_id'];
    $data['karbo_link'] .= '&label=' . $data['text_store_name'];

    $karbo_qr_data  = 'karbowanec:' . $data['karbo_address'];
    $karbo_qr_data .= '?amount=' . $data['text_total'];
    $karbo_qr_data .= '&payment_id=' . $data['karbo_payment_id'];
    $data['karbo_qr_link']  = 'https://chart.googleapis.com/chart?cht=qr';
    $data['karbo_qr_link'] .= '&chl=' . urlencode($karbo_qr_data);
    $data['karbo_qr_link'] .= '&chs=201x201&choe=UTF=8&chld=L';

    if ($order_trig){
      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/karbo.css')){
        $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/karbo.css');
        } else {
        $this->document->addStyle('catalog/view/theme/default/stylesheet/karbo.css');
      }
      $this->document->addScript('catalog/view/javascript/karbo.js');
      $data['column_left'] = $this->load->controller('common/column_left');
      $data['column_right'] = $this->load->controller('common/column_right');
      $data['content_top'] = $this->load->controller('common/content_top');
      $data['content_bottom'] = $this->load->controller('common/content_bottom');
      $data['footer'] = $this->load->controller('common/footer');
      $data['header'] = $this->load->controller('common/header');
      $data['continue'] = $this->url->link('extension/checkout/karbo');
      $this->response->setOutput($this->load->view('extension/checkout/karbo', $data));
      } else {
      unset ($this->session->data['karbo_wallet_address']);
      unset ($this->session->data['karbo_payment_id']);
      unset ($this->session->data['karbo_order_id']);
      unset ($this->session->data['karbo_total']);
      unset ($this->session->data['karbo_store_name']);
      $this->response->redirect($this->url->link('common/home'));
    }
  }

}

?>
