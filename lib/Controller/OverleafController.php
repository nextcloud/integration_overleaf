<?php

declare(strict_types=1);

namespace OCA\Overleaf\Controller;

use Exception;
use OCA\Overleaf\Service\OverleafService;
use OCA\Overleaf\Service\OverleafSettingsService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\Route;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

/**
 * @psalm-suppress UnusedClass
 */
class OverleafController extends Controller {

	public function __construct(
		string $appName,
		IRequest $request,
		private OverleafService $overleafService,
		private OverleafSettingsService $overleafSettingsService,
		private ?string $userId,
	) {
		parent::__construct($appName, $request);
	}
	/**
	 * Generate an Overleaf link with all required shares for the given fileIds to be opened in Overleaf
	 *
	 * @param int $fileId File id to pass into the Overleaf link
	 * @return DataResponse<Http::STATUS_OK, string, array{}>
	 *
	 * 200: Data Returned
	 */
	#[NoAdminRequired]
	#[Route(type: Route::TYPE_FRONTPAGE, verb: 'POST', url: '/api/overleaf')]
	public function getOverleaf(int $fileId): DataResponse {
		if ($this->userId === null) {
			return new DataResponse('', Http::STATUS_BAD_REQUEST);
		}
		try {
			$url = $this->overleafService->generateOverleafUrl($fileId, $this->userId);
			return new DataResponse(
				$url,
				Http::STATUS_OK,
			);
		} catch (Exception $e) {
			return new DataResponse(
				$e->getMessage(),
				Http::STATUS_INTERNAL_SERVER_ERROR,
			);
		}
	}
	/**
	 * Update config for Overleaf server
	 *
	 * @param string $overleafServer The url of the Overleaf server
	 * @return DataResponse<Http::STATUS_OK, string, array{}>
	 *
	 * 200: Empty response
	 */
	#[NoAdminRequired]
	#[Route(type: Route::TYPE_FRONTPAGE, verb: 'PUT', url: '/api/admin-config')]
	public function overleafAdminConfig(string $overleafServer): DataResponse {
		$this->overleafSettingsService->setOverleafServer($overleafServer);
		return new DataResponse(
			'',
			Http::STATUS_OK,
		);
	}
}
