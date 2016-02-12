<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/03/15
 * Time: 19:34
 */

namespace AppBundle\Command;

use JDJ\UserBundle\Entity\Avatar;
use JDJ\UserBundle\Service\AvatarImportService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DownloadAvatarCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:avatars:download')
            ->setDescription('Download avatars of all users')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var AvatarImportService $avatarImportService */
        $avatarImportService = $this->getContainer()->get('app.service.avatar.import');

        $avatars = $avatarImportService
            ->getAvatars();

        /** @var Avatar $avatar */
        foreach ($avatars as $avatar) {

            if (!file_exists($avatar->getAbsolutePath())) {
                $output->writeln("Downloading avatar <info>".$avatarImportService->getAvatarOriginalPath($avatar)."</info>");

                try {
                    $avatarImportService
                        ->downloadAvatar($avatar);
                } catch(\Exception $e) {
                    $output->writeln("<error>Downloading failed</error>");
                }
            }
        }
    }
} 