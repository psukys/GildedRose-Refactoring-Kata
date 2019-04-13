<?php

class GildedRose {

    private $items;

    function __construct($items) {
        $this->items = $items;
    }

    function update_quality() {
        foreach ($this->items as $item) {
            if ($item->name == 'Sulfuras, Hand of Ragnaros') {
                continue;
            }

            // normal item degrade
            if ($item->name != 'Aged Brie' and $item->name != 'Backstage passes to a TAFKAL80ETC concert') {
                if ($item->quality > 0) {
                    $item->quality = $item->quality - 1;
                }
            // brie or concert upgrade
            } else {
                $item->quality = $item->quality + 1; // brie would end here
                if ($item->name == 'Backstage passes to a TAFKAL80ETC concert') {
                    if ($item->sell_in < 11) {
                        $item->quality = $item->quality + 1;
                    }
                    if ($item->sell_in < 6) {
                        $item->quality = $item->quality + 1;
                    }
                }
                if ($item->quality > 50) {
                    $item->quality = 50;
                }
            }
            
            // sellin degrade - can be negative!
            $item->sell_in = $item->sell_in - 1;
            
            if ($item->sell_in < 0) {
                if ($item->name != 'Aged Brie') {
                    if ($item->name != 'Backstage passes to a TAFKAL80ETC concert') {
                        if ($item->quality > 0) {
                            $item->quality = $item->quality - 1; // normal expired item -2 (-1 before)
                        }
                    } else {
                        $item->quality = 0; // after concert -> 0
                    }
                } else {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1; // undocumented -> expired upgrades twice
                    }
                }
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

