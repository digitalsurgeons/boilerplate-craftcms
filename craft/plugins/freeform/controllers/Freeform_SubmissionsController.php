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

use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\MultipleValueInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\NoStorageInterface;
use Solspace\Freeform\Library\DataExport\ExportDataCSV;
use Solspace\Freeform\Library\Helpers\PermissionsHelper;

class Freeform_SubmissionsController extends Freeform_BaseController
{
    /**
     * @throws HttpException
     */
    public function actionIndex()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SUBMISSIONS_ACCESS);

        $forms = $this->getFormService()->getAllForms();
        craft()->templates->includeJsResource('freeform/js/cp/submissions.js');

        if (file_exists(__DIR__ . '/../resources/js/cp/export-button.js')) {
            craft()->templates->includeJsResource('freeform/js/cp/export-button.js');
            craft()->templates->includeTranslations('Quick Export');
        }

        $this->renderTemplate('freeform/submissions', ['forms' => $forms]);
    }

    /**
     * Exports submission data as CSV
     *
     * @throws Exception
     */
    public function actionExport()
    {
        $this->requirePostRequest();
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SUBMISSIONS_ACCESS);

        $submissionIds = craft()->request->getRequiredPost('submissionIds');
        $submissionIds = explode(',', $submissionIds);

        $submissions = $this->getSubmissionService()->getAsArray($submissionIds);

        $form = null;
        if ($submissions) {
            $formId = $submissions[0]['formId'];
            $form   = $this->getFormService()->getFormById($formId);

            if (!$form) {
                throw new Exception(Craft::t('Form with ID {id} not found', ['id' => $formId]));
            }
        } else {
            throw new Exception(Craft::t('No submissions found'));
        }

        $csvData = [];
        $labels  = ['ID', 'Submission Date'];
        foreach ($submissions as $submission) {
            $rowData = [];
            $rowData[] = $submission['id'];
            $rowData[] = $submission['dateCreated'];

            foreach ($form->getLayout()->getFields() as $field) {
                if ($field instanceof NoStorageInterface) {
                    continue;
                }

                if (empty($csvData)) {
                    $labels[] = $field->getLabel();
                }

                $columnName = Freeform_SubmissionRecord::getFieldColumnName($field->getId());

                $value = $submission[$columnName];
                if ($field instanceof MultipleValueInterface) {
                    $value = json_decode($value);
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                }

                $rowData[] = $value;
            }

            $csvData[] = $rowData;
        }
        unset($submissions);

        array_unshift($csvData, $labels);

        $fileName = sprintf('%s submissions %s.csv', $form->name, date('Y-m-d H:i', time()));

        $export = new ExportDataCSV('browser', $fileName);
        $export->initialize();

        foreach ($csvData as $csv) {
            $export->addRow($csv);
        }

        $export->finalize();
        exit();
    }

    /**
     * @param array $variables
     *
     * @throws HttpException
     */
    public function actionEdit(array $variables = [])
    {
        if (empty($variables['submission'])) {
            $submissionId = isset($variables['submissionId']) ? $variables['submissionId'] : null;

            /** @var Freeform_SubmissionModel $submission */
            $submission = $this->getSubmissionService()->getSubmissionById($submissionId);

            if (!$submission) {
                throw new HttpException(404, Craft::t('Submission with ID {id} not found', ['id' => $submissionId]));
            }

            $title = $submission->getContent()->title;
        } else {
            /** @var Freeform_SubmissionModel $submission */
            $submission   = $variables['submission'];
            $submissionId = $submission->id;
            $title        = $submission->getContent()->title;
        }

        /** @var array|null $allowedFormIds */
        $allowedFormIds = craft()->freeform_submissions->getAllowedSubmissionFormIds();
        if (null !== $allowedFormIds) {
            PermissionsHelper::requirePermission(
                PermissionsHelper::prepareNestedPermission(
                    PermissionsHelper::PERMISSION_SUBMISSIONS_MANAGE,
                    $submission->formId
                )
            );
        }

        craft()->templates->includeCssResource('freeform/css/submissions.css');
        craft()->templates->includeJsResource('freeform/js/cp/submissions.js');

        $layout = $submission->getForm()->getLayout();

        $statuses        = [];
        $statusModelList = craft()->freeform_statuses->getAllStatuses();
        foreach ($statusModelList as $id => $status) {
            $statuses[$id] = $status;
        }

        $variables = array_merge(
            $variables,
            [
                'submission'         => $submission,
                'layout'             => $layout,
                'title'              => $title,
                'statuses'           => $statuses,
                'continueEditingUrl' => 'freeform/submissions/{id}',
                'crumbs'             => [
                    ['label' => 'Freeform', 'url' => UrlHelper::getUrl('freeform')],
                    ['label' => Craft::t('Submissions'), 'url' => UrlHelper::getUrl('freeform/submissions')],
                    [
                        'label' => $title,
                        'url'   => UrlHelper::getUrl(
                            'freeform/submissions/' . $submissionId
                        ),
                    ],
                ],
            ]
        );

        $this->renderTemplate('freeform/submissions/edit', $variables);
    }

    /**
     * @throws Exception
     */
    public function actionSave()
    {
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SUBMISSIONS_MANAGE);

        $post = craft()->request->getPost();

        $submissionId    = isset($post['submissionId']) ? $post['submissionId'] : null;
        $submissionModel = $this->getSubmissionService()->getSubmissionById($submissionId);

        $submissionModel->setAttributes($post);
        $submissionModel->getContent()->title = craft()->request->getPost('title', $submissionModel->title);

        if ($this->getSubmissionService()->save($submissionModel)) {
            // Return JSON response if the request is an AJAX request
            if (craft()->request->isAjaxRequest()) {
                $this->returnJson(['success' => true]);
            } else {
                craft()->userSession->setNotice(Craft::t('Submission updated'));
                craft()->userSession->setFlash(Craft::t('Submission updated'), true);
                $this->redirectToPostedUrl($submissionModel);
            }
        } else {
            // Return JSON response if the request is an AJAX request
            if (craft()->request->isAjaxRequest()) {
                $this->returnJson(['success' => false]);
            } else {
                craft()->userSession->setError(Craft::t('Submission could not be updated'));

                // Send the event back to the template
                craft()->urlManager->setRouteVariables(
                    ['submission' => $submissionModel, 'errors' => $submissionModel->getErrors()]
                );
            }
        }
    }

    /**
     * Deletes a field
     */
    public function actionDelete()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();
        PermissionsHelper::requirePermission(PermissionsHelper::PERMISSION_SUBMISSIONS_MANAGE);

        $submissionId = craft()->request->getRequiredPost('id');

        $submission = $this->getSubmissionService()->getSubmissionById($submissionId);
        if ($submission) {
            $this->getSubmissionService()->delete($submission);
            $this->returnJson(['success' => true]);
        }

        $this->returnErrorJson(
            Craft::t('Could not find submission by ID {id}', ['id' => $submissionId])
        );
    }
}
