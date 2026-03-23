<?php

namespace app\comandos;

use yii\console\Controller;
use eDesarrollos\cron\Scheduler;
use Cron\CronExpression;

class CronController extends Controller {

  public function actionIndex() {
    $schedule = new Scheduler();

    $callback = require \Yii::getAlias('@app/config/crones.php');
    $callback($schedule);

    foreach ($schedule->getJobs() as $job) {
      $cron = new CronExpression($job->expression);

      if ($cron->isDue()) {
        exec("php yii {$job->command} > /dev/null 2>&1 &");
      }
    }
  }
}
