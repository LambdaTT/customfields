<?php

namespace Customfields\Migrations;

use SplitPHP\DbManager\Migration;
use SplitPHP\Database\DbVocab;

class CreateCustomFieldsTables extends Migration
{
  public function apply()
  {
    $this->Table('CST_CUSTOMFIELD', 'Campo Customizado')
      ->id('id_cst_customfield') // int primary key auto increment
      ->string('ds_entityname', 60)
      ->string('ds_fieldname', 60)
      ->string('ds_fieldlabel', 60)
      ->string('do_fieldtype', 1)->setDefaultValue('T')
      ->string('do_is_required', 1)->setDefaultValue('N')
      ->text('tx_rules')->nullable()->setDefaultValue(null);

    $this->Table('CST_CUSTOMFIELD_VALUE', 'Valor do Campo Customizado')
      ->id('id_cst_customfield_value') // int primary key auto increment
      ->int('id_cst_customfield')
      ->int('id_reference_entity')
      ->text('tx_value')
      ->Foreign('id_cst_customfield')
      ->references('id_cst_customfield')
      ->atTable('CST_CUSTOMFIELD')
      ->onUpdate(DbVocab::FKACTION_CASCADE)
      ->onDelete(DbVocab::FKACTION_CASCADE);
  }
}
