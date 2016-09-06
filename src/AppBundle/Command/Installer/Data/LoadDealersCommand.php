<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Dealer;
use AppBundle\Entity\DealerImage;
use AppBundle\Entity\PricesList;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadDealersCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:dealers:load')
            ->setDescription('Load all dealers')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command load all dealers.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        foreach ($this->getDealers() as $data) {
            $output->writeln(sprintf('Load <comment>%s</comment> dealer', $data['name']));

            $dealer = $this->createOrReplaceDealer($data);

            if (!$this->getManager()->contains($dealer)) {
                $this->getManager()->persist($dealer);
            }
        }

        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @param array $data
     *
     * @return Dealer
     */
    protected function createOrReplaceDealer(array $data)
    {
        /** @var Dealer $dealer */
        $dealer = $this->getRepository()->findOneBy(['code' => $data['code']]);

        if (null === $dealer) {
            $dealer = $this->getFactory()->createNew();
        }

        if (isset($data['image'])) {
            $imageInfos = pathinfo($data['image']);
            $dealerImage = $dealer->getImage();

            if (null === $dealerImage) {
                $dealerImage = new DealerImage();
                $dealer->setImage($dealerImage);
            }

            $dealerImage->setPath($data['code'] . "." . $imageInfos['extension']);
            file_put_contents($dealer->getImage()->getAbsolutePath(), file_get_contents($data['image']));
        }

        if (isset($data['pricesList'])) {
            $pricesList = $dealer->getPricesList();

            if (null === $pricesList) {
                $pricesList = new PricesList();
            }

            $pricesList
                ->setDealer($dealer)
                ->setActive($data['pricesList']['active'])
                ->setPath($data['pricesList']['path'])
                ->setHeaders($data['pricesList']['headers']);

            $dealer->setPricesList($pricesList);
        }

        $dealer
            ->setCode($data['code'])
            ->setName($data['name'])
            ->setActive($data['active']);

        return $dealer;
    }

    /**
     * @return array
     */
    protected function getDealers()
    {
        return [
            [
                'code' => 'blue-glaucus',
                'name' => 'Blue Glaucus',
                'active' => true,
            ],
            [
                'code' => 'esprit-jeu',
                'name' => 'Esprit Jeu',
                'active' => true,
                'image' => __DIR__ . '/../../../../../web/assets/img/Logo-Esprit-Jeu-HD.png',
            ],
            [
                'code' => 'fungames',
                'name' => 'Fungames',
                'active' => true,
            ],
            [
                'code' => 'ludifolie',
                'name' => 'Ludifolie',
                'active' => true,
                'image' => 'http://www.ludifolie.com/images/logo-400x200.jpg',
            ],
            [
                'code' => 'ludibay',
                'name' => 'Ludibay',
                'active' => true,
                'image' => 'http://festivaldujeu.istres.free.fr/images/ludibay%20logo%20court.jpg',
            ],
            [
                'code' => 'ludomus',
                'name' => 'Ludomus',
                'active' => true,
                'image' => 'https://geodorthophonie.files.wordpress.com/2015/09/ludomus.jpg',
            ],
            [
                'code' => 'philibert',
                'name' => 'Philibert',
                'active' => true,
                'image' => 'http://ulule.me/presales/0/6/6/9660/philibert_jpg_640x860_q85.jpg',
                'pricesList' => [
                    'active' => true,
                    'path' => 'philibert.csv',
                    'headers' => false,
                ],
            ],
            [
                'code' => 'sur-la-route-du-jeu',
                'name' => 'Sur La Route Du Jeu',
                'active' => true,
            ],

        ];
    }

    /**
     * @return Factory
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.dealer');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.dealer');
    }
}
