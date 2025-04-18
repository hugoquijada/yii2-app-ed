<?php

use yii\db\Migration;

/**
 * Class m250417_221340_agregando_tabla_rutas
 */
class m250417_221340_agregando_tabla_rutas extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('Ruta', [
            'id' => $this->string(50)->notNull(),
            'idPadre' => $this->string(50),

            'nombre' => $this->string(255)->notNull(),
            'urlAbsoluta' => $this->string(255)->notNull(),
            'icono' => $this->string(255),
            'orden' => $this->integer(),
            'visible' => $this->boolean()->notNull()->defaultValue(true)->comment("Indica si la ruta debe ser visible en el menu lateral"),
            'creado' => $this->timestamp()->append("with time zone"),
            'modificado' => $this->timestamp()->append("with time zone"),
            'eliminado' => $this->timestamp()->append("with time zone"),
        ]);

        $this->createTable('RutaUsuario', [
            'id' => $this->string(50)->notNull(),
            'idRuta' => $this->string(50)->notNull(),
            'idUsuario' => $this->string(50)->notNull(),

            'asignado' => $this->timestamp()->append("with time zone"),
            'creado' => $this->timestamp()->append("with time zone"),
            'modificado' => $this->timestamp()->append("with time zone"),
            'eliminado' => $this->timestamp()->append("with time zone"),
        ]);

        //llaves primarias
        $this->addPrimaryKey("RutaPK", "Ruta", "id");   
        $this->addPrimaryKey("RutaUsuarioPK", "RutaUsuario", "id");

        //llaves foraneas Ruta
        $this->addForeignKey("RutaIdPadreFK", "Ruta", "idPadre", "Ruta", "id");

        //llaves foraneas RutaUsuario
        $this->addForeignKey("RutaUsuarioIdRutaFK", "RutaUsuario", "idRuta", "Ruta", "id");
        $this->addForeignKey("RutaUsuarioIdUsuarioFK", "RutaUsuario", "idUsuario", "Usuario", "id");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("RutaIdPadreFK", "Ruta");
        $this->dropForeignKey("RutaUsuarioIdRutaFK", "RutaUsuario");
        $this->dropForeignKey("RutaUsuarioIdUsuarioFK", "RutaUsuario");

        $this->dropTable("RutaUsuario");
        $this->dropTable("Ruta");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250417_221340_agregando_tabla_rutas cannot be reverted.\n";

        return false;
    }
    */
}
