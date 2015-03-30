<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 25/02/2015
 * Time: 19:09
 */

namespace JDJ\CoreBundle\Behat;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * Class UtilsContext
 * @package AppBundle\Behat
 */
class UtilsContext extends DefaultContext
{
    /**
     * @When /^I wait until loading has finished/
     */
    public function waitUntilLoadingHasFinished()
    {
        $this->getSession()->wait(5000, "$('.spinner').is('visible')");
        $this->getSession()->wait(5000, "$('.spinner').not('visible')");
    }

    /**
     * @When /^I wait "([^""]*)" seconds$/
     *
     * @param integer $time
     */
    public function waitTime($time)
    {
        $this->getSession()->wait($time * 1000);
    }

    /**
     * @When /^I wait "([^""]*)" seconds until "([^""]*)"$/
     *
     * @param integer $time time in milliseconds
     * @param string $condition JS condition
     */
    public function waitTimeUntilCondition($time, $condition)
    {
        $this->getSession()->wait($time * 1000, $condition);
    }

    /**
     * @When /^I attach file "([^""]*)" to "([^""]*)"$/
     *
     * @param string $path path to file
     * @param string $locator input id, name or label
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function attachFile($path, $locator)
    {
        $path = realpath($this->getContainer()->getParameter('kernel.root_dir') . "/../" . $path);
        $page = $this->getSession()->getPage();
        $page->attachFileToField($locator, $path);
    }


    /**
     * @param string $radioLabel
     *
     * @throws ElementNotFoundException
     * @return void
     * @Given /^I select the "([^"]*)" radio button$/
     */
    public function iSelectTheRadioButton($radioLabel)
    {
        $radioButton = $this->getSession()->getPage()->findField($radioLabel);
        if (null === $radioButton) {
            throw new ElementNotFoundException($this->getSession(), 'form field', 'id|name|label|value', $radioLabel);
        }
        $value = $radioButton->getAttribute('value');
        $this->getSession()->getDriver()->click($radioButton->getXPath());
    }
}