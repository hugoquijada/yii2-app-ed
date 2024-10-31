<?php

use yii\db\Migration;

/**
 * Class m240918_003321_agregando_tabla_refresh_token
 */
class m240918_003321_agregando_tabla_refresh_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("RefreshTokenUsuario", [
            "token" => $this->string(36)->notNull(),
            "idUsuario" => $this->string(36),
            "expiracion" => $this->timestamp()->append("with time zone")->comment("Fecha en que el token expira"),
            "creado" => $this->timestamp()->append("with time zone")->comment("Fecha de creacion del token"),
            "eliminado" => $this->timestamp()->append("with time zone")->comment("Eliminar validez del token"),
        ]);
        $this->addPrimaryKey("RefreshTokenUsuarioPK", "RefreshTokenUsuario", "token");
        $this->addForeignKey("RefreshTokenUsuarioIdUsuarioFK", "RefreshTokenUsuario", "idUsuario", "Usuario", "id");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("RefreshTokenUsuarioIdUsuarioFK", "RefreshTokenUsuario");
        $this->dropTable("RefreshTokenUsuario");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240918_003321_agregando_tabla_refresh_token cannot be reverted.\n";

        return false;
    }
    */
}
