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

use Solspace\Freeform\Library\Composer\Components\AbstractField;
use Solspace\Freeform\Library\Composer\Components\FieldInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\FileUploadField;
use Solspace\Freeform\Library\Exceptions\FreeformException;
use Solspace\Freeform\Library\FileUploads\FileUploadHandlerInterface;
use Solspace\Freeform\Library\FileUploads\FileUploadResponse;

class Freeform_FilesService extends BaseApplicationComponent implements FileUploadHandlerInterface
{
    /** @var array */
    private static $fileUploadFieldIds;

    /**
     * Uploads a file and flags it as "unfinalized"
     * It will be finalized only after the form has been submitted fully
     *
     * All unfinalized files will be deleted after a certain amount of time
     *
     * @param FileUploadField $field
     *
     * @return FileUploadResponse|null
     * @throws FreeformException
     */
    public function uploadFile(FileUploadField $field)
    {
        if (!$field->getAssetSourceId()) {
            return null;
        }

        $assetService = craft()->assets;

        $uploadedFile = UploadedFile::getInstanceByName($field->getHandle());
        $folder       = $assetService->getRootFolderBySourceId($field->getAssetSourceId());

        if (!$uploadedFile) {
            return null;
        }

        if (!$this->onBeforeUpload($field)->performAction) {
            return null;
        }

        try {
            $response = $assetService->insertFileByLocalPath(
                $uploadedFile->tempName,
                $uploadedFile->name,
                $folder->id,
                AssetConflictResolution::KeepBoth
            );
        } catch (Exception $e) {
            return new FileUploadResponse(null, [$e->getMessage()]);
        }

        if ($response->isSuccess()) {
            $assetId = $response->getDataItem("fileId");
            $this->markAssetUnfinalized($assetId);

            $this->onAfterUpload($field, $assetId);

            return new FileUploadResponse($assetId);
        } elseif ($response->isError()) {
            return new FileUploadResponse(null, $response->getErrors());
        }

        throw new FreeformException(Craft::t("Could not handle file upload"));
    }

    /**
     * Returns an array of all fields which are of type FILE
     *
     * @return array
     */
    public function getFileUploadFieldIds()
    {
        if (is_null(self::$fileUploadFieldIds)) {
            $fileUploadFieldIds = craft()
                ->db
                ->createCommand()
                ->select("id")
                ->from("freeform_fields")
                ->where("type = '" . FieldInterface::TYPE_FILE . "'")
                ->queryColumn();

            self::$fileUploadFieldIds = $fileUploadFieldIds;
        }

        return self::$fileUploadFieldIds;
    }

    /**
     * Stores the unfinalized assetId in the database
     * So that it can be deleted later if the form hasn't been finalized
     *
     * @param int $assetId
     */
    public function markAssetUnfinalized($assetId)
    {
        $record          = new Freeform_UnfinalizedFileRecord();
        $record->assetId = $assetId;
        $record->save(false);
    }

    /**
     * Remove all unfinalized assets which are older than the TTL
     * specified in settings
     */
    public function cleanUpUnfinalizedAssets()
    {
        $date = new \DateTime("-180 minutes");

        $assetIds = craft()
            ->db
            ->createCommand()
            ->select("assetId")
            ->from("freeform_unfinalized_files")
            ->where(
                "dateCreated < :now",
                ["now" => $date->format("Y-m-d H:i:s")]
            )
            ->queryColumn();

        if (!empty($assetIds)) {
            craft()->assets->deleteFiles($assetIds, true);
        }
    }

    /**
     * Get a serializable array of asset sources containing
     * their ID, name and type
     *
     * @return array
     */
    public function getAssetSources()
    {
        $assetSourceModels = craft()->assetSources->getAllSources(false);
        $assetSources      = [];
        foreach ($assetSourceModels as $source) {
            $assetSources[] = [
                "id"   => (int)$source->id,
                "name" => $source->name,
                "type" => $source->type,
            ];
        }

        return $assetSources;
    }

    /**
     * Get a key-value list of asset sources, indexed by ID
     *
     * @return array
     */
    public function getAssetSourceList()
    {
        $assetSourceModels = craft()->assetSources->getAllSources(false);
        $assetSources      = [];
        foreach ($assetSourceModels as $source) {
            $assetSources[(int)$source->id] = $source->name;
        }

        return $assetSources;
    }

    /**
     * Returns an array of all file kinds
     * [type => [ext, ext, ..]
     * I.e. ["image" => ["gif", "png", "jpg", "jpeg", ..]]
     *
     * @return array
     */
    public function getFileKinds()
    {
        $fileKinds = IOHelper::getFileKinds();
        $fileKinds['illustrator']['extensions'][] = 'eps';

        $returnArray = [];
        foreach ($fileKinds as $kind => $extensions) {
            $returnArray[$kind] = $extensions["extensions"];
        }

        return $returnArray;
    }

    /**
     * @param FileUploadField $field
     *
     * @return Event
     */
    private function onBeforeUpload(FileUploadField $field)
    {
        $event = new Event($this, ['field' => $field]);
        $this->raiseEvent(FreeformPlugin::EVENT_BEFORE_UPLOAD, $event);

        return $event;
    }

    /**
     * @param FileUploadField $field
     * @param int             $assetId
     *
     * @return Event
     */
    private function onAfterUpload(FileUploadField $field, $assetId)
    {
        $event = new Event($this, ['field' => $field, 'assetId' => $assetId]);
        $this->raiseEvent(FreeformPlugin::EVENT_AFTER_UPLOAD, $event);

        return $event;
    }
}
