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

use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\NoStorageInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\ObscureValueInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\StaticValueInterface;
use Solspace\Freeform\Library\Composer\Components\Form;
use Solspace\Freeform\Library\Database\SubmissionHandlerInterface;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;

class Freeform_SubmissionsService extends BaseApplicationComponent implements SubmissionHandlerInterface
{
    /** @var Freeform_SubmissionModel[] */
    private static $submissionCache = [];

    /**
     * @param int $id
     *
     * @return Freeform_SubmissionModel|null
     */
    public function getSubmissionById($id)
    {
        if (is_null(self::$submissionCache) || !isset(self::$submissionCache[$id])) {
            if (is_null(self::$submissionCache)) {
                self::$submissionCache = [];
            }

            $submissionRecord = Freeform_SubmissionRecord::model()->findById($id);
            $submissionModel  = null;
            if ($submissionRecord) {
                $submissionModel = Freeform_SubmissionModel::populateModel($submissionRecord);
            }

            self::$submissionCache[$id] = $submissionModel;
        }

        return self::$submissionCache[$id];
    }

    /**
     * @param array|null $formIds
     * @param array|null $statusIds
     *
     * @return int
     */
    public function getSubmissionCount(array $formIds = null, array $statusIds = null)
    {
        $command = craft()
            ->db
            ->createCommand()
            ->select("COUNT(id)")
            ->from(Freeform_SubmissionRecord::TABLE)
            ->where("1=1");


        if (!is_null($formIds)) {
            $command->andWhere(["in", "formId", $formIds]);
        }

        if (!is_null($statusIds)) {
            $command->andWhere(["in", "statusId", $statusIds]);
        }

        $submissionCount = (int)$command->queryScalar();

        return $submissionCount;
    }

    /**
     * Returns submission coun by form ID
     *
     * @return array
     */
    public function getSubmissionCountByForm()
    {
        $countList = craft()
            ->db
            ->createCommand()
            ->select("formId, COUNT(id) as submissionCount")
            ->from(Freeform_SubmissionRecord::TABLE)
            ->group("formId")
            ->queryAll();

        $submissionCountByForm = [];
        foreach ($countList as $data) {
            $submissionCountByForm[$data["formId"]] = (int)$data["submissionCount"];
        }

        return $submissionCountByForm;
    }

    /**
     * Stores the submitted fields to database
     *
     * @param Form  $form
     * @param array $fields
     *
     * @return Freeform_SubmissionModel|null
     */
    public function storeSubmission(Form $form, array $fields)
    {
        $savableFields = [];
        foreach ($fields as $field) {
            if ($field instanceof NoStorageInterface) {
                continue;
            }

            $columnName = Freeform_SubmissionRecord::getFieldColumnName($field->getId());
            $value      = $field->getValue();

            // Since the value is obfuscated, we have to get the real value
            if ($field instanceof ObscureValueInterface) {
                $value = $field->getActualValue($value);
            } else if ($field instanceof StaticValueInterface) {
                if (!empty($value)) {
                    $value = $field->getStaticValue();
                }
            }

            $savableFields[$columnName]             = $value;
            $titleReplacements[$field->getHandle()] = $value;
        }

        $titleReplacements["dateCreated"] = (new DateTime())->nice();

        $submission           = Freeform_SubmissionModel::create();
        $submission->statusId = $form->getDefaultStatus();
        $submission->formId   = $form->getId();
        $submission->setAttributes($savableFields);

        $fieldsByHandle = $form->getLayout()->getFieldsByHandle();

        $submission->getContent()->title = craft()->templates->renderString(
            $form->getSubmissionTitleFormat(),
            array_merge(
                $fieldsByHandle,
                [
                    "dateCreated" => new DateTime(),
                    "form"        => $form,
                ]
            )
        );

        if ($this->save($submission)) {
            $this->finalizeFormFiles($form);

            return $submission;
        }

        return null;
    }

    /**
     * Finalize all files uploaded in this form, so that they don' get deleted
     *
     * @param Form $form
     */
    public function finalizeFormFiles(Form $form)
    {
        $assetIds = [];

        foreach ($form->getLayout()->getFileUploadFields() as $field) {
            $assetIds[] = $field->getValue();
        }

        if (empty($assetIds)) {
            return;
        }

        $criteria = new \CDbCriteria();
        $criteria->addInCondition("assetId", $assetIds);

        $records = Freeform_UnfinalizedFileRecord::model()->findAllByAttributes([], $criteria);

        foreach ($records as $record) {
            $record->delete();
        }
    }

    /**
     * @param Freeform_SubmissionModel $submissionModel
     *
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public function save(Freeform_SubmissionModel $submissionModel)
    {
        $isNewSubmission = !$submissionModel->id;

        if (!$isNewSubmission) {
            $submissionRecord = Freeform_SubmissionRecord::model()->findById($submissionModel->id);

            if (!$submissionRecord) {
                throw new Exception(Craft::t("Submission with ID {id} not found", ["id" => $submissionModel->id]));
            }
        } else {
            $submissionRecord = new Freeform_SubmissionRecord();
        }

        $beforeSaveEvent = $this->onBeforeSave($submissionModel, $isNewSubmission);

        $this->validateAndUpdateStatus($submissionModel);

        $submissionAttributes = $submissionModel->getAttributes();
        unset($submissionAttributes["id"]);

        foreach ($submissionAttributes as $key => $value) {
            if (is_array($value)) {
                $value = array_unique($value);
            }

            $submissionRecord->setAttribute($key, $value);
        }

        $maxIncrementalId = $this->getMaxIncrementalId() + 1;

        $submissionModel->incrementalId  = $maxIncrementalId;
        $submissionRecord->incrementalId = $maxIncrementalId;

        $submissionRecord->validate();
        $submissionModel->addErrors($submissionRecord->getErrors());

        if ($beforeSaveEvent->performAction && !$submissionModel->hasErrors()) {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try {
                $isSaved = craft()->elements->saveElement($submissionModel);

                if ($isSaved) {
                    if ($isNewSubmission) {
                        $submissionRecord->id = $submissionModel->id;
                    }

                    $submissionRecord->save(false);

                    if ($transaction !== null) {
                        $transaction->commit();
                    }

                    $this->onAfterSave($submissionModel, $isNewSubmission);

                    return true;
                }
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
     * @param Freeform_SubmissionModel[] $submissions
     *
     * @return bool
     * @throws \CDbException
     * @throws \Exception
     */
    public function delete($submissions)
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SUBMISSIONS_MANAGE);

        if (!$submissions) {
            return false;
        }

        $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;

        try {
            if (!is_array($submissions)) {
                $submissions = [$submissions];
            }

            foreach ($submissions as $submission) {
                $event = $this->onBeforeDelete($submission);

                if ($event->performAction) {
                    craft()->elements->deleteElementById($submission->id);

                    $this->onAfterDelete($submission);
                }
            }

            if ($transaction !== null) {
                $transaction->commit();
            }
        } catch (\Exception $e) {
            if ($transaction !== null) {
                $transaction->rollback();
            }

            throw $e;
        }

        return true;
    }

    /**
     * @param int $oldStatusId
     * @param int $newStatusId
     */
    public function swapStatuses($oldStatusId, $newStatusId)
    {
        $oldStatusId = (int)$oldStatusId;
        $newStatusId = (int)$newStatusId;

        craft()
            ->db
            ->createCommand()
            ->update(
                Freeform_SubmissionRecord::TABLE,
                ["statusId" => $newStatusId],
                "statusId = :oldStatusId",
                [
                    "oldStatusId" => $oldStatusId,
                ]
            );
    }

    /**
     * Gets all submission data by their ID's
     * And returns it as an associative array
     *
     * @param array $submissionIds
     *
     * @return array
     */
    public function getAsArray(array $submissionIds)
    {
        return craft()
            ->db
            ->createCommand()
            ->select('*')
            ->from(Freeform_SubmissionRecord::TABLE)
            ->where(['in', 'id', $submissionIds])
            ->queryAll();
    }

    /**
     * Add a session flash variable that the form has been submitted
     */
    public function markFormAsSubmitted(Form $form)
    {
        craft()->userSession->setFlash(Form::SUBMISSION_FLASH_KEY . $form->getId(), true);
    }

    /**
     * Check for a session flash variable for form submissions
     *
     * @param Form $form
     *
     * @return bool
     */
    public function wasFormFlashSubmitted(Form $form)
    {
        return (bool) craft()->userSession->getFlash(Form::SUBMISSION_FLASH_KEY . $form->getId(), false);
    }

    /**
     * Either returns an array of allowed form ID's
     * for which the user can edit submissions
     *
     * or NULL if *all* form submissions can be edited
     *
     * @return array|null
     */
    public function getAllowedSubmissionFormIds()
    {
        if (PermissionsHelper::checkPermission(PermissionsHelper::PERMISSION_SUBMISSIONS_MANAGE)) {
            return null;
        }

        $formIds = PermissionsHelper::getNestedPermissionIds(PermissionsHelper::PERMISSION_SUBMISSIONS_MANAGE);

        return $formIds;
    }

    /**
     * @return int
     */
    private function getMaxIncrementalId()
    {
        return (int) craft()->db
            ->createCommand()
            ->select('MAX(incrementalId)')
            ->from(Freeform_SubmissionRecord::TABLE)
            ->queryScalar();
    }

    /**
     * Checks if the default set status is valid
     * If it isn't - gets the first one and sets that
     *
     * @param Freeform_SubmissionModel $submissionModel
     */
    private function validateAndUpdateStatus(Freeform_SubmissionModel $submissionModel)
    {
        /** @var Freeform_StatusesService $statusService */
        $statusService = craft()->freeform_statuses;
        $statusIds     = $statusService->getAllStatusIds();

        if (!in_array($submissionModel->statusId, $statusIds)) {
            $submissionModel->statusId = reset($statusIds);
        }
    }

    /**
     * @param Freeform_SubmissionModel $model
     * @param bool                     $isNew
     *
     * @return Event
     */
    private function onBeforeSave(Freeform_SubmissionModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_SubmissionModel $model
     * @param bool                     $isNew
     *
     * @return Event
     */
    private function onAfterSave(Freeform_SubmissionModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_SubmissionModel $model
     *
     * @return Event
     */
    private function onBeforeDelete(Freeform_SubmissionModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_DELETE, $event);

        return $event;
    }

    /**
     * @param Freeform_SubmissionModel $model
     *
     * @return Event
     */
    private function onAfterDelete(Freeform_SubmissionModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_DELETE, $event);

        return $event;
    }
}
