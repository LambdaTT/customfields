<?php

namespace Settings\Seeds;

use SplitPHP\DbManager\Seed;
use SplitPHP\ModsManager;

class CustomfieldsPermissions extends Seed
{
  public function apply()
  {
    if (!ModsManager::modExists('iam')) return;

    $this->SeedTable('IAM_PERMISSION', batchSize: 1)
      ->onField('ds_title')->setFixedValue('Ler Campos Personalizados')
      ->onField('ds_tag')->setFixedValue('customfields.customfield.read')
    ;

    $this->SeedTable('IAM_PERMISSION', batchSize: 1)
      ->onField('ds_title')->setFixedValue('Gerenciar Campos Personalizados')
      ->onField('ds_tag')->setFixedValue('customfields.customfield.manage')
    ;
  }
}
