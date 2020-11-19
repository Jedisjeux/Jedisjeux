<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\Factory;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
interface ExampleFactoryInterface
{
    /**
     * @return object
     */
    public function create(array $options = []);
}
