<?php

declare(strict_types=1);

namespace OCA\Overleaf\Controller;

use OCA\Overleaf\Services\OverleafService;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\ApiRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;

/**
 * @psalm-suppress UnusedClass
 */
class OverleafController extends OCSController {

	public function __construct(
		string $appName,
		IRequest $request,
		private OverleafService $overleafService,
		private ?string $userId,
	) {
		parent::__construct($appName, $request);
	}
	/**
	 * Generate an Overleaf link with all required shares for the given fileIds to be opened in Overleaf
	 *
	 * @param list<int> $fileIds File ids to pass into the Overleaf link
	 * @return DataResponse<Http::STATUS_OK, array{message: string}, array{}>
	 *
	 * 200: Data Returned
	 */
	#[NoAdminRequired]
	#[ApiRoute(verb: 'POST', url: '/api/overleaf')]
	public function getOverleaf(array $fileIds): DataResponse {
		if ($this->userId === null) {
			return new DataResponse(
				[
					'message' => 'No user id found',
				],
				Http::STATUS_UNAUTHORIZED,
			);
		}
		try {
			$url = $this->overleafService->generateOverleafUrl($fileIds, $this->userId);
			return new DataResponse(
				[
					'message' => $url,
				],
				Http::STATUS_OK,
			);
		} catch (\Exception $e) {
			return new DataResponse(
				[
					'message' => $e->getMessage(),
				],
				Http::STATUS_INTERNAL_SERVER_ERROR,
			);
		}
	}
}
