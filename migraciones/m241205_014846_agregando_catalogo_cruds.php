<?php

use yii\db\Migration;

/**
 * Class m241205_014846_agregando_catalogo_cruds
 */
class m241205_014846_agregando_catalogo_cruds extends Migration
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

        $this->createTable('Propiedad', [
            "id" => $this->string(50)->notNull(),
            "idFormulario" => $this->string(50)->notNull(),
            "idTipoCampo" => $this->string(50)->notNull(),

            "nombre" => $this->string(255)->notNull(),
            "configuracion" => $this->json(),

            "requerido" => $this->boolean()->notNull()->defaultValue(false),
            "mostrar" => $this->boolean()->notNull()->defaultValue(false),

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

        $this->createTable('ResultadoFormulario', [
            "id" => $this->string(50)->notNull(),
            "idFormulario" => $this->string(50)->notNull(),

            "creado" => $this->timestamp()->append("with time zone"),
            "modificado" => $this->timestamp()->append("with time zone"),
            "eliminado" => $this->timestamp()->append("with time zone"),
        ]);

        $this->createTable('ResultadoFormularioValor',[
            "id" => $this->string(50)->notNull(),
            "idResultadoFormulario" => $this->string(50)->notNull(),
            "idPropiedad" => $this->string(50)->notNull(),

            "valor" => $this->string(1023),
            
            "creado" => $this->timestamp()->append("with time zone"),
            "modificado" => $this->timestamp()->append("with time zone"),
            "eliminado" => $this->timestamp()->append("with time zone"),
        ]);

        //llaves primarias
        $this->addPrimaryKey("FormularioPK", "Formulario", "id");
        $this->addPrimaryKey("PropiedadPK", "Propiedad", "id");
        $this->addPrimaryKey("PropiedadOpcionPK", "PropiedadOpcion", "id");
        $this->addPrimaryKey("TipoCampoPK", "TipoCampo", "id");
        $this->addPrimaryKey("ResultadoFormularioPK", "ResultadoFormulario", "id");
        $this->addPrimaryKey("ResultadoFormularioValorPK", "ResultadoFormularioValor", "id");

        //llaves foráneas de Propiedad
        $this->addForeignKey("PropiedadFKFormulario", "Propiedad", "idFormulario", "Formulario", "id");
        $this->addForeignKey("PropiedadFKTipoCampo", "Propiedad", "idTipoCampo", "TipoCampo", "id");

        //llaves foráneas de PropiedadOpcion
        $this->addForeignKey("PropiedadOpcionFKPropiedad", "PropiedadOpcion", "idPropiedad", "Propiedad", "id");

        //llaves foráneas de ResultadoFormulario
        $this->addForeignKey("ResultadoFormularioFKFormulario", "ResultadoFormulario", "idFormulario", "Formulario", "id");

        //llaves foráneas de ResultadoFormularioValor
        $this->addForeignKey("ResultadoFormularioValorFKResultadoFormulario", "ResultadoFormularioValor", "idResultadoFormulario", "ResultadoFormulario", "id");
        $this->addForeignKey("ResultadoFormularioValorFKPropiedad", "ResultadoFormularioValor", "idPropiedad", "Propiedad", "id");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //drop keys
        $this->dropForeignKey("PropiedadFKFormulario", "Propiedad");
        $this->dropForeignKey("PropiedadFKTipoCampo", "Propiedad");
        $this->dropForeignKey("PropiedadOpcionFKPropiedad", "PropiedadOpcion");
        $this->dropForeignKey("ResultadoFormularioFKFormulario", "ResultadoFormulario");
        $this->dropForeignKey("ResultadoFormularioValorFKResultadoFormulario", "ResultadoFormularioValor");
        $this->dropForeignKey("ResultadoFormularioValorFKPropiedad", "ResultadoFormularioValor");
        
        //drop tables
        $this->dropTable("Formulario");
        $this->dropTable("Propiedad");
        $this->dropTable("PropiedadOpcion");
        $this->dropTable("TipoCampo");
        $this->dropTable("ResultadoFormulario");
        $this->dropTable("ResultadoFormularioValor");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241205_014846_agregando_catalogo_cruds cannot be reverted.\n";

        return false;
    }
    */
}
