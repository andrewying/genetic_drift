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
    'mainteinance_status' => false
  );
}
