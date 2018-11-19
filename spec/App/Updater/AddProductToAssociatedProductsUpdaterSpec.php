<?php

namespace spec\App\Updater;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductAssociationInterface;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class AddProductToAssociatedProductsUpdaterSpec extends ObjectBehavior
{
    function let(FactoryInterface $productAssociationFactory)
    {
        $this->beConstructedWith($productAssociationFactory);
    }

    function it_adds_a_product_to_each_associated_products(
        ProductInterface $product,
        ProductAssociationInterface $association,
        ProductAssociationTypeInterface $associationType,
        ProductInterface $associatedProduct,
        ProductAssociationInterface $associatedProductAssociation,
        ProductAssociationTypeInterface $associatedProductAssociationType
    ): void {
        $association->getType()->willReturn($associationType);

        $association->getAssociatedProducts()->willReturn(new ArrayCollection([
            $associatedProduct->getWrappedObject(),
        ]));

        $product->getId()->willReturn(1);
        $associatedProduct->getId()->willReturn(2);

        $associatedProduct->getAssociations()->willReturn(new ArrayCollection([
            $associatedProductAssociation->getWrappedObject(),
        ]));

        $associatedProductAssociation->getType()->willReturn($associatedProductAssociationType);
        $associatedProductAssociationType->getCode()->willReturn('XYZ');
        $associationType->getCode()->willReturn('XYZ');

        $associatedProductAssociation->addAssociatedProduct($product)->shouldBeCalled();

        $this->update($product, $association);
    }

    function it_skips_associated_product_if_it_is_the_current_product(
        ProductInterface $product,
        ProductAssociationInterface $association,
        ProductAssociationTypeInterface $associationType,
        ProductInterface $associatedProduct,
        ProductAssociationInterface $associatedProductAssociation
    ): void {
        $association->getType()->willReturn($associationType);

        $association->getAssociatedProducts()->willReturn(new ArrayCollection([
            $associatedProduct->getWrappedObject(),
        ]));

        $product->getId()->willReturn(1);
        $associatedProduct->getId()->willReturn(1);

        $associatedProductAssociation->addAssociatedProduct($product)->shouldNotBeCalled();

        $this->update($product, $association);
    }
}
