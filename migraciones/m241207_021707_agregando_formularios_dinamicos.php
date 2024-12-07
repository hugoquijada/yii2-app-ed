<?php

use yii\db\Migration;

class m241207_021707_agregando_formularios_dinamicos extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('Formulario', [
            "id" => $this->string(50)->notNull(),

            "nombre" => $this->string(255)->notNull(),
            "tabla" => $this->string(255)->notNull(),

            "creado" => $this->timestamp()->append("with time zone"),
            "modificado" => $this->timestamp()->append("with time zone"),
            "eliminado" => $this->timestamp()->append("with time zone"),
        ]);

        $this->createTable('PropiedadFormulario', [
            "id" => $this->string(50)->notNull(),
            "idFormulario" => $this->string(50)->notNull(),
            "idTipoCampo" => $this->string(50)->notNull(),

            "etiqueta"=> $this->string(255)->notNull(),
            "nombre" => $this->string(255)->notNull(),

            "requerido" => $this->boolean()->notNull()->defaultValue(false),

            "creado" => $this->timestamp()->append("with time zone"),
            "modificado" => $this->timestamp()->append("with time zone"),
            "eliminado" => $this->timestamp()->append("with time zone"),
        ]);

        $this->createTable('PropiedadOpcion', [
            "id" => $this->string(50)->notNull(),
            "idPropiedad" => $this->string(50)->notNull(),

            "valor" => $this->string(255)->notNull(),
            "etiqueta" => $this->string(255)->notNull(),

            "creado" => $this->timestamp()->append("with time zone"),
            "modificado" => $this->timestamp()->append("with time zone"),
            "eliminado" => $this->timestamp()->append("with time zone"),
        ]);

        $this->createTable('TipoCampo', [
            "id" => $this->string(50)->notNull(),

            "clave" => $this->string(255)->notNull()->unique(),
            "nombre" => $this->string(255)->notNull(),

            "creado" => $this->timestamp()->append("with time zone"),
            "modificado" => $this->timestamp()->append("with time zone"),
            "eliminado" => $this->timestamp()->append("with time zone"),
        ]);

        // agregar llaves primarias
        $this->addPrimaryKey("FormularioPK", "Formulario", "id");
        $this->addPrimaryKey("PropiedadFormularioPK", "PropiedadFormulario", "id");
        $this->addPrimaryKey("PropiedadOpcionPK", "PropiedadOpcion", "id");
        $this->addPrimaryKey("TipoCampoPK", "TipoCampo", "id");

        // llaves foraneas de propiedadformulario
        $this->addForeignKey("PropiedadFormularioIdFormularioFK", "PropiedadFormulario", "idFormulario", "Formulario", "id");
        $this->addForeignKey("PropiedadFormularioIdTipoCampoFK", "PropiedadFormulario", "idTipoCampo", "TipoCampo", "id");

        // llaves foraneas de propiedadopcion
        $this->addForeignKey("PropiedadOpcionIdPropiedadFK", "PropiedadOpcion", "idPropiedad", "PropiedadFormulario", "id");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("PropiedadFormularioIdFormularioFK", "PropiedadFormulario");
        $this->dropForeignKey("PropiedadFormularioIdTipoCampoFK", "PropiedadFormulario");
        $this->dropForeignKey("PropiedadOpcionIdPropiedadFK", "PropiedadOpcion");
        $this->dropTable("PropiedadFormulario");
        $this->dropTable("PropiedadOpcion");
        $this->dropTable("Formulario");
        $this->dropTable("TipoCampo");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241207_021707_agregando_formularios_dinamicos cannot be reverted.\n";

        return false;
    }
    */
}
