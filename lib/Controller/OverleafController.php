<?php

declare(strict_types=1);

namespace OCA\Overleaf\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\ApiRoute;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\Route;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\Constants;
use OCP\IRequest;

/**
 * @psalm-suppress UnusedClass
 */
class OverleafController extends Controller {

	public function __construct(
		string $appName,
		IRequest $request,
	) {
		parent::__construct($appName, $request);
	}
	#[NoAdminRequired]
	#[ApiRoute(verb: 'GET', url: '/api/overleaf')]
	#[Http\Attribute\NoCSRFRequired]
	public function index(): DataResponse {
		return new DataResponse(
			[
				'message' => 1,
			],
			Http::STATUS_OK,
		);
	}
}
