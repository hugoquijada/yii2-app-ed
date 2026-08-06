<?php

use yii\db\Migration;

class m260806_022518_correccion extends Migration {
  /**
   * {@inheritdoc}
   */
  public function safeUp() {
    $this->alterColumn("Media", "idUsuario", $this->string(36));
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown() {
    $this->alterColumn("Media", "idUsuario", $this->integer());
  }
}
