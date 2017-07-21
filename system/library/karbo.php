<?php

class Karbo {

  private $service_host = null;
  private $service_port = null;
  private $service_port_ssl = false;
  private $id_connection = '';

  const RPC_HOST = '127.0.0.1';
  const RPC_PORT = '15000';
  const DECIMAL_POINT = 12;
  const PREC_POINT = 4;
  const ID_CONN = 'EWF8aIFX0y9w';
  const KRB_FEE = 100000000;
  const KRB_MIXIN = 0;
  const TX_CONF = 6;


  public function __construct($rpc_host = '', $rpc_port = '', $rpc_ssl = false, $id_connection = ''){

    $this->service_host = self::RPC_HOST;
    $this->service_port = self::RPC_PORT;
    $this->id_connection = self::ID_CONN;
    $this->tx_conf = self::TX_CONF;
    if ($rpc_host != "" and $rpc_port != ""){
      $this->service_host = $rpc_host;
      $this->service_port = $rpc_port;
    };
    if ($rpc_ssl) $this->service_port_ssl = true;
    if ($id_connection != '') $this->id_connection = $id_connection;

    return true;
  }

  private function apiCall($req){
    static $ch = null;
    if ($this->service_port_ssl){
      $url = 'https://';
      } else {
      $url = 'http://';
    }
    $url .= $this->service_host . ':' . $this->service_port . '/json_rpc';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($req));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $res = curl_exec($ch);
    if(curl_errno($ch) > 0){
      curl_close($ch);
      //echo 'error connection';
      return false;
      } else {
      curl_close($ch);
      $result = json_decode($res, true);
      //print_r($result);
      if($result != NULL){
        if(!isset($result['error'])){
          return $result;
        }
      }
      //echo 'internal error';
      return false;
    }
  }

  private function BalanceFormat($balance_src, $mode){
    $balance_res = 0;
    if ($balance_src > 0){
      if ($mode){
        $balance_res = round($balance_src * pow(10, self::DECIMAL_POINT), 0);
        } else {
        $balance_res = round($balance_src / pow(10, self::DECIMAL_POINT), self::PREC_POINT);
      }
    }
    return $balance_res;
  }

  public function getHeight(){
    $args = array();
    $args['jsonrpc'] = '2.0';
    $args['id'] = $this->id_connection;
    $args['method'] = 'get_height';
    $result = array();
    $result['height'] = 0;
    $data = $this->apiCall($args);
    //print_r($data);
    if (!$data === false){
      if (isset($data['id'])){
        if ($data['id'] == $this->id_connection){
          if (isset($data['result']['height'])){
            if ($data['result']['height'] > 0){
              $result['height'] = $data['result']['height'];
            }
          }
          return $result;
        }
      }
    }
    return false;
  }

  public function getBalance(){
    $args = array();
    $args['jsonrpc'] = '2.0';
    $args['id'] = $this->id_connection;
    $args['method'] = 'getbalance';
    $result = array();
    $result['available_balance'] = 0;
    $result['locked_amount'] = 0;
    $data = $this->apiCall($args);
    //print_r($data);
    if (!$data === false){
      if (isset($data['id'])){
        if ($data['id'] == $this->id_connection){
          if (isset($data['result']['available_balance'])){
            $result['available_balance'] = $this->BalanceFormat($data['result']['available_balance'], false);
          }
          if (isset($data['result']['locked_amount'])){
            $result['locked_amount'] = $this->BalanceFormat($data['result']['locked_amount'], false);
          }
          return $result;
        }
      }
    }
    return false;
  }

  public function doTransfers($transfers, $payment_id = ''){
    $args = array();
    $result = array();
    $args['jsonrpc'] = '2.0';
    $args['id'] = $this->id_connection;
    $args['method'] = 'transfer';
    for ($i = 0; $i < count($transfers); $i++){
      $transfers[$i]['amount'] = $this->BalanceFormat($transfers[$i]['amount'], true);
    }
    $args['params']['destinations'] = $transfers;
    if ($payment_id == ''){
      $args['params']['payment_id'] = $this->genPaymentId();
      } else {
      $args['params']['payment_id'] = $payment_id;
    }
    $args['params']['fee'] = self::KRB_FEE;
    $args['params']['mixin'] = self::KRB_MIXIN;
    $args['params']['unlock_time'] = 0;
    //print_r($args);
    $data = $this->apiCall($args);
    //print_r($data);
    if (!$data === false){
      if (isset($data['id'])){
        if ($data['id'] == $this->id_connection){
          if (isset($data['result']['tx_hash'])){
            $result['tx_hash'] = $data['result']['tx_hash'];
            $result['payment_id'] = $args['params']['payment_id'];
            return $result;
          }
        }
      }
    }
    return false;
  }

  public function getPayments($payment_id){
    $args = array();
    $args['jsonrpc'] = '2.0';
    $args['id'] = $this->id_connection;
    $args['method'] = 'get_payments';
    $args['params']['payment_id'] = $payment_id;
    $payments = array();
    $data = $this->apiCall($args);
    //print_r($data['result']['payments']);
    if (!$data === false){
      if (isset($data['id'])){
        if ($data['id'] == $this->id_connection){
          if (isset($data['result']['payments'])){
            $payments = $data['result']['payments'];
            return $payments;
          }
        }
      }
    }
    return false;
  }

  public function getTransfers(){
    $args = array();
    $args['jsonrpc'] = '2.0';
    $args['id'] = $this->id_connection;
    $args['method'] = 'get_transfers';
    $transfers = array();
    $data = $this->apiCall($args);
    //print_r($data);
    if (!$data === false){
      if (isset($data['id'])){
        if ($data['id'] == $this->id_connection){
          if (isset($data['result']['transfers'])){
            $transfers = $data['result']['transfers'];
            return $transfers;
          }
        }
      }
    }
    return false;
  }

  public function getStatus(){
    $data = array();
    $result = array();
    $result['status'] = false;
    $result['height'] = 0;
    $data = $this->getHeight();
    if ($data !== false){
      $result['status'] = true;
      $result['height'] = $data['height'];
    }
    return $result;
  }

  public function getStatusPayment($payment_id){
    $result = array();
    $data_status = $this->getStatus();
    //print_r($data_status);
    usleep(50000);
    $data_payments = $this->getPayments($payment_id);
    //print_r($data_payments);
    if ($data_status['status'] != false && $data_payments !== false){
      if (count($data_payments) == 1){
        $data_payment = $data_payments[0];
        if (isset($data_status['height']) && isset($data_payment['block_height'])){
          if ($data_status['height'] - $data_payment['block_height'] > $this->tx_conf){
            $result['status'] = true;
            $result['status_txt'] = 'Confirmed';
            } else {
            $result['status'] = false;
            $result['status_txt'] = 'Unconfirmed';
          }
          $result['amount'] = $this->BalanceFormat($data_payment['amount'], false);
          $result['tx_hash'] = $data_payment['tx_hash'];
          $result['payment_id'] = $payment_id;
          return $result;
        }
      }
    }
    return false;
  }

  public static function genPaymentId(){
    $buff = '';
    $buff = bin2hex(openssl_random_pseudo_bytes(32));
    return $buff;
  }

}

?>
