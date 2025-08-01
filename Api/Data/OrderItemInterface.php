<?php
namespace Sizebay\SizebayTracker\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterfaace;

interface OrderItemInterface extends ExtensibleDataInterface
{
    public function getSku(): ?string;
    public function setSku(string $sku): self;

    public function getQuantity(): ?int;
    public function setQuantity(int $qty): self;

    public function getPrice(): ?float;
    public function setPrice(float $price): self;

    public function getPermalink(): ?string;
    public function setPermalink(string $permalink): self;

    public function getSize(): ?string;
    public function setSize(string $size): self;

    public function getFeedProductId(): ?int;
    public function setFeedProductId(int $id): self;
}
