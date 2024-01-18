<?php

namespace App\MyAreaBricks\Custom\Areabricks;

use Pimcore\Extension\Document\Areabrick\Attribute\AsAreabrick;
use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

#[AsAreabrick(id: 'events-featured')]
class EventsFeatured extends AbstractTemplateAreabrick
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'Events Featured';
    }
}
