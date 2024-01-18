<?php

namespace App\Controller;

use App\Form\UploadFileFormType;
use Pimcore\Model\Asset;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadController extends BaseController
{
    public function __construct(
        private SluggerInterface $slugger,
    )
    {
    }

    /**
     * Index page for Files Upload
     *
     * @Route("/files/upload", name="files-upload")
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function indexAction(
        Request $request,
    ): Response
    {
        $action = $this->generateUrl('files-upload');
        $form = $this->createForm(UploadFileFormType::class, [], [
            'action' => $action,
        ]);

        //store referer in session to get redirected after login
        if (!$request->get('no-referer-redirect')) {
            $request->getSession()
                ->set(
                    '_security.demo_frontend.target_path',
                    $request->headers->get('referer')
                );
        }

        $form->handleRequest($request);

        $filename = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('file');
            $filenameCustom = $request->get('filename_custom', null);
            $filename = $this->upload($file, $filenameCustom);
        }

        return $this->render('default/files.upload.html.twig', [
            'filename' => $filename,
            'form' => $form->createView(),
            'hideBreadcrumbs' => true,
        ]);
    }

    /**
     * @param UploadedFile $file
     * @param string|null $filename
     * @return string
     */
    public function upload(UploadedFile $file, string $filename = null): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        if (empty($filename)) {
            $safeFilename = $this->slugger->slug($originalFilename);
            $filename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        }
        $fileData = file_get_contents($file->getPathname());

        try {
            $asset = new Asset();
            $asset->setFilename($filename);
            $asset->setParentId(423);
            $asset->setData($fileData);
            $asset->save();
        } catch (\Exception $e) {
            // ... handle exception if something happens during file upload
            dd($e);
        }

        return $filename;
    }
}
