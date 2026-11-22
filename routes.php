<?php

namespace Settings\Routes;

use SplitPHP\Request;
use SplitPHP\WebService;
use SplitPHP\Exceptions\Unauthorized;
use SplitPHP\ModsManager;

class Settings extends WebService
{
  private const CUSTOMFIELD_PERMISSIONS = [
    'read'    => 'customfields.customfield.read',
    'manage'  => 'customfields.customfield.manage'
  ];

  public function init()
  {
    $this->setAntiXsrfValidation(false);

    /////////////////
    // CUSTOM FIELDS ENDPOINTS:
    /////////////////
    $this->addEndpoint('GET', "/v1/field/?entityName?", function (Request $request) {
      $entityName = $request->getRoute()->params['entityName'];

      return $this->response
        ->withStatus(200)
        ->withData($this->getService('settings/customfield')->fieldsOfEntity($entityName));
    });

    $this->addEndpoint('POST', "/v1/field", function (Request $request) {
      $this->auth(self::CUSTOMFIELD_PERMISSIONS['manage']);

      $data = $request->getBody();
      return $this->response
        ->withStatus(201)
        ->withData($this->getService('settings/customfield')->createField($data));
    }, true);

    $this->addEndpoint('DELETE', "/v1/field/?entityName?/?fieldName?", function (Request $request) {
      $this->auth(self::CUSTOMFIELD_PERMISSIONS['manage']);

      $entityName = $request->getRoute()->params['entityName'];
      $fieldName = $request->getRoute()->params['fieldName'];

      $deleted = $this->getService('settings/customfield')->deleteField($entityName, $fieldName);

      if (!$deleted) return $this->response->withStatus(404);

      return $this->response
        ->withStatus(204);
    }, true);
  }

  private function auth(array|string $permissions)
  {
    if (!ModsManager::modExists('iam')) return;

    // Auth user login:
    if (!$this->getService('iam/session')->authenticate())
      throw new Unauthorized("Não autorizado.");

    // Validate user permissions:
    $this->getService('iam/permission')->requireAny($permissions);
  }
}
