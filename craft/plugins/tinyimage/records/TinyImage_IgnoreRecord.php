<?php

namespace Craft;

class TinyImage_IgnoreRecord extends BaseRecord
{
    /**
     * Returns the name of the associated database table.
     *
     * @return string
     */
    public function getTableName()
    {
        return 'tinyimage_assets_ignored';
    }

    /**
     * Define the records attributes.
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
