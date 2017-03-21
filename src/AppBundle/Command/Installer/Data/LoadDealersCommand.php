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
use AppBundle\Entity\PriceList;
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

        if (isset($data['priceList'])) {
            $priceList = $dealer->getPriceList();

            if (null === $priceList) {
                $priceList = new PriceList();
            }

            $priceList
                ->setDealer($dealer)
                ->setActive($data['priceList']['active'])
                ->setPath($data['priceList']['path'])
                ->setHeaders($data['priceList']['headers'])
                ->setDelimiter($data['priceList']['delimiter'] ?? ';')
                ->setUtf8($data['priceList']['utf8'] ?? true);


            $dealer->setPriceList($priceList);
        }

        $dealer
            ->setCode($data['code'])
            ->setName($data['name']);

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
                'image' => 'http://www.blueglaucus.com/img/themes/normal/blue_glaucus.png',
                'priceList' => [
                    'active' => true,
                    'path' => 'http://www.blueglaucus.com/files/csv_divers/jeudisjeux.csv',
                    'headers' => false,
                ],
            ],
            [
                'code' => 'esprit-jeu',
                'name' => 'Esprit Jeu',
                'image' => __DIR__ . '/../../../../../web/assets/frontend/img/Logo-Esprit-Jeu-HD.png',
                'priceList' => [
                    'active' => true,
                    'path' => 'http://www.espritjeu.com/dhtml/fluxComparateur.php?w=2&c=016ab62aea63a0eaa98ce3aebee5ed39&t=csv',
                    'headers' => true,
                ],
            ],
            [
                'code' => 'fungames',
                'name' => 'Fungames',
                'image' => 'http://www.fungamesnet.fr/img/fungamesnet-logo-1488007203.jpg',
            ],
            [
                'code' => 'ludifolie',
                'name' => 'Ludifolie',
                'image' => 'http://www.ludifolie.com/images/logo-400x200.jpg',
                'priceList' => [
                    'active' => true,
                    'path' => 'http://www.ludifolie.com/jedisjeux-export-tarifs.csv',
                    'headers' => false,
                    'utf8' => false,
                ],
            ],
            [
                'code' => 'ludibay',
                'name' => 'Ludibay',
                'image' => 'http://festivaldujeu.istres.free.fr/images/ludibay%20logo%20court.jpg',
            ],
            [
                'code' => 'ludomus',
                'name' => 'Ludomus',
                'image' => 'https://geodorthophonie.files.wordpress.com/2015/09/ludomus.jpg',
                'priceList' => [
                    'active' => true,
                    'path' => 'http://www.ludomus.com/export/jedisjeux-export-tarif.csv',
                    'headers' => true,
                    'utf8' => false,
                ],
            ],
            [
                'code' => 'philibert',
                'name' => 'Philibert',
                'image' => 'http://ulule.me/presales/0/6/6/9660/philibert_jpg_640x860_q85.jpg',
                'priceList' => [
                    'active' => true,
                    'path' => 'http://www.philibertnet.com/modules/ukoo_fluxjedisjeux/export.csv',
                    'headers' => false,
                ],
            ],
            [
                'code' => 'jeux-en-boite',
                'name' => 'Jeux en boite',
                'image' => 'http://www.jeuxenboite.be/presta/img/logo.jpg',
                'priceList' => [
                    'active' => true,
                    'path' => 'http://www.jeuxenboite.be/presta/jedisjeux-export-tarifs.csv',
                    'headers' => false,
                    'delimiter' => ',',
                    'utf8' => false,
                ],
            ],
            [
                'code' => 'jumanji-shop',
                'name' => 'JumanjiShop',
                'priceList' => [
                    'active' => true,
                    'path' => 'http://www.jumanji-shop.com/jedisjeux.csv',
                    'headers' => false,
                    'delimiter' => '|',
                ],
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
