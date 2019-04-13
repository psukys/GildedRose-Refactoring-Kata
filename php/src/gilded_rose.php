<?php

class GildedRose {

    private $items;

    function __construct($items) {
        $this->items = $items;
    }

    /**
     * Updates Aged Brie quality.
     * @param Item $item Aged brie item
     */
    function update_brie_quality($item) {
        if ($item->sell_in > 0) {
            $item->quality += 1;
        } else {
            $item->quality += 2; // expired 2x
        }
    }

    /**
     * Update concert pass quality.
     * @param Item $item concert pass item
     */
    function update_concert_pass_quality($item) {
        if ($item->sell_in <= 0) { // expired
            $item->quality = 0;
        } elseif ($item->sell_in <= 5) { // 3x 5 days before
            $item->quality += 3;
        } elseif ($item->sell_in <= 10) { // 2x 10 days before
            $item->quality += 2;
        } else {
            $item->quality += 1;
        }
    }

    /**
     * Update conjured item's quality.
     * @param Item $item conjured item
     */
    function update_conjured_quality($item) {
        // from requirements: degrades twice as fast as normal item.
        $this->update_normal_item_quality($item, 2);
    }

    /**
     * Update normal (without special features) item's quality.
     * @param Item $item item to be updated
     */
    function update_normal_item_quality($item, $degrade_scale) {
        if ($item->sell_in > 0) {
            $item->quality -= $degrade_scale;
        } else { // expired -2x
            $item->quality -= 2 * $degrade_scale;
        }
    }

    /**
     * Check item's quality and adjusts it if it goes out of defined range.
     * @param Item $item item that will be checked
     * @param int $min minimum value in range
     * @param int $max maximum value in range
     */
    function fix_quality_by_range($item, $min, $max) {
        if ($item->quality > $max) {
            $item->quality = $max;
        }

        if ($item->quality < $min) {
            $item->quality = $min;
        }
    }

    function update_quality() {
        foreach ($this->items as $item) {
            if ($item->name == 'Sulfuras, Hand of Ragnaros') { // special case - no changes
                continue;
            }

            switch ($item->name) {
                case 'Aged Brie':
                    $this->update_brie_quality($item);
                    break;
                case 'Backstage passes to a TAFKAL80ETC concert':
                    $this->update_concert_pass_quality($item);
                    break;
                case (preg_match('/Conjured/i', $item->name) ? true : false):
                    $this->update_conjured_quality($item);
                    break;
                default: // normal items
                    $this->update_normal_item_quality($item, 1);
            }

            $item->sell_in -= 1;
            $this->fix_quality_by_range($item, 0, 50);
        }
    }
}

class Item {

    public $name;
    public $sell_in;
    public $quality;

    function __construct($name, $sell_in, $quality) {
        $this->name = $name;
        $this->sell_in = $sell_in;
        $this->quality = $quality;
    }

    public function __toString() {
        return "{$this->name}, {$this->sell_in}, {$this->quality}";
    }

}

