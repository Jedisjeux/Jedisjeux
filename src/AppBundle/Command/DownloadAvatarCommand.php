<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/03/15
 * Time: 19:34
 */

namespace AppBundle\Command;

use AppBundle\Entity\AbstractImage;
use AppBundle\Entity\Avatar;
use Doctrine\ORM\EntityRepository;
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
        $output->writeln("<comment>" . $this->getDescription() . "</comment>");

        /** @var EntityRepository $repository */
        $repository = $this->getContainer()->get('app.repository.avatar');

        $avatars = $repository
            ->findAll();

        /** @var Avatar $avatar */
        foreach ($avatars as $avatar) {

            if (!file_exists($avatar->getAbsolutePath())) {
                $output->writeln("Downloading avatar <info>".$this->getAvatarOriginalPath($avatar)."</info>");

                try {
                    $this
                        ->downloadAvatar($avatar);
                } catch(\Exception $e) {
                    $output->writeln(sprintf("<error>%s</error>", $e->getMessage()));
                }
            }
        }
    }

    /**
     * @param Avatar $avatar
     */
    public function downloadAvatar(Avatar $avatar)
    {
        file_put_contents($avatar->getAbsolutePath(), file_get_contents($this->getAvatarOriginalPath($avatar)));
    }

    /**
     * @param Avatar $avatar
     * @return string
     */
    public function getAvatarOriginalPath(Avatar $avatar)
    {
        return "http://www.jedisjeux.net/phpbb3/download/file.php?avatar=".$avatar->getPath();
    }
} 