<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/03/15
 * Time: 19:34
 */

namespace JDJ\UserBundle\Command;

use JDJ\UserBundle\Entity\User;
use JDJ\UserBundle\Service\AvatarImportService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AvatarImportCommand
 * @package JDJ\UserBundle\Command
 */
class AvatarImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:avatar:import')
            ->setDescription('Greet someone')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var AvatarImportService $avatarImportService */
        $avatarImportService = $this->getContainer()->get('app.service.avatar.import');

        $avatars = $avatarImportService
            ->getAvatars();

        /** @var User $user */
        foreach ($avatars as $user) {

            if (null !== $user->getAvatar()) {
                if (!file_exists($user->getAbsolutePath())) {
                    $output->writeln("Downloading avatar ".$avatarImportService->getAvatarOriginalPath($user));

                    $avatarImportService
                        ->downloadAvatar($user);
                }
            }

        }
    }
} 