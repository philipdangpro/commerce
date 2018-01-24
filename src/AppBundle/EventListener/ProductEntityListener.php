<?php
/**
 * Created by PhpStorm.
 * User: wabap2-14
 * Date: 17/01/18
 * Time: 10:32
 * à remove dans Go to Settings -> Editor -> File and Code Templates -> Includes (TAB) -> PHP File Header
 */

namespace AppBundle\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Entity\Product;

class ProductEntityListener
{
    public function postLoad(Product $product, LifecycleEventArgs $args){
//        dump('dans postload');
//        dump($product); //-image: img/product/0718fcf92e906f4be18a4bde672beba0.jpg/480/?37293
//
//        die;
    }

    public function prePersist(Product $product, LifecycleEventArgs $args){
        dump('dans prepersist');
//        exit;
    }



    public function postUpdate(Product $product, LifecycleEventArgs $args){
        dump('dans postupdate');
    }

}
