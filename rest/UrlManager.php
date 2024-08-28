<?php

namespace app\rest; 

class UrlManager extends \yii\web\UrlManager {

  public $enablePrettyUrl = true;

  public $showScriptName = false;

  public $enableStrictParsing = true;

  // public $ruleConfig = ['class' => 'eDesarrollos\rest\UrlRule'];

  public $rules = [
    'OPTIONS <module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>.<formato:(json|xml|csv|html|sql)>' => '<module>/<controller>/options',
    'OPTIONS <module:[\w-]+>/<controller:[\w-]+>.<formato:(json|xml|csv|html|sql)>' => '<module>/<controller>/options',
    'GET <module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>.<formato:(json|xml|csv|html|sql)>' => '<module>/<controller>/<action>',
    'GET <module:[\w-]+>/<controller:[\w-]+>.<formato:(json|xm|csvl|html|sql)>' => '<module>/<controller>',
    'POST <module:[\w-]+>/<controller:[\w-]+>.<formato:(json|xml|csv|html|sql)>' => '<module>/<controller>/guardar',
    'PUT <module:[\w-]+>/<controller:[\w-]+>.<formato:(json|xml|csv|html|sql)>' => '<module>/<controller>/guardar',
    'DELETE <module:[\w-]+>/<controller:[\w-]+>.<formato:(json|xml|csv|html|sql)>' => '<module>/<controller>/eliminar',
    'GET <controller:[\w-]+>/<action:[\w-]+>.<formato:(pdf)>' => 'pdf/<controller>/<action>',
    'GET <controller:[\w-]+>.<formato:(pdf)>' => 'pdf/<controller>',
    'GET <controller:[\w-]+>/<action:[\w-]+>.<formato:(xlsx)>' => 'excel/<controller>/<action>',
    'GET <controller:[\w-]+>.<formato:(xlsx)>' => 'excel/<controller>',
    'GET <controller:[\w-]+>/<action:[\w-]+>.<formato:(word)>' => 'word/<controller>/<action>',
    'GET <controller:[\w-]+>.<formato:(word)>' => 'word/<controller>',
  ];

}