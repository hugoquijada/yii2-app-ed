<?php

use yii\db\Migration;

/**
 * Class m250425_022807_creando_crud_de_formularios
 */
class m250425_022807_creando_crud_de_formularios extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        /**
         * export interface FilaProps { 
         *  rowProps?: RowProps;
         *   items: ColumnaProps[];
         *   gutter?: [number, number];
         * }
         * 
         * export interface ColumnaProps {
         *  columnProps?: ColProps;
         *  span?: number;
         *  xs?: number;
         *  sm?: number;
         *  md?: number;
         *  lg?: number;
         *  xl?: number;
         *  xxl?: number;
         *  formItem: IFormRendererItem;
         * }
         * 
         * export type FormRendererItemType = 
         * "input-texto" | "input-clave" | "input-selector" | "selector-query" |
         * "boton-submit" //TODO: AGREGAR LOS DEMAS TIPOS DE ITEMS
         * 
         * export interface IFormRendererItem {
         *  type: FormRendererItemType;
         *  props?: any;
         *  isFormLoading?: boolean;
         * }
         */
        $this->createTable('Formulario', [
            'id' => $this->string(50)->notNull(),
            'nombre' => $this->string(255)->notNull(),
            'endpoint' => $this->string(255), //Endpoint al que se hará el post de los datos del formulario
            'items' => $this->json(), //Array de FilaProps > ColumnaProps > IFormRendererItem en el frontend
            'creado' => $this->timestamp()->append("with time zone"),
            'modificado' => $this->timestamp()->append("with time zone"),
            'eliminado' => $this->timestamp()->append("with time zone"),
        ]);

        $this->addPrimaryKey('pk_formulario', 'Formulario', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('Formulario');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250425_022807_creando_crud_de_formularios cannot be reverted.\n";

        return false;
    }
    */
}
