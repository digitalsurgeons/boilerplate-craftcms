<?php

namespace Craft;

use Solspace\Freeform\Library\Composer\Components\Form;
use Solspace\Freeform\Library\DataExport\ExportDataCSV;
use Solspace\Freeform\Library\Exceptions\FreeformException;

class Freeform_ExportProfilesService extends BaseApplicationComponent
{
    /** @var Freeform_ExportProfileModel[] */
    private static $profileCache;
    private static $allProfilesLoaded;

    /**
     * @return Freeform_ExportProfileModel[]
     */
    public function getAllProfiles()
    {
        if (null === self::$profileCache || !self::$allProfilesLoaded) {
            $profileRecords     = Freeform_ExportProfileRecord::model()->findAll();
            self::$profileCache = Freeform_ExportProfileModel::populateModels($profileRecords, 'id');

            self::$allProfilesLoaded = true;
        }

        return self::$profileCache;
    }

    /**
     * @param int $id
     *
     * @return Freeform_ExportProfileModel|null
     */
    public function getProfileById($id)
    {
        if (null === self::$profileCache || !isset(self::$profileCache[$id])) {
            if (null === self::$profileCache) {
                self::$profileCache = [];
            }

            $record = Freeform_ExportProfileRecord::model()->findById($id);

            self::$profileCache[$id] = $record ? Freeform_ExportProfileModel::populateModel($record) : null;
        }

        return self::$profileCache[$id];
    }

    /**
     * @param Freeform_ExportProfileModel $model
     *
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public function save(Freeform_ExportProfileModel $model)
    {
        $isNew = !$model->id;

        if (!$isNew) {
            $record = Freeform_ExportProfileRecord::model()->findById($model->id);

            if (!$record) {
                throw new Exception(Craft::t('Export Profile with ID {id} not found', ['id' => $model->id]));
            }
        } else {
            $record = new Freeform_ExportProfileRecord();
        }

        $beforeSaveEvent = $this->onBeforeSave($model, $isNew);

        $record->name      = $model->name;
        $record->formId    = $model->formId;
        $record->limit     = $model->limit;
        $record->dateRange = $model->dateRange;
        $record->fields    = $model->fields;
        $record->filters   = $model->filters;
        $record->statuses  = $model->statuses;

        $record->validate();
        $model->addErrors($record->getErrors());

        if ($beforeSaveEvent->performAction && !$model->hasErrors()) {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try {
                $record->save(false);

                if (!$model->id) {
                    $model->id = $record->id;
                }

                self::$profileCache[$model->id] = $model;

                if ($transaction !== null) {
                    $transaction->commit();
                }

                $this->onAfterSave($model, $isNew);

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
     * @param int $id
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteById($id)
    {
        $model = $this->getProfileById($id);

        if (!$model) {
            return false;
        }

        if (!$this->onBeforeDelete($model)->performAction) {
            return false;
        }

        $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
        try {
            $affectedRows = craft()->db
                ->createCommand()
                ->delete(
                    Freeform_ExportProfileRecord::TABLE,
                    ['id' => $model->id]
                );

            if ($transaction !== null) {
                $transaction->commit();
            }

            $this->onAfterDelete($model);

            return (bool) $affectedRows;
        } catch (\Exception $exception) {
            if ($transaction !== null) {
                $transaction->rollback();
            }

            throw $exception;
        }
    }

    /**
     * @param Form  $form
     * @param array $labels
     * @param array $data
     */
    public function exportCsv(Form $form, array $labels, array $data)
    {
        $csvData = $data;
        array_unshift($csvData, array_values($labels));

        $fileName = sprintf('%s submissions %s.csv', $form->getName(), date('Y-m-d H:i', time()));

        $export = new ExportDataCSV('browser', $fileName);
        $export->initialize();

        foreach ($csvData as $csv) {
            $export->addRow($csv);
        }

        $export->finalize();
        exit();
    }

    /**
     * @param Form  $form
     * @param array $data
     */
    public function exportJson(Form $form, array $data)
    {
        $export = [];
        foreach ($data as $itemList) {
            $sub = [];
            foreach ($itemList as $id => $value) {
                $label = $this->getHandleFromIdentificator($form, $id);

                $sub[$label] = $value;
            }

            $export[] = $sub;
        }

        $fileName = sprintf('%s submissions %s.json', $form->getName(), date('Y-m-d H:i', time()));

        $output = json_encode($export, JSON_PRETTY_PRINT);

        return $this->outputFile($output, $fileName, 'application/octet-stream');
    }

    /**
     * @param Form  $form
     * @param array $data
     */
    public function exportText(Form $form, array $data)
    {
        $output = '';
        foreach ($data as $itemList) {
            foreach ($itemList as $id => $value) {
                $label = $this->getHandleFromIdentificator($form, $id);

                $output .= $label . ': ' . $value . "\n";
            }

            $output .= "\n";
        }

        $fileName = sprintf('%s submissions %s.txt', $form->getName(), date('Y-m-d H:i', time()));

        return $this->outputFile($output, $fileName, 'text/plain');
    }

    /**
     * @param Form  $form
     * @param array $data
     */
    public function exportXml(Form $form, array $data)
    {
        $xml = new \SimpleXMLElement('<root/>');

        foreach ($data as $itemList) {
            $submission = $xml->addChild('submission');

            foreach ($itemList as $id => $value) {
                $label = $this->getHandleFromIdentificator($form, $id);

                $node = $submission->addChild($label, htmlspecialchars($value));
                $node->addAttribute('label', $this->getLabelFromIdentificator($form, $id));
            }
        }

        $fileName = sprintf('%s submissions %s.xml', $form->getName(), date('Y-m-d H:i', time()));

        return $this->outputFile($xml->asXML(), $fileName, 'text/xml');
    }

    /**
     * @param Form   $form
     * @param string $id
     *
     * @return string
     */
    private function getLabelFromIdentificator(Form $form, $id)
    {
        static $cache;

        if (null === $cache) {
            $cache = [];
        }

        if (!isset($cache[$id])) {
            $label = $id;
            if (preg_match('/^(?:field_)?(\d+)$/', $label, $matches)) {
                $fieldId = $matches[1];
                try {
                    $field = $form->getLayout()->getFieldById($fieldId);
                    $label = $field->getLabel();
                } catch (FreeformException $e) {
                }
            } else {
                switch ($id) {
                    case 'id':
                        $label = 'ID';
                        break;

                    case 'dateCreated':
                        $label = 'Date Created';
                        break;

                    default:
                        $label = ucfirst($label);
                        break;
                }
            }

            $cache[$id] = $label;
        }

        return $cache[$id];
    }

    /**
     * @param Form   $form
     * @param string $id
     *
     * @return string
     */
    private function getHandleFromIdentificator(Form $form, $id)
    {
        static $cache;

        if (null === $cache) {
            $cache = [];
        }

        if (!isset($cache[$id])) {
            $label = $id;
            if (preg_match('/^field_(\d+)$/', $label, $matches)) {
                $fieldId = $matches[1];
                try {
                    $field = $form->getLayout()->getFieldById($fieldId);
                    $label = $field->getHandle();
                } catch (FreeformException $e) {
                }
            }

            $cache[$id] = $label;
        }

        return $cache[$id];
    }

    /**
     * @param string $content
     * @param string $fileName
     * @param string $contentType
     */
    private function outputFile($content, $fileName, $contentType)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . strlen($content));

        echo $content;

        exit();
    }

    /**
     * @param Freeform_ExportProfileModel $model
     * @param bool                        $isNew
     *
     * @return Event
     */
    private function onBeforeSave(Freeform_ExportProfileModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_ExportProfileModel $model
     * @param bool                        $isNew
     *
     * @return Event
     */
    private function onAfterSave(Freeform_ExportProfileModel $model, $isNew)
    {
        $event = new Event($this, ['model' => $model, 'isNew' => $isNew]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_SAVE, $event);

        return $event;
    }

    /**
     * @param Freeform_ExportProfileModel $model
     *
     * @return Event
     */
    private function onBeforeDelete(Freeform_ExportProfileModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_DELETE, $event);

        return $event;
    }

    /**
     * @param Freeform_ExportProfileModel $model
     *
     * @return Event
     */
    private function onAfterDelete(Freeform_ExportProfileModel $model)
    {
        $event = new Event($this, ['model' => $model]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_DELETE, $event);

        return $event;
    }
}
