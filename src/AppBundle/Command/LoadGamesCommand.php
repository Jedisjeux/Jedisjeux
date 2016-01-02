<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 16:32
 */

namespace AppBundle\Command;

use Doctrine\ORM\EntityRepository;
use JDJ\JeuBundle\Entity\Jeu;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadGamesCommand extends LoadCommand
{
    protected $writeEntityInOutput = false;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:games:load')
            ->setDescription('Load games');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln("<comment>Load games</comment>");

        parent::execute($input, $output);
    }

    /**
     * @inheritdoc
     */
    public function getRows()
    {
        $query = <<<EOM
select      old.id,
            old.nom as name,
            old.min as joueurMin,
            old.max as joueurMax,
            old.age_min as ageMin,
            old.presentation as description,
            old.duree as durationMin,
            old.duree as durationMax,
            old.valid as status,
            old.date as createdAt,
            old.date as updatedAt
from        jedisjeux.jdj_game old
where       old.valid in (0, 1, 2, 5, 3)
EOM;
    $rows = $this->getDatabaseConnection()->fetchAll($query);

        return $rows;
    }

    /**
     * @inheritdoc
     */
    public function filterData(array $data)
    {
        $data['joueurMin'] = !empty($data['joueurMin']) ? $data['joueurMin'] : null;
        $data['joueurMax'] = !empty($data['joueurMax']) ? $data['joueurMax'] : null;
        $data['ageMin'] = !empty($data['ageMin']) ? $data['ageMin'] : null;
        $data['description'] = !empty($data['description']) ? $this->getHTMLFromText($data['description']) : null;
        $data['createdAt'] = \DateTime::createFromFormat('Y-m-d H:i:s', $data['createdAt']);
        $data['updatedAt'] = \DateTime::createFromFormat('Y-m-d H:i:s', $data['updatedAt']);
        switch ($data['status']) {
            case 0 :
                $data['status'] = Jeu::WRITING;
                break;
            case 1 :
                $data['status'] = Jeu::PUBLISHED;
                break;
            case 2 :
                $data['status'] = Jeu::NEED_A_TRANSLATION;
                break;
            case 5 :
                $data['status'] = Jeu::NEED_A_REVIEW;
                break;
            case 3 :
                $data['status'] = Jeu::READY_TO_PUBLISH;
                break;

        }

        return parent::filterData($data);
    }

    public function createEntityNewInstance()
    {
        return new Jeu();
    }

    public function getTableName()
    {
        return 'jdj_jeu';
    }

    public function getRepository()
    {
        return $this->getEntityManager()->getRepository('JDJJeuBundle:Jeu');
    }

    private function getHTMLFromText($text)
    {
        $text = trim($text);

        /**
         * Turn Double carryage returns into <p>
         */
        $text = "<p>" . preg_replace("/\\n(\\r)?\n/", "</p><p>", $text) . "</p>";

        /**
         * Turn Simple carryage returns into <br />
         */
        $text = "<p>" . preg_replace("/\\n/", "<br />", $text) . "</p>";

        $text = $this->cleanBBCode($text);

        $text = preg_replace("/\<(b|strong)\>/", '</p><h4>', $text);
        $text = preg_replace("/\<\/(b|strong)\>/", '</h4><p>', $text);

        $text = preg_replace("/\<p\>( |\n|\r)*\<br \/>/", '<p>', $text);
        $text = preg_replace("/\<p\>( )*\<\/p\>/", '', $text);
        return $text;
    }

    private function cleanBBCode($text)
    {
        $text = preg_replace("/\[(b):[0-9a-z]+\]/", '</p><h4>', $text);
        $text = preg_replace("/\[(\/)?(b):[0-9a-z]+\]/", '</h4><p>', $text);
        $text = preg_replace("/\[(\/)?(i):[0-9a-z]+\]/", '<${1}i>', $text);
        return $text;
    }
}