<?php

use yii\db\Migration;

/**
 * Class m241205_043326_agregando_propiedad_nombre_a_resultadoFormularioValor
 */
class m241205_043326_agregando_propiedad_nombre_a_resultadoFormularioValor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ResultadoFormularioValor', 'etiqueta', $this->string(255)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ResultadoFormularioValor', 'etiqueta');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241205_043326_agregando_propiedad_nombre_a_resultadoFormularioValor cannot be reverted.\n";

        return false;
    }
    */
}
