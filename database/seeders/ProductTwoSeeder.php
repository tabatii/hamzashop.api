<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductTwoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new Product;
        $product->long_title = 'Minoxidil Viñas 50 mg/ml solucion cutanea';
        $product->short_title = 'Minoxidil Viñas';
        $product->price = 400;
        $product->stock = 100;
        $product->images = [
            [
                'path' => 'products/m9uEVCKgf4Y-sjJF8EWb/c3IVhiKe3m.jpg',
                'url' => 'https://firebasestorage.googleapis.com/v0/b/hamzashop-afb8f.appspot.com/o/products%2Fm9uEVCKgf4Y-sjJF8EWb%2Fc3IVhiKe3m.jpg?alt=media&token=f294cb6d-a8a0-404d-bb0c-1a102c7713bd'
            ],
            [
                'path' => 'products/m9uEVCKgf4Y-sjJF8EWb/TdPSs-Le0i.jpg',
                'url' => 'https://firebasestorage.googleapis.com/v0/b/hamzashop-afb8f.appspot.com/o/products%2Fm9uEVCKgf4Y-sjJF8EWb%2FTdPSs-Le0i.jpg?alt=media&token=7b876e66-6335-4c19-ba96-5784c7ae3dd6'
            ],
            [
                'path' => 'products/m9uEVCKgf4Y-sjJF8EWb/jmMFZWGbqT.jpg',
                'url' => 'https://firebasestorage.googleapis.com/v0/b/hamzashop-afb8f.appspot.com/o/products%2Fm9uEVCKgf4Y-sjJF8EWb%2FjmMFZWGbqT.jpg?alt=media&token=f2b37cfd-cef2-4c66-b4ed-dad8e082eda2'
            ],
        ];
        $product->description = "<p>Minoxidil is a medication used for the treatment of male-pattern hair loss. It is an antihypertensive vasodilator. It is available as a generic medication and over the counter.</p><p>Minoxidil is scientifically proven to improve hair growth and potentially help men with male pattern baldness regrow lost hair. In the tests used to secure FDA approval, minoxidil was primarily tested on the top of the scalp and crown, resulting in a common belief that it only works on these areas.</p><p>Minoxidil works by improving blood flow to the area in which it's applied. Apply it to your scalp and it can potentially improve the supply of blood and nutrients to hair follicles, improving hair density and increasing the rate of growth. As such, there's no reason minoxidil shouldn't work for a receding hairline.</p><p>Also, it does stimulate hair growth, although scientists aren't quite sure how it works. Minoxidil is available as Rogaine or Theroxidil, or in generic form effectiveness: Minoxidil works for about 2 out of 3 men. It's most effective if you're under age 40 and have only recently started to lose your hair.</p><p>While the exact mechanism of action for minoxidil the active ingredient isn't actually clear, it's believed to work by partially enlarging hair follicles and elongating the growth phase of hair. With more follicles in the growth phase, you'll see more hair coverage on your scalp.</p><p>Increased hair loss, one of the most publicized side effects of minoxidil, is often the result of hair follicles rapidly moving through the hair growth cycle and shedding before an anagen phase. There are also side effects of minoxidil that can occur from excessive use of the medication.</p><p>Most studies indicate that the results from minoxidil usually start to show after six months, with few or no visible results in the first three months of use. There are also studies that don't show any results after three months, indicating that minoxidil can take some time to start working.</p><p>Minoxidil is a Health Canada and US FDA-approved medication for hair loss in men and women. The drug is marketed as 2% and 5% topical solutions. This over-the-counter product is considered safe but should be used with caution. Furthermore, minoxidil is an orally active vasodilator for the treatment of severe hypertension.</p>";
        $product->status = 'public';
        $product->save();
    }
}
