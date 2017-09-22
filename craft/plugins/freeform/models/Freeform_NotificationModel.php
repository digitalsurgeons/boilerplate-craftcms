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

use Solspace\Freeform\Library\DataObjects\EmailTemplate;
use Solspace\Freeform\Library\Mailing\NotificationInterface;

/**
 * Class Freeform_NotificationModel
 *
 * @property int    $id
 * @property string $name
 * @property string $handle
 * @property string $description
 * @property string $fromName
 * @property string $fromEmail
 * @property string $replyToEmail
 * @property bool   $includeAttachments
 * @property string $subject
 * @property string $bodyHtml
 * @property string $bodyText
 * @property int    $sortOrder
 */
class Freeform_NotificationModel extends BaseModel implements NotificationInterface, \JsonSerializable
{
    /**
     * @return $this
     */
    public static function create()
    {
        $model            = new Freeform_NotificationModel();
        $model->fromEmail = craft()->systemSettings->getSetting("email", "emailAddress");
        $model->fromName  = craft()->systemSettings->getSetting("email", "senderName");
        $model->subject   = "New submission from {{ form.name }}";
        $model->bodyHtml  = <<<EOT
<p>Submitted on: {{ dateCreated|date('Y-m-d H:i:s') }}</p>
<ul>
{% for field in allFields %}
    <li>{{ field.label }}: {{ field.getValueAsString() }}</li>
{% endfor %}
</ul>
EOT;
        $model->bodyText  = $model->bodyHtml;

        return $model;
    }

    /**
     * @param string $filePath
     *
     * @return Freeform_NotificationModel
     */
    public static function createFromTemplate($filePath)
    {
        $template = new EmailTemplate($filePath);

        $model                     = new Freeform_NotificationModel();
        $model->id                 = pathinfo($filePath, PATHINFO_BASENAME);
        $model->name               = $template->getName();
        $model->handle             = $template->getHandle();
        $model->description        = $template->getDescription();
        $model->fromEmail          = $template->getFromEmail();
        $model->fromName           = $template->getFromName();
        $model->subject            = $template->getSubject();
        $model->replyToEmail       = $template->getReplyToEmail();
        $model->bodyHtml           = $template->getBody();
        $model->bodyText           = $template->getBody();
        $model->includeAttachments = $template->isIncludeAttachments();

        return $model;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return bool
     */
    public function isFileBasedTemplate()
    {
        return !is_numeric($this->id);
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @return string
     */
    public function getReplyToEmail()
    {
        return $this->replyToEmail;
    }

    /**
     * @return bool
     */
    public function isIncludeAttachmentsEnabled()
    {
        return (bool) $this->includeAttachments;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    /**
     * @return string
     */
    public function getBodyText()
    {
        return $this->bodyText;
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            "id"                 => AttributeType::Number,
            "name"               => AttributeType::String,
            "handle"             => [
                "type"     => AttributeType::Handle,
                "required" => true,
                "unique"   => true,
            ],
            "description"        => [
                "type"   => AttributeType::String,
                "column" => ColumnType::Text,
            ],
            "fromName"           => AttributeType::String,
            "fromEmail"          => AttributeType::String,
            "replyToEmail"       => AttributeType::String,
            "includeAttachments" => AttributeType::Bool,
            "subject"            => AttributeType::String,
            "bodyHtml"           => [
                "type"   => AttributeType::String,
                "column" => ColumnType::Text,
            ],
            "bodyText"           => [
                "type"   => AttributeType::String,
                "column" => ColumnType::Text,
            ],
            "sortOrder"          => AttributeType::Number,
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *        which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            "id"          => is_numeric($this->id) ? (int) $this->id : $this->id,
            "name"        => $this->name,
            "handle"      => $this->handle,
            "description" => $this->description,
        ];
    }
}
