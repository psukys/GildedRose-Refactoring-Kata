<?php

require_once 'gilded_rose.php';

class GildedRoseTest extends PHPUnit\Framework\TestCase  {

    /**
     * Tests that SellIn degrades after each update
     * Given: a casual item with mid values
     * Expect: SellIn to be decreased by 1
     */
    function testSellInDegrade() {
        $this->fail();
    }

    /**
     * Tests that Quality degrades after each update
     * Given: a casual item with mid values
     * Expect: Quality to be decreased by 1
     */
    function testQualityDegrade() {
        $this->fail();
    }

    /**
     * Tests that Quality of Brie increases after each update
     * Given: Aged Brie with non-max Quality
     * Expect: Quality to be increased by 1
     */
    function testBrieQualityIncrease() {
        $this->fail();
    }

    /**
     * Tests that Sulfuras has persistant quality
     * Given: Sulfuras item
     * Expect: Quality persists to be 80 after updates
     */
    function testSulfurasQualityPersistance() {
        $this->fail();
    }

    /**
     * Tests that quality cannot go over 50 for Brie
     * Given: Aged Brie item with 50 quality
     * Expect: After update, quality stays 50
     */
    function testQualityMaxWithBrie() {
        $this->fail();
    }

    /**
     * Tests that quality cannot go over 50 for concert backpass
     * Given: Concert tickets with 50 quality
     * Expect: After update, quality stays 50
     */
    function testQualityMaxWithConcert() {
        $this->fail();
    }

    /**
     * Tests that quality for a concert with less than 50 for concert pass stops at 50,
     * when update step is higher
     * Given: Concert tickets with 49 quality
     * Expect: After update, quality tops to 50
     */
    function testQualityMaxWithConcertOverflow() {
        $this->fail();
    }

    /**
     * Tests that quality of normal item degradation
     * Given: Normal item with 0 quality
     * Expect: After update, quality stays 0
     */
    function testQualityMin() {
        $this->fail();
    }

    /**
     * Tests that quality of normal item degradation when expired
     * Given: Normal item with 0 quality and 0 sellin
     * Expect: After update, quality stays 0
     */
    function testQualityMinExpired() {
        $this->fail();
    }

    /**
     * Tests that quality of a normal expired item degrades down to 0 and no less
     * Given: normal item with 1 quality and 0 sellin (-2 quality per update)
     * Expect: After update, quality is 0
     */
    function testQualityMinExpiredUnderflow() {
        $this->fail();
    }

    /**
     * Tests that quality of a concert ticket increases
     * Given: Concert ticket with some quality, and selling > 10 days
     * Expect: After update, quality increases by 1
     */
    function testConcertNormalUpdate() {
        $this->fail();
    }

    /**
     * Tests that quality increase by double if less-eq than 10 days are left to sell.
     * Given: Concert ticket with some quality, and 5 < sellin <= 10 days
     * Expect: After update, quality increases by 2
     */
    function testConcert10dayUpdate() {
        $this->fail();
    }

    /**
     * Tests that quality increase by double if less-eq than 5 days are left to sell.
     * Given: Concert ticket with some quality, and 0 < sellin <= 5 days
     * Expect: After update, quality increases by 3
     */
    function testConcert5dayUpdate() {
        $this->fail();
    }

    /**
     * Tests that concert pass is worthless after the concert
     * Given: Concert ticket with some quality and 0 sellin
     * Expect: After update, quality is 0
     */
    function testConcert0dayUpdate() {
        $this->fail();
    }
}
