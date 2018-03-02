<?php

namespace App\Http\Controllers;


class BundlesController extends Controller
{
    protected $json = [];

    protected $bundles = [];

    public function listBroadBand()
    {
        $json = file_get_contents(resource_path('database/data.json'));
        $json = json_decode($json, true);
//        $broadband = collect($json)->where('type', '=', 'bb');
//        $landline = collect($json)->where('type', '=', 'll');
//        $tv = collect($json)->where('type', '=', 'tv');
//        $addon = collect($json)->where('type', '=', 'addon');
        $bundles = [];
        $this->json = $json;
        for ($i = 0; $i < count($json); $i++) {
            if($json[$i]['type'] != 'bb') {
                continue;
            }

            $bundles[$i]['items'] = [$json[$i]];
            $bundles[$i]['title'] = $json[$i]['title'];
            $bundles[$i]['price'] = $json[$i]['price'];
            $bundles[$i]['hasType'][] = $json[$i]['type'];
            if (isset($json[$i]['extras'])) {
                $bundles[$i]['extras'] = $json[$i]['extras'];
            }
            for ($a = 0; $a < count($json); $a++) {
                $discount = 0;
                if($i == $a || $json[$i]['type'] == $json[$a]['type']) {
                    continue;
                }

                if (isset($json[$i]['extras'])) {
                    $discount = $this->findExtra($json[$i]['extras'], $json[$a]['id']);
                }
                array_push($bundles[$i]['items'], $json[$a]);
            }
        }
        $discount = 0;
        foreach ($bundles as $bundle) {
            foreach ($bundle['items'] as $item) {
                if($bundle['title'] == $item['title']) {
                    continue;
                }

                if(in_array($item['type'], $bundle['hasType'])){
                    continue;
                }

                if($item['title'] == 'AddonTV' && ! in_array('tv', $bundle['hasType'])) {
                    continue;
                }

                if (isset($bundle['items'][0]['extras'])) {
                    $discount = $this->findExtra($bundle['items'][0]['extras'], $item['id']);
                }
                $hasType = array_merge($bundle['hasType'], [$item['type']]);
                $this->bundles[] = [
                    'title' => $bundle['title'] . ' + ' . $item['title'],
                    'price' => $bundle['price'] + $item['price'] + $discount,
                    'hasType' => $hasType,
                    'extras' => $bundle['extras'],
                ];

            }
        }
        foreach ($this->bundles as $bundle) {
            foreach ($json as $item) {
                if($bundle['title'] == $item['title']) {
                    continue;
                }

                if($item['type'] == 'bb') {
                    continue;
                }

                if(in_array($item['type'], $bundle['hasType'])){
                    continue;
                }

                if($item['title'] == 'AddonTV' && ! in_array('tv', $bundle['hasType'])) {
                    continue;
                }

                if (isset($bundle['extras'])) {
                    $discount = $this->findExtra($bundle['extras'], $item['id']);
                }
                $hasType = array_merge($bundle['hasType'], [$item['type']]);
                $this->bundles[] = [
                    'title' => $bundle['title'] . ' + ' . $item['title'],
                    'price' => $bundle['price'] + $item['price'] + $discount,
                    'hasType' => $hasType,
                    'extras' => $bundle['extras'],
                ];

            }
        }

        foreach ($this->bundles as $bundle) {
            foreach ($json as $item) {
                if(stripos($bundle['title'], $item['title'])) {
                    continue;
                }

                if($item['type'] == 'bb') {
                    continue;
                }

                if(in_array($item['type'], $bundle['hasType']) && $item['type'] != 'addon'){
                    continue;
                }

                if($item['title'] == 'AddonTV' && ! in_array('tv', $bundle['hasType'])) {
                    continue;
                }

                if (isset($bundle['extras'])) {
                    $discount = $this->findExtra($bundle['extras'], $item['id']);
                }
                $hasType = array_merge($bundle['hasType'], [$item['type']]);
                $this->bundles[] = [
                    'title' => $bundle['title'] . ' + ' . $item['title'],
                    'price' => $bundle['price'] + $item['price'] + $discount,
                    'hasType' => $hasType,
                    'extras' => $bundle['extras'],
                ];

            }
        }

        $collect = collect($this->bundles)->sortBy('price')->unique()->values()->all();
//        dd($collect);
        return response()->json($collect, 200);
    }

    /**
     * @return array
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param array $json
     */
    public function setJson($json)
    {
        $this->json = $json;
    }

//    protected function mountBundles($title = null, $price = 0, $index = 0)
//    {
//        if(! isset($this->json[$index])) {
//            return null;
//        }
//
//        if($this->json[$index]['type'] == 'bb') {
////            $collect = collect($this->bundles)->where('title', 'LIKE', '%Broadband');
////            if (count($collect) > 0) {
//////                dd($collect);
////                return null;
////            }
//        }
//
//        foreach ($this->json as $item) {
//
//        }
//        $discount  = isset($this->json[$index]['extras']) ? $this->findExtra($this->json[$index]['extras'], $this->json[$index]['id']) : 0;
//
//        $title = $title . $this->json[$index]['title'];
//        $price = $this->json[$index]['price'] + $price + $discount;
//        $this->bundles[] = [
//            'title' => $title,
//            'price' => $price,
//        ];
//        $index++;
//        if(isset($this->json[$index])) {
//            $return = $this->mountBundles($title . ' - ', $price, $index);
//            if(is_null($return)) {
//                $index++;
//                $this->mountBundles($title . ' - ', $price, $index);
//            }
//        }
//
//        return $this->bundles;
//
//    }

    protected function findExtra(array $extra, $id)
    {
        $collect = collect($extra)->where('productId', $id);
        if(count($collect) > 0) {
            return $collect->first()['cost'];
        }

        return 0;
    }
}