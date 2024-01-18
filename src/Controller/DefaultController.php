<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace App\Controller;

use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\Event;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    /**
     * @Route("/examples", name="examples")
     */
    public function examplesAction(): Response
    {
        return $this->render('default/examples.html.twig', [
            'hideBreadcrumbs' => true,
        ]);
    }

    /**
     *
     * @return array
     */
    #[Template('default/default.html.twig')]
    public function defaultAction(): array
    {
        return [];
    }

    public function genericMailAction(): Response
    {
        return $this->render('default/generic_mail.html.twig');
    }

    public function galleryRenderletAction(Request $request): Response
    {
        $params = [];
        if ($request->attributes->get('id') && $request->attributes->get('type') === 'asset') {
            $params['asset'] = Asset::getById($request->attributes->getInt('id'));
        }

        return $this->render('default/gallery_renderlet.html.twig', $params);
    }

    public function eventAction(Request $request): Response
    {
        if ($request->get('type') == 'object') {
            if ($event = Event::getById($request->get('id'))) {
                dump($event);
                return $this->render('default/event.html.twig', ['event' => $event]);
            }
        }

        throw new NotFoundHttpException('Event not found.');

    }
}
