<?php

namespace Geneticdrift;

class Config
{
  public $config = array(
    'redis_server' => array(
      'server' => 'localhost',
      'port' => 6379
    ),
    'web_root' => '/home/admin/web/apps.andrewying.com/public_html',
    'admin_email' => 'me@andrewying.com',
    'maintenance_status' => false,
    'automatic_update' => false,
    'update' => array(
      'method' => 'github',
      'token' => 'f2a097a3ddfd67ada833310de270003a26759d31'
    )
  );

  public function appStatus() {
    if ($this->config['maintenance_status']) {
      return $this->checkIp();
    }
    else {
      return true;
    }
  }

  private function checkIp() {
    $whitelist = file_get_contents($this->config['web_root'] . '/config/whitelist.json');

    if (!$whitelist || empty($whitelist)) {
      return false;
    }
    else {
      $array = json_decode($whitelist, TRUE);
      $ipAddress = $this->getUserIpAddr();
      if (isset($array[$ipAddress])) {
        return true;
      }
      else {
        return false;
      }
    }
  }

  private function getUserIpAddr() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
      return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      return $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
      return $_SERVER['REMOTE_ADDR'];
    }
  }

}
