<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\OptionsResolver;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\Options;
use Webmozart\Assert\Assert;

/**
 * Using the hacky hack to distinct between option which wasn't set
 * and option which was set to empty.
 *
 * Usage:
 *
 *   $optionsResolver
 *     ->setDefault('option', LazyOption::randomOne($repository))
 *     ->setNormalizer('option', LazyOption::findOneBy($repository, 'code'))
 *   ;
 *
 *   Returns:
 *     - null if user explicitly set it (['option' => null])
 *     - random one if user skipped that option ([])
 *     - specific one if user defined that option (['option' => 'CODE'])
 *
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
final class LazyOption
{
    /**
     * @return \Closure
     */
    public static function randomOne(RepositoryInterface $repository)
    {
        return function (Options $options) use ($repository) {
            $objects = $repository->findAll();

            if ($objects instanceof Collection) {
                $objects = $objects->toArray();
            }

            Assert::notEmpty($objects);

            return $objects[array_rand($objects)];
        };
    }

    public static function randomOneImage(string $directory)
    {
        return function (Options $options) use ($directory) {
            $finder = new Finder();
            $files = $finder->files()->in($directory);
            $images = [];

            /* @var File $file */

            foreach ($files as $sourcePathName) {
                $file = new File($sourcePathName);
                $images[] = $file->getPathname();
            }

            Assert::notEmpty($images);

            return $images[array_rand($images)];
        };
    }

    /**
     * @param int $chanceOfRandomOne
     *
     * @return \Closure
     */
    public static function randomOneOrNull(RepositoryInterface $repository, $chanceOfRandomOne)
    {
        return function (Options $options) use ($repository, $chanceOfRandomOne) {
            if (mt_rand(1, 100) > $chanceOfRandomOne) {
                return null;
            }

            $objects = $repository->findAll();

            if ($objects instanceof Collection) {
                $objects = $objects->toArray();
            }

            return 0 === count($objects) ? null : $objects[array_rand($objects)];
        };
    }

    /**
     * @return \Closure
     */
    public static function randomOneImageOrNull(string $directory, int $chanceOfRandomOne)
    {
        return function (Options $options) use ($directory, $chanceOfRandomOne) {
            if (mt_rand(1, 100) > $chanceOfRandomOne) {
                return null;
            }

            $finder = new Finder();
            $files = $finder->files()->in($directory);
            $images = [];

            /* @var File $file */

            foreach ($files as $sourcePathName) {
                $file = new File($sourcePathName);
                $images[] = $file->getPathname();
            }

            return 0 === count($images) ? null : $images[array_rand($images)];
        };
    }

    /**
     * @param int $amount
     *
     * @return \Closure
     */
    public static function randomOnes(RepositoryInterface $repository, $amount)
    {
        return function (Options $options) use ($repository, $amount) {
            $objects = $repository->findAll();

            if ($objects instanceof Collection) {
                $objects = $objects->toArray();
            }

            $selectedObjects = [];
            for (; $amount > 0 && count($objects) > 0; --$amount) {
                $randomKey = array_rand($objects);

                $selectedObjects[] = $objects[$randomKey];

                unset($objects[$randomKey]);
            }

            return $selectedObjects;
        };
    }

    public static function randomOnesImage(string $directory, int $amount)
    {
        return function (Options $options) use ($directory, $amount) {
            $finder = new Finder();
            $files = $finder->files()->in($directory);
            $images = [];

            /* @var File $file */

            foreach ($files as $sourcePathName) {
                $file = new File($sourcePathName);
                $images[] = $file->getPathname();
            }

            $selectedImages = [];
            for (; $amount > 0 && count($images) > 0; --$amount) {
                $randomKey = array_rand($images);

                $selectedImages[] = $images[$randomKey];

                unset($images[$randomKey]);
            }

            return $selectedImages;
        };
    }

    /**
     * @return \Closure
     */
    public static function all(RepositoryInterface $repository)
    {
        return function (Options $options) use ($repository) {
            return $repository->findAll();
        };
    }

    /**
     * @param string $field
     *
     * @return \Closure
     */
    public static function findBy(RepositoryInterface $repository, $field)
    {
        return function (Options $options, $previousValues) use ($repository, $field) {
            if (null === $previousValues || [] === $previousValues) {
                return $previousValues;
            }

            Assert::isArray($previousValues);

            $resources = [];
            foreach ($previousValues as $previousValue) {
                if (is_object($previousValue)) {
                    $resources[] = $previousValue;
                } else {
                    $resources[] = $repository->findOneBy([$field => $previousValue]);
                }
            }

            return $resources;
        };
    }

    /**
     * @param string $field
     *
     * @return \Closure
     */
    public static function findOneBy(RepositoryInterface $repository, $field)
    {
        return function (Options $options, $previousValue) use ($repository, $field) {
            if (null === $previousValue || [] === $previousValue) {
                return $previousValue;
            }

            if (is_object($previousValue)) {
                return $previousValue;
            } else {
                return $repository->findOneBy([$field => $previousValue]);
            }
        };
    }
}
