<?php

namespace Craft;

class TinyImageVariable
{
    public function localSources()
    {
        return craft()->tinyImage_source->getLocalSources();
    }

    public function imageCountBySource($sourceId)
    {
        return craft()->tinyImage_source->getImageCountBySource($sourceId);
    }
}
