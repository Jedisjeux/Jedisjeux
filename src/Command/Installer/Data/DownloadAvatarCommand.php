<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command\Installer\Data;

use App\Entity\Avatar;
use Doctrine\ORM\EntityRepository;
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
        $output->writeln('<comment>'.$this->getDescription().'</comment>');

        $repository = $this->getRepository();

        foreach ($repository->createQueryBuilder('o')->getQuery()->iterate() as $row) {
            /** @var Avatar $avatar */
            $avatar = $row[0];

            if (!file_exists($avatar->getAbsolutePath())) {
                $output->writeln('Downloading avatar <info>'.$this->getAvatarOriginalPath($avatar).'</info>');

                try {
                    $this
                        ->downloadAvatar($avatar);
                } catch (\Exception $e) {
                    $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
                }
            }
        }
    }

    /**
     * @return EntityRepository $repository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.avatar');
    }

    public function downloadAvatar(Avatar $avatar)
    {
        file_put_contents($avatar->getAbsolutePath(), file_get_contents($this->getAvatarOriginalPath($avatar)));
    }

    public function getAvatarOriginalPath(Avatar $avatar): string
    {
        return 'http://www.jedisjeux.net/media/cache/resolve/full/'.$avatar->getWebPath();
    }
}
