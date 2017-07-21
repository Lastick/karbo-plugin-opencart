<?php

class ModelPaymentKarbo extends Model {

  public function getMethod($address, $total){
    $this->language->load('payment/karbo');

    $query = NULL;
    $sql_q = "";
    $sql_q  = "SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE";
    $sql_q .= " `geo_zone_id` = " . (int)$this->config->get('karbo_geo_zone_id') . " AND";
    $sql_q .= " `country_id` = " . (int)$address['country_id'] . " AND";
    $sql_q .= " (`zone_id` = " . (int)$address['zone_id'] . " OR zone_id = 0)";
    $query = $this->db->query($sql_q)->rows;

    if ($total > 0 and ($this->config->get('karbo_geo_zone_id') == 0 or count($query) == 1)){
      $status = true;
      } else {
      $status = false;
    }

    $method_data = array();

    if ($status){
      $method_data = array(
        'code' => 'karbo',
        'title' => $this->language->get('text_title'),
        'sort_order' => $this->config->get('karbo_sort_order')
      );
    }

    return $method_data;
  }

  public function addKarboOrder($order_id, $payment_id){
    $query = NULL;
    $sql_q = "";
    $sql_q  = "INSERT INTO `" . DB_PREFIX . "order_karbo` (`order_id`, `payment_id`)";
    $sql_q .= " VALUES (" . (int)$order_id . ", '" . $payment_id . "')";
    $this->db->query($sql_q);
    return true;
  }

  public function getKarboPaymentId($order_id){
    $query = NULL;
    $sql_q = "";
    $sql_q  = "SELECT `payment_id` FROM `" . DB_PREFIX . "order_karbo` WHERE";
    $sql_q .= " `order_id` = " . (int)$order_id;
    $query = $this->db->query($sql_q)->rows;
    $payment_id = '';
    if (count($query) == 1){
      $status = true;
      foreach ($query as $result){
        $payment_id = $result['payment_id'];
      }
    }
    return $payment_id;
  }

  public function KarboConfirmPayment(){
    $this->load->library('karbo');
    $this->load->model('checkout/order');
    $karbo = new Karbo($this->config->get('karbo_wallet_host'),
                       $this->config->get('karbo_wallet_port'),
                       $this->config->get('karbo_wallet_ssl'));

    $query = NULL;
    $sql_q = "";
    $sql_q  = "SELECT `order_id` FROM `" . DB_PREFIX . "order`";
    $sql_q .= " WHERE `payment_code` = 'karbo' AND";
    $sql_q .= " `order_status_id` = " . (int)$this->config->get('karbo_order_status_id');
    $query = $this->db->query($sql_q)->rows;
    $data = array();
    $r_n = 0;
    $logger = new Log('Karbo.log');
    if (count($query) > 0){
      foreach ($query as $result){
        if (isset($result['order_id'])){
          $order_id = $result['order_id'];
          usleep(50000);
          $payment_id = $this->getKarboPaymentId($order_id);
          if ($payment_id != ''){
            $payinfo = $karbo->getStatusPayment($payment_id);
            $logger->write('Payment candidate: order - ' . $order_id . ', payment_id - ' . $payment_id);
            if (is_array($payinfo)){
              if ($payinfo['status']){
                $order_info = $this->model_checkout_order->getOrder($order_id);
                if ($payinfo['amount'] >= $order_info['total']){
                  $this->model_checkout_order->update($order_id, $this->config->get('karbo_order_payment_status_id'));
                  $data[$r_n]['order_id'] = $order_id;
                  $data[$r_n]['order_id'] = $payment_id;
                  $logger->write('Payment confirm: order - ' . $order_id . ', payment_id - ' . $payment_id);
                  $r_n++;
                  } else {
                  $logger->write('Payment error: order - ' . $order_id . ', payment_id - ' . $payment_id);
                }
              }
            }
          }
        }
      }
    }
    return $data;
  }

}

?>
