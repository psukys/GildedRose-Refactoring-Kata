<?php

class GildedRose {

    private $items;

    function __construct($items) {
        $this->items = $items;
    }

    function update_quality() {
        foreach ($this->items as $item) {
            if ($item->name == 'Sulfuras, Hand of Ragnaros') { // special case - no changes
                continue;
            }

            switch ($item->name) {
                case 'Aged Brie':
                    if ($item->sell_in > 0) {
                        $item->quality += 1;
                    } else {
                        $item->quality += 2; // expired 2x
                    }
                    break;
                case 'Backstage passes to a TAFKAL80ETC concert':
                    if ($item->sell_in <= 0) { // expired
                        $item->quality = 0;
                    } elseif ($item->sell_in <= 5) { // 3x 5 days before
                        $item->quality += 3;
                    } elseif ($item->sell_in <= 10) { // 2x 10 days before
                        $item->quality += 2;
                    } else {
                        $item->quality += 1;
                    }
                    break;
                default: // normal items
                    if ($item->sell_in > 0) {
                        $item->quality -= 1;
                    } else {
                        $item->quality -= 2;
                    }
            }

            $item->sell_in -= 1;

            if ($item->quality > 50) {
                $item->quality = 50;
            }

            if ($item->quality < 0) {
                $item->quality = 0;
            }
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

