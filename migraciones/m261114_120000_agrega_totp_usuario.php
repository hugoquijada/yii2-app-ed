<?php

use yii\db\Migration;

class m261114_120000_agrega_totp_usuario extends Migration {

  public function safeUp() {
    $this->addColumn('Usuario', 'totpSemilla', $this->string(500));
    $this->addColumn('Usuario', 'totpActivo', $this->boolean()->notNull()->defaultValue(false));
  }

  public function safeDown() {
    $this->dropColumn('Usuario', 'totpActivo');
    $this->dropColumn('Usuario', 'totpSemilla');
  }
}
