<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 03/09/2015
 * Time: 10:58
 */

namespace AppBundle\Utils;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DateCalculator
{
    /**
     * @param string $string
     * @return \DateTime
     */
    public function getDay($string)
    {
        switch($string) {
            case 'today':
                $date = new \DateTime();
                break;
            case 'yesterday':
                $date = new \DateTime();
                $date->sub(new \DateInterval('P1D'));
                break;
            case 'first_day_of_the_week':
                $date = $this->getFirstDayOfTheWeek();
                break;
            case 'last_day_of_the_week':
                $date = $this->getLastDayOfTheWeek();
                break;
            case 'first_day_of_next_week':
                $date = $this->getFirstDayOfNextWeek();
                break;
            case 'first_day_of_the_month':
                $date = $this->getFirstDayOfTheMonth();
                break;
            case 'last_day_of_the_month':
                $date = $this->getLastDayOfTheMonth();
                break;
            case 'first_day_of_next_month':
                $date = $this->getFirstDayOfNextMonth();
                break;
            case 'first_day_of_previous_month':
                $date = $this->getFirstDayOfPreviousMonth();
                break;
            case 'last_day_of_previous_month':
                $date = $this->getLastDayOfPreviousMonth();
                break;
            case 'first_day_of_the_year':
                $date = $this->getFirstDayOfTheYear();
                break;
            default:
                $date = \DateTime::createFromFormat('Y-m-d', $string);
                break;
        }

        return $date;
    }

    /**
     * @return \DateTime
     */
    public function getFirstDayOfTheMonth()
    {
        $date = new \DateTime();
        $date
            ->setDate((int)$date->format('Y'), (int)$date->format('m'), 1)
            ->setTime(0, 0);
        
        return $date;
    }

    /**
     * @return \DateTime
     */
    public function getFirstDayOfNextMonth()
    {
        $firstDayOfTheMonth = $this->getFirstDayOfTheMonth();
        $date = clone($firstDayOfTheMonth);
        $date
            ->add(new \DateInterval('P1M'));
        
        return $date;
    }

    /**
     * @return \DateTime
     */
    public function getFirstDayOfPreviousMonth()
    {
        $firstDayOfTheMonth = $this->getFirstDayOfTheMonth();
        $date = clone($firstDayOfTheMonth);
        $date
            ->sub(new \DateInterval('P1M'));
        
        return $date;
    }

    /**
     * @return \DateTime
     */
    public function getFirstDayOfTheYear()
    {
        $firstDayOfTheMonth = $this->getFirstDayOfTheMonth();
        $date = clone($firstDayOfTheMonth);
        $nbMonthsToSubstract = (int)$firstDayOfTheMonth->format('m') - 1;
        $date
            ->sub(new \DateInterval('P'. $nbMonthsToSubstract .'M'));
        
        return $date;
    }

    /**
     * @return \DateTime
     */
    public function getLastDayOfTheMonth()
    {
        $firstDayOfTheMonth = $this->getFirstDayOfNextMonth();
        $date = clone($firstDayOfTheMonth);
        $date
            ->sub(new \DateInterval('P1D'));
        
        return $date;
    }

    /**
     * @return \DateTime
     */
    public function getLastDayOfPreviousMonth()
    {
        $firstDayOfTheMonth = $this->getFirstDayOfTheMonth();
        $date = clone($firstDayOfTheMonth);
        $date
            ->sub(new \DateInterval('P1D'));

        return $date;
    }

    /**
     * @return \DateTime
     */
    public function getFirstDayOfTheWeek()
    {
        $date = new \DateTime();
        $date->setTime(0, 0);
        $dayOfWeek = (int) $date->format('w');

        if ($dayOfWeek > 0) {
            $nbDayToSubstract = (int)$date->format('w') - 1;
        } else {
            // back from sunday to previous monday
            $nbDayToSubstract = 6;
        }
        $date->sub(new \DateInterval('P' . $nbDayToSubstract . 'D'));
        
        return $date;
    }

    /**
     * @return \DateTime
     */
    public function getLastDayOfTheWeek()
    {
        $firstDayOfTheWeek = $this->getFirstDayOfTheWeek();
        $date = clone($firstDayOfTheWeek);
        $date->add(new \DateInterval('P6D'));
        
        return $date;
    }

    public function getFirstDayOfNextWeek()
    {
        $firstDayOfTheWeek = $this->getFirstDayOfTheWeek();
        $date = clone($firstDayOfTheWeek);
        $date->add(new \DateInterval('P7D'));
        
        return $date;
    }

    /**
     * @return \DateTime
     */
    public function getFirstSecondOfToday()
    {
        /** First second of the day */
        $date = new \DateTime();
        $date
            ->setTime(0, 0);
        
        return $date;
    }

    /**
     * @return \DateTime
     */
    public function getFirstSecondOfTomorrow() {
        $firstSecondOfToday = $this->getFirstSecondOfToday();
        $date = clone($firstSecondOfToday);
        $date
            ->add(new \DateInterval('P1D'));
        
        return $date;
    }
}