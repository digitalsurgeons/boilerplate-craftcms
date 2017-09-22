<?php
/**
 * Freeform for Craft
 *
 * @package       Solspace:Freeform
 * @author        Solspace, Inc.
 * @copyright     Copyright (c) 2008-2017, Solspace, Inc.
 * @link          https://solspace.com/craft/freeform
 * @license       https://solspace.com/software/license-agreement
 */

namespace Craft;

use Solspace\Freeform\Library\Helpers\PermissionsHelper;

class Freeform_StatusesController extends Freeform_BaseController
{
    /**
     * @throws HttpException
     */
    public function actionIndex()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $statuses = $this->getStatusesService()->getAllStatuses();

        $this->renderTemplate('freeform/statuses', ['statuses' => $statuses]);
    }

    /**
     * @param array $variables
     *
     * @throws HttpException
     */
    public function actionEdit(array $variables = [])
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $statusService = $this->getStatusesService();

        if (empty($variables['status'])) {
            $statusId = isset($variables['statusId']) ? $variables['statusId'] : null;
            if ($statusId) {
                /** @var Freeform_StatusModel $status */
                $status = $statusService->getStatusById($statusId);

                if (!$status) {
                    throw new HttpException(404, Craft::t('Status with ID {id} not found', ['id' => $statusId]));
                }

                $title = $status->name;
            } else {
                $status = Freeform_StatusModel::create();
                $title  = Craft::t('Create a new status');
            }
        } else {
            /** @var Freeform_StatusModel $status */
            $status   = $variables['status'];
            $statusId = $status->id;
            $title    = $status->name;
        }

        $variables = array_merge(
            $variables,
            [
                'status'             => $status,
                'title'              => $title,
                'continueEditingUrl' => 'freeform/settings/statuses/{id}',
                'crumbs'             => [
                    ['label' => 'Freeform', 'url' => UrlHelper::getUrl('freeform')],
                    ['label' => Craft::t('Statuses'), 'url' => UrlHelper::getUrl('freeform/settings/statuses')],
                    [
                        'label' => $title,
                        'url'   => UrlHelper::getUrl(
                            'freeform/settings/statuses/' . ($statusId ? $statusId : 'new')
                        ),
                    ],
                ],
            ]
        );

        $this->renderTemplate('freeform/statuses/edit', $variables);
    }

    /**
     * @throws Exception
     */
    public function actionSave()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $post = craft()->request->getPost();

        $statusId = isset($post['statusId']) ? $post['statusId'] : null;
        $status   = $this->getNewOrExistingStatus($statusId);

        $status->setAttributes($post);

        if ($this->getStatusesService()->save($status)) {
            // Return JSON response if the request is an AJAX request
            if (craft()->request->isAjaxRequest()) {
                $this->returnJson(['success' => true]);
            } else {
                craft()->userSession->setNotice(Craft::t('Status saved'));
                craft()->userSession->setFlash(Craft::t('Status saved'), true);
                $this->redirectToPostedUrl($status);
            }
        } else {
            // Return JSON response if the request is an AJAX request
            if (craft()->request->isAjaxRequest()) {
                $this->returnJson(['success' => false]);
            } else {
                craft()->userSession->setError(Craft::t('Status not saved'));

                // Send the event back to the template
                craft()->urlManager->setRouteVariables(['status' => $status, 'errors' => $status->getErrors()]);
            }
        }
    }

    /**
     * @return string json
     */
    public function actionReorder()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $idList = JsonHelper::decode(craft()->request->getPost('ids', '[]'));

        try {
            $order = 1;
            foreach ($idList as $id) {
                craft()
                    ->db
                    ->createCommand()
                    ->update(
                        'freeform_statuses',
                        ['sortOrder' => $order++],
                        'id = :id',
                        ['id' => $id]
                    );
            }

            return $this->returnJson(['success' => true]);
        } catch (\Exception $e) {
            return $this->returnErrorJson($e->getMessage());
        }
    }

    /**
     * Deletes a field
     */
    public function actionDelete()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $statusId = craft()->request->getRequiredPost('id');

        $this->getStatusesService()->deleteById($statusId);
        $this->returnJson(['success' => true]);
    }

    /**
     * @param int $statusId
     *
     * @return Freeform_StatusModel
     * @throws Exception
     */
    private function getNewOrExistingStatus($statusId)
    {
        $statusService = $this->getStatusesService();

        if ($statusId) {
            $status = $statusService->getStatusById($statusId);

            if (!$status) {
                throw new Exception(Craft::t('Status with ID {id} not found', ['id' => $statusId]));
            }
        } else {
            $status            = Freeform_StatusModel::create();
            $status->sortOrder = $statusService->getNextSortOrder();
        }

        return $status;
    }
}
