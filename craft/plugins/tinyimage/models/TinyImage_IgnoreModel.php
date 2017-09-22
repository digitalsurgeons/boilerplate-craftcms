<?php

namespace Craft;

class TinyImage_IgnoreModel extends BaseModel
{
    /**
     * Define the models attributes.
     *
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'assetId' => array(
                'type' => AttributeType::Number,
                'required' => true
            )
        );
    }
}
