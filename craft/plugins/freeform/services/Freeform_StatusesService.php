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

use Solspace\Freeform\Library\Database\StatusHandlerInterface;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;

class Freeform_StatusesService extends BaseApplicationComponent implements StatusHandlerInterface
{
    /** @var Freeform_StatusModel[] */
    private static $statusCache;
    private static $allStatusesLoaded;

    /**
     * Get the ID of the default status
     *
     * @return int
     */
    public function getDefaultStatusId()
    {
        $id = craft()
            ->db
            ->createCommand()
            ->select('id')
            ->from('freeform_statuses')
            ->where('isDefault = 1')
            ->queryScalar();

        return (int)$id;
    }

    /**
     * @param bool $indexById
     *
     * @return Freeform_StatusModel[]
     */
    public function getAllStatuses($indexById = true)
    {
        if (null === self::$statusCache || !self::$allStatusesLoaded) {
            $statusRecords     = Freeform_StatusRecord::model()->ordered()->findAll();
            self::$statusCache = Freeform_StatusModel::populateModels($statusRecords, $indexById ? 'id' : null);

            self::$allStatusesLoaded = true;
        }

        return self::$statusCache;
    }

    /**
     * @param bool $indexById
     *
     * @return array
     */
    public function getAllStatusNames($indexById = true)
    {
        $list = [];
        foreach ($this->getAllStatuses() as $status) {
            if ($indexById) {
                $list[$status->id] = $status->name;
            } else {
                $list[] = $status->name;
            }
        }

        return $list;
    }

    /**
     * Returns an array of status ID's
     *
     * @return array
     */
    public function getAllStatusIds()
    {
        return craft()
            ->db
            ->createCommand()
            ->select('id')
            ->from('freeform_statuses')
            ->order('name ASC')
            ->queryColumn();
    }

    /**
     * @param int $id
     *
     * @return Freeform_StatusModel|null
     */
    public function getStatusById($id)
    {
        if (null === self::$statusCache || !isset(self::$statusCache[$id])) {
            if (null === self::$statusCache) {
                self::$statusCache = [];
            }

            $statusRecord = Freeform_StatusRecord::model()->findById($id);

            self::$statusCache[$id] = $statusRecord ? Freeform_StatusModel::populateModel($statusRecord) : null;
        }

        return self::$statusCache[$id];
    }

    /**
     * @param Freeform_StatusModel $model
     *
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public function save(Freeform_StatusModel $model)
    {
        $isNewStatus = !$model->id;

        if (!$isNewStatus) {
            $record = Freeform_StatusRecord::model()->findById($model->id);

            if (!$record) {
                throw new Exception(Craft::t('Status with ID {id} not found', ['id' => $model->id]));
            }
        } else {
            $record = new Freeform_StatusRecord();
        }

        $beforeSaveEvent = $this->onBeforeSave($model, $isNewStatus);

        $record->name      = $model->name;
        $record->handle    = $model->handle;
        $record->color     = $model->color;
        $record->isDefault = $model->isDefault;
        $record->sortOrder = $model->sortOrder;

        $record->validate();
        $model->addErrors($record->getErrors());

        if ($beforeSaveEvent->performAction && !$model->hasErrors()) {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try {
                $record->save(false);

                if (!$model->id) {
                    $model->id = $record->id;
                }

                self::$statusCache[$model->id] = $model;

                if ($transaction !== null) {
                    $transaction->commit();
                }

                // Force other default statuses to be turned off
                if ($record->isDefault) {
                    craft()
                        ->db
                        ->createCommand()
                        ->update('freeform_statuses', ['isDefault' => 0], 'id != :id', ['id' => $record->id]);
                }

                $this->onAfterSave($model, $isNewStatus);

                return true;
            } catch (\Exception $e) {
                if ($transaction !== null) {
                    $transaction->rollback();
                }

                throw $e;
            }
        }

        return false;
    }

    /**
     * @param int $statusId
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteById($statusId)
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SETTINGS_ACCESS);

        $statusModel = $this->getStatusById($statusId);

        if (!$statusModel) {
            return false;
        }

        if (!$this->onBeforeDelete($statusModel)->performAction) {
            return false;
        }

        if ($statusModel->isDefault) {
            return false;
        }

        $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
        try {
            craft()->freeform_submissions->swapStatuses($statusModel->id, $this->getDefaultStatusId());

            $affectedRows = craft()->db
                ->createCommand()
                ->delete('freeform_statuses', ['id' => $statusModel->id]);

            if ($transaction !== null) {
                $transaction->commit();
            }

            craft()->freeform_forms->swapDeletedStatusToDefault($statusModel->id, $this->getDefaultStatusId());

            $this->onAfterDelete($statusModel);

            return (bool)$affectedRows;
        } catch (\Exception $exception) {
            if ($transaction !== null) {
                $transaction->rollback();
            }

            throw $exception;
        }
    }

    /**
     * @return int
     */
    public function getNextSortOrder()
    {
        $maxSortOrder = craft()
            ->db
            ->createCommand()
            ->select('MAX(sortOrder)')
            ->from('freeform_statuses')
            ->queryScalar();

        return (int)$maxSortOrder + 1;
    }

    /**
     * @param Freeform_StatusModel $model
     * @param bool                 $isNew
     *
     * @return Event
     */
    private function onBeforeSave(Freeform_StatusModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_StatusModel $model
     * @param bool                 $isNew
     *
     * @return Event
     */
    private function onAfterSave(Freeform_StatusModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_StatusModel $model
     *
     * @return Event
     */
    private function onBeforeDelete(Freeform_StatusModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_DELETE, $event);

        return $event;
    }

    /**
     * @param Freeform_StatusModel $model
     *
     * @return Event
     */
    private function onAfterDelete(Freeform_StatusModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_DELETE, $event);

        return $event;
    }
}
