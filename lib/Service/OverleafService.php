<?php

namespace OCA\Overleaf\Service;

use DateTime;
use Exception;
use OCP\Constants;
use OCP\Files\File;
use OCP\Files\IRootFolder;
use OCP\Files\NotPermittedException;
use OCP\Http\Client\IClientService;
use OCP\IURLGenerator;
use OCP\Share\IManager as ShareManager;
use OCP\Share\IShare;

class OverleafService
{
	public function __construct(
		IClientService                  $clientService,
		private IRootFolder             $root,
		private ShareManager            $shareManager,
		private IURLGenerator           $urlGenerator,
		private OverleafSettingsService $overleafSettingsService
	)
	{
		$this->client = $clientService->newClient();
	}

	/**
	 * Generate Overleaf URL
	 *
	 * @param int $fileIds
	 * @param string $userId
	 * @return string
	 * @throws NotPermittedException
	 * @throws Exception
	 */
	public function generateOverleafUrl(int $fileId, string $userId)
	{
		// Link should not be needed for very long due to only being read once by overleaf.
		$expirationDate = new DateTime('now + 25 hours');
		$overleafUrl = $this->overleafSettingsService->getOverleafServer();
		$userFolder = $this->root->getUserFolder($userId);
		$nodes = $userFolder->getById($fileId);
		if (count($nodes) > 0 && ($nodes[0] instanceof File)) {
			$node = $nodes[0];
			$share = $this->shareManager->newShare();
			$share->setNode($node);
			$share->setPermissions(Constants::PERMISSION_READ);
			$share->setShareType(IShare::TYPE_LINK);
			$share->setSharedBy($userId);
			$share->setLabel('Overleaf');
			$share->setExpirationDate($expirationDate);

			$share = $this->shareManager->createShare($share);
			$token = $share->getToken();
			$linkUrl = $this->urlGenerator->getAbsoluteURL(
				'/public.php/dav/files/' . $token
			);
			$overleafUrl .= '?snip_uri=' . $linkUrl;
		} else {
			throw new Exception('File not found');
		}
		return $overleafUrl;
	}
}
