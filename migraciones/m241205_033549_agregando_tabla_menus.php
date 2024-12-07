<?php

use yii\db\Migration;

/**
 * Class m241205_033549_agregando_tabla_menus
 */
class m241205_033549_agregando_tabla_menus extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('Menu', [
            'id' => $this->string(50)->notNull(),
            'idPadre' => $this->string(50)->notNull(),
            
            'nombre' => $this->string(255)->notNull(),
            'urlAbsoluta' => $this->string(255)->notNull(),
            'icono' => $this->string(255),
            'orden' => $this->integer()->notNull()->defaultValue(0),

            'creado' => $this->timestamp()->append("with time zone"),
            'modificado' => $this->timestamp()->append("with time zone"),
            'eliminado' => $this->timestamp()->append("with time zone"),
        ]);

        $this->createTable('MenuUsuario', [
            'id' => $this->string(50)->notNull(),
            'idMenu' => $this->string(50)->notNull(),
            'idUsuario' => $this->string(50)->notNull(),

            'asignado' => $this->timestamp()->append("with time zone"),
            'creado' => $this->timestamp()->append("with time zone"),
            'modificado' => $this->timestamp()->append("with time zone"),
            'eliminado' => $this->timestamp()->append("with time zone"),
        ]);

        //llaves primarias
        $this->addPrimaryKey("MenuPK", "Menu", "id");
        $this->addPrimaryKey("MenuUsuarioPK", "MenuUsuario", "id");

        //llaves foraneas Menu
        $this->addForeignKey("MenuIdPadreFK", "Menu", "idPadre", "Menu", "id");

        //llaves foraneas MenuUsuario
        $this->addForeignKey("MenuUsuarioIdMenuFK", "MenuUsuario", "idMenu", "Menu", "id");
        $this->addForeignKey("MenuUsuarioIdUsuarioFK", "MenuUsuario", "idUsuario", "Usuario", "id");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("MenuIdPadreFK", "Menu");
        $this->dropForeignKey("MenuUsuarioIdMenuFK", "MenuUsuario");
        $this->dropForeignKey("MenuUsuarioIdUsuarioFK", "MenuUsuario");

        $this->dropTable("MenuUsuario");
        $this->dropTable("Menu");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241205_033549_agregando_tabla_menus cannot be reverted.\n";

        return false;
    }
    */
}
