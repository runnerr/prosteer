<?php

use Phalcon\Cli\Task;

class CompareTask extends Task {

    public function mainAction()
    {
        echo "compare gtest\n";
        $this->compareSite(2);
        echo "compare rcs\n";
        $this->compareSite(3);
        echo "done!\n";

    }

    private function compareSite($site_id)
    {
        $prods = Products::find([
            'conditions' => 'brand<>\'\'',
            'order' => 'LENGTH(code) DESC, LENGTH(brand) DESC',
        ]);
        foreach($prods as $i => $prod) {
            $code = $prod->code;
            $items2 = Items::find([
                    'conditions' => 'site_id='.$site_id.' AND product_id=0 AND name LIKE \'%'.addslashes($prod->brand).'%\'',
                    'order' => 'LENGTH(code) DESC',
                ]
            );
            foreach ($items2 as $n => $item) {
                if (strlen($item->code) && strlen($code) && strpos($item->code, $code)!==FALSE ) {
                    echo
                        $prod->brand."\t".
                        $prod->model."\t".
                        $prod->price."\t".
                        $item->name."\t".
                        $item->price."\n";

                    $item->product_id = $prod->id;
                    $item->save();

                    break;
                }
            }
        }
    }

}
