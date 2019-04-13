<?php

require_once 'gilded_rose.php';

class GildedRoseTest extends PHPUnit\Framework\TestCase  {

    private $foo;
    private $brie;
    private $concert;
    private $sulfuras;

    function setUp() {
        $this->foo = new Item('foo', 10, 10);
        $this->brie = new item('Aged Brie', 10, 10);
        $this->concert = new Item('Backstage passes to a TAFKAL80ETC concert', 10, 10);
        $this->sulfuras = new Item('Sulfuras, Hand of Ragnaros', 10, 80);
    }

    /**
     * Tests that SellIn degrades after each update
     * Given: a casual item with mid values
     * Expect: SellIn to be decreased by 1
     */
    function testSellInDegrade() {
        $sellin = 10;
        $this->foo->sell_in = $sellin;
        $target = new GildedRose([$this->foo]);
        $target->update_quality();
        $this->assertEquals($sellin - 1, $this->foo->sell_in);
    }

    /**
     * Tests that Quality degrades after each update
     * Given: a casual item with mid values
     * Expect: Quality to be decreased by 1
     */
    function testQualityDegrade() {
        $quality = 10;
        $this->foo->quality = $quality;
        $target = new GildedRose([$this->foo]);
        $target->update_quality();
        $this->assertEquals($quality - 1, $this->foo->quality);
    }

    /**
     * Tests that Quality of Brie increases after each update
     * Given: Aged Brie with non-max Quality
     * Expect: Quality to be increased by 1
     */
    function testBrieQualityIncrease() {
        $quality = 10;
        $this->brie->quality = $quality;
        $target = new GildedRose([$this->brie]);
        $target->update_quality();
        $this->assertEquals($quality + 1, $this->brie->quality);
    }

    /**
     * Tests that quality of Brie increases by 2 when it's expired
     * Given: Aged Brie with non max quality and 0 sellin
     * Expect: After update - quality increases by 2
     */
    function testExpiredBrieDoubleQualityIncrease() {
        $quality = 10;
        $sellin = 0;
        $this->brie->quality = $quality;
        $this->brie->sell_in = $sellin;
        $target = new GildedRose([$this->brie]);
        $target->update_quality();
        $this->assertEquals($quality + 2, $this->brie->quality);
    }

    /**
     * Tests that Sulfuras has persistant quality
     * Given: Sulfuras item
     * Expect: Quality persists to be 80 after updates
     */
    function testSulfurasQualityPersistance() {
        $target = new GildedRose([$this->sulfuras]);
        $target->update_quality();
        $this->assertEquals(80, $this->sulfuras->quality);
    }

    /**
     * Tests that quality cannot go over 50 for Brie
     * Given: Aged Brie item with 50 quality
     * Expect: After update, quality stays 50
     */
    function testQualityMaxWithBrie() {
        $quality = 50;
        $this->brie->quality = $quality;
        $target = new GildedRose([$this->brie]);
        $target->update_quality();
        $this->assertEquals(50, $this->brie->quality);
    }

    /**
     * Tests that quality cannot go over 50 for concert backpass
     * Given: Concert tickets with 50 quality
     * Expect: After update, quality stays 50
     */
    function testQualityMaxWithConcert() {
        $quality = 50;
        $this->concert->quality = $quality;
        $target = new GildedRose([$this->concert]);
        $target->update_quality();
        $this->assertEquals(50, $this->concert->quality);
    }

    /**
     * Tests that quality for a concert with less than 50 for concert pass stops at 50,
     * when update step is higher
     * Given: Concert tickets with 49 quality, sellin <= 10
     * Expect: After update, quality tops to 50
     */
    function testQualityMaxWithConcertOverflow() {
        $quality = 49;
        $sellin = 10;
        $this->concert->quality = $quality;
        $this->concert->sell_in = $sellin;
        $target = new GildedRose([$this->concert]);
        $target->update_quality();
        $this->assertEquals(50, $this->concert->quality);
    }

    /**
     * Tests that quality of normal item degradation
     * Given: Normal item with 0 quality
     * Expect: After update, quality stays 0
     */
    function testQualityMin() {
        $quality = 0;
        $this->foo->quality = $quality;
        $target = new GildedRose([$this->foo]);
        $target->update_quality();
        $this->assertEquals(0, $this->foo->quality);
    }

    /**
     * Tests that quality of normal item degradation when expired
     * Given: Normal item with 0 quality and 0 sellin
     * Expect: After update, quality stays 0
     */
    function testQualityMinExpired() {
        $sellin = 0;
        $quality = 0;
        $this->foo->sell_in = $sellin;
        $this->foo->quality = $quality;
        $target = new GildedRose([$this->foo]);
        $target->update_quality();
        $this->assertEquals(0, $this->foo->quality);
    }

    /**
     * Tests that quality of a normal expired item degrades down to 0 and no less
     * Given: normal item with 1 quality and 0 sellin (-2 quality per update)
     * Expect: After update, quality is 0
     */
    function testQualityMinExpiredUnderflow() {
        $sellin = 0;
        $quality = 1;
        $this->foo->sell_in = $sellin;
        $this->foo->quality = $quality;
        $target = new GildedRose([$this->foo]);
        $target->update_quality();
        $this->assertEquals(0, $this->foo->quality);
    }

    /**
     * Tests that quality of a concert ticket increases
     * Given: Concert ticket with some quality, and selling > 10 days
     * Expect: After update, quality increases by 1
     */
    function testConcertNormalUpdate() {
        $sellin = 50;
        $quality = 10;
        $this->concert->sell_in = $sellin;
        $this->concert->quality = $quality;
        $target = new GildedRose([$this->concert]);
        $target->update_quality();
        $this->assertEquals($quality + 1, $this->concert->quality);
    }

    /**
     * Tests that quality increase by double if less-eq than 10 days are left to sell.
     * Given: Concert ticket with some quality, and 5 < sellin <= 10 days
     * Expect: After update, quality increases by 2
     */
    function testConcert10dayUpdate() {
        $sellin = 10;
        $quality = 10;
        $this->concert->sell_in = $sellin;
        $this->concert->quality = $quality;
        $target = new GildedRose([$this->concert]);
        $target->update_quality();
        $this->assertEquals($quality + 2, $this->concert->quality);
    }

    /**
     * Tests that quality increase by double if less-eq than 5 days are left to sell.
     * Given: Concert ticket with some quality, and 0 < sellin <= 5 days
     * Expect: After update, quality increases by 3
     */
    function testConcert5dayUpdate() {
        $sellin = 5;
        $quality = 10;
        $this->concert->sell_in = $sellin;
        $this->concert->quality = $quality;
        $target = new GildedRose([$this->concert]);
        $target->update_quality();
        $this->assertEquals($quality + 3, $this->concert->quality);
    }

    /**
     * Tests that concert pass is worthless after the concert
     * Given: Concert ticket with some quality and 0 sellin
     * Expect: After update, quality is 0
     */
    function testConcert0dayUpdate() {
        $sellin = 0;
        $quality = 10;
        $this->concert->sell_in = $sellin;
        $this->concert->quality = $quality;
        $target = new GildedRose([$this->concert]);
        $target->update_quality();
        $this->assertEquals(0, $this->concert->quality);
    }
}
