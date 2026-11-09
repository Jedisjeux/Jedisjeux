<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Unit\Form\Extension;

use App\Form\EventSubscriber\AddUserFormSubscriber;
use App\Form\Extension\CustomerTypeExtension;
use App\Form\Type\Customer\CustomerType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class CustomerTypeExtensionTest extends TestCase
{
    public function testItExtendsAnAbstractTypeExtension(): void
    {
        $this->assertInstanceOf(AbstractTypeExtension::class, new CustomerTypeExtension());
    }

    public function testItExtendsCustomerType(): void
    {
        $this->assertSame([CustomerType::class], CustomerTypeExtension::getExtendedTypes());
    }

    public function testItAddsUserFormSubscriber(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);

        $builder->expects($this->once())
            ->method('addEventSubscriber')
            ->with($this->equalTo(new AddUserFormSubscriber()))
            ->willReturnSelf();

        (new CustomerTypeExtension())->buildForm($builder, []);
    }
}
