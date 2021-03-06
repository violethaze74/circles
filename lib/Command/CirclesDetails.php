<?php

declare(strict_types=1);


/**
 * Circles - Bring cloud-users closer together.
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 * @copyright 2017
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace OCA\Circles\Command;

use daita\MySmallPhpTools\Exceptions\InvalidItemException;
use daita\MySmallPhpTools\Exceptions\RequestNetworkException;
use daita\MySmallPhpTools\Exceptions\SignatoryException;
use OC\Core\Command\Base;
use OCA\Circles\Exceptions\CircleNotFoundException;
use OCA\Circles\Exceptions\FederatedItemException;
use OCA\Circles\Exceptions\FederatedUserException;
use OCA\Circles\Exceptions\FederatedUserNotFoundException;
use OCA\Circles\Exceptions\InitiatorNotFoundException;
use OCA\Circles\Exceptions\MemberNotFoundException;
use OCA\Circles\Exceptions\OwnerNotFoundException;
use OCA\Circles\Exceptions\RemoteInstanceException;
use OCA\Circles\Exceptions\RemoteNotFoundException;
use OCA\Circles\Exceptions\RemoteResourceNotFoundException;
use OCA\Circles\Exceptions\UnknownRemoteException;
use OCA\Circles\Exceptions\UserTypeNotFoundException;
use OCA\Circles\Service\CircleService;
use OCA\Circles\Service\ConfigService;
use OCA\Circles\Service\FederatedUserService;
use OCA\Circles\Service\MemberService;
use OCA\Circles\Service\RemoteService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class CirclesDetails
 *
 * @package OCA\Circles\Command
 */
class CirclesDetails extends Base {


	/** @var FederatedUserService */
	private $federatedUserService;

	/** @var RemoteService */
	private $remoteService;

	/** @var MemberService */
	private $memberService;

	/** @var CircleService */
	private $circleService;

	/** @var ConfigService */
	private $configService;


	/**
	 * CirclesDetails constructor.
	 *
	 * @param FederatedUserService $federatedUserService
	 * @param RemoteService $remoteService
	 * @param CircleService $circlesService
	 * @param MemberService $membersService
	 * @param ConfigService $configService
	 */
	public function __construct(
		FederatedUserService $federatedUserService, RemoteService $remoteService,
		CircleService $circlesService, MemberService $membersService, ConfigService $configService
	) {
		parent::__construct();
		$this->federatedUserService = $federatedUserService;
		$this->remoteService = $remoteService;
		$this->circleService = $circlesService;
		$this->memberService = $membersService;
		$this->configService = $configService;
	}


	/**
	 *
	 */
	protected function configure() {
		parent::configure();
		$this->setName('circles:manage:details')
			 ->setDescription('get details about a circle by its ID')
			 ->addArgument('circle_id', InputArgument::REQUIRED, 'ID of the circle')
			 ->addOption('instance', '', InputOption::VALUE_REQUIRED, 'Instance of the circle', '')
			 ->addOption('initiator', '', InputOption::VALUE_REQUIRED, 'set an initiator to the request', '')
			 ->addOption('status-code', '', InputOption::VALUE_NONE, 'display status code on exception');

	}


	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 *
	 * @return int
	 * @throws CircleNotFoundException
	 * @throws InitiatorNotFoundException
	 * @throws InvalidItemException
	 * @throws OwnerNotFoundException
	 * @throws RemoteInstanceException
	 * @throws RemoteNotFoundException
	 * @throws RemoteResourceNotFoundException
	 * @throws RequestNetworkException
	 * @throws SignatoryException
	 * @throws UnknownRemoteException
	 * @throws FederatedUserException
	 * @throws FederatedUserNotFoundException
	 * @throws MemberNotFoundException
	 * @throws UserTypeNotFoundException
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int {
		$circleId = (string)$input->getArgument('circle_id');
		$instance = $input->getOption('instance');
		$initiator = $input->getOption('initiator');

		try {
			if ($instance !== '') {
				$data = ['initiator' => $initiator];
				$circle = $this->remoteService->getCircleFromInstance($circleId, $instance, $data);
			} else {

				try {
					$this->federatedUserService->commandLineInitiator($initiator, $circleId, true);
					$circle = $this->circleService->getCircle($circleId);
				} catch (CircleNotFoundException $e) {
					throw new CircleNotFoundException(
						'unknown circle, use --instance to retrieve the data from a remote instance'
					);
				}
			}
		} catch (FederatedItemException $e) {
			if ($input->getOption('status-code')) {
				throw new FederatedItemException(
					' [' . get_class($e) . ', ' . $e->getStatus() . ']' . "\n" . $e->getMessage()
				);
			}

			throw $e;
		}

		echo json_encode($circle, JSON_PRETTY_PRINT) . "\n";

		return 0;
	}

}

