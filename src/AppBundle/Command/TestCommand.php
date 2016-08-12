<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\TextFilter\Bbcode2Html;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TestCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:test')
            ->setDescription('Tests')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command is a test.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $body = $this->getBody();
        $bbcode2html = $this->getBbcode2Html();
        $body = $bbcode2html
            ->setBody($body)
            ->getFilteredBody();
        $output->writeln($body);
    }

    /**
     * @return Bbcode2Html
     */
    protected function getBbcode2Html()
    {
        return $this->getContainer()->get('app.text.filter.bbcode2html');
    }

    /**
     * @return string
     */
    protected function getBody()
    {
        return <<<EOM

[center][image=800,center:808080]45999[/image:808080][/center]

[jeu:pbptg1eo]42[/jeu:pbptg1eo]

[jeu:pbptg1eo]1[/jeu:pbptg1eo]
EOM;

    }
}
