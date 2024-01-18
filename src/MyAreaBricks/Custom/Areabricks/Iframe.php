<?php

namespace App\MyAreaBricks\Custom\Areabricks;

use Pimcore\Extension\Document\Areabrick\Attribute\AsAreabrick;
use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

#[AsAreabrick(id: 'iframe')]
class Iframe extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'IFrame';
    }
}
