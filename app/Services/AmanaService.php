<?php

namespace App\Services;

class AmanaService
{
    private $price;
    private $data = [
        ['weight' => 5, 'price' => 50],
        ['weight' => 10, 'price' => 65],
        ['weight' => 15, 'price' => 75],
        ['weight' => 20, 'price' => 85],
    ];

    public function price($weight)
    {
        foreach ($this->data as $row):
            if ($weight <= $row['weight']) {
                $this->price = $row['price'];
                break;
            }
        endforeach;
        return $this->price;
    }
}
