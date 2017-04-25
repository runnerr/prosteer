<?php

use Phalcon\Cli\Task;

class ProsteerTask extends Task {


    public function mainAction()
    {
        echo "Import CSV\n";
        $this->import();
        echo "items code calc\n";
        $this->updatecode();
    }

    private function import()
    {
        //Truncate table
        $prods = Products::find();
        $prods->delete();

        $lines = file($this->config->prosteer->masteramCsv);
        foreach ($lines as $line) {
            list($product_id, $brand, $model, $title, $site2, $status, $status2, $price, $price2) = explode("\t", $line . "\t\t\t\t\t\t", 9);
            if ($product_id>0) {
                $code = $model;
                $code2 = '';
                if (preg_match('/(.*)\((.*)\)/',$model,$reg)) {
                    $code = $reg[1];
                    $code2 = $reg[2];
                }
                $code = strtolower(preg_replace("/\W/", '', $code));
                $code2 = strtolower(preg_replace("/\W/", '', $code2));
                $item = new Products();
                $item->id = $product_id;
                $item->brand = $brand;
                $item->model = $model;
                $item->title = $title;
                $item->price = (double)$price;
                $item->price2 = (double)$price2;
                $item->code = $code;
                $item->code2 = $code2;
                $item->create();
            }
        }
        echo "Import done!\n";
    }

    private function reset()
    {
        $files = [
            $this->config->prosteer->sitemapFile,
            $this->config->prosteer->priceUrls,
            $this->config->prosteer->priceList,
        ];
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    private function sitemap()
    {
        $sitemapUrl = $this->config->prosteer->sitemapUrl;
        $links = $this->sitemapXmlParse($sitemapUrl);
        if ($fp = fopen(APP_PATH.'/app/cache/prosteer_urls.txt','w')) {
            foreach ($links as $link) {
                fputs($fp, $link . PHP_EOL);
            }
            fclose($fp);
        }
        echo "ok";
    }

    private function sitemapXmlParse($url)
    {
        $urls = array();
        echo APP_PATH."\n\n";
        if (!file_exists($this->config->prosteer->sitemapFile) && $fp = fopen($this->config->prosteer->sitemapFile,'w')) {
            $xml = file_get_contents($url);
            fputs($fp, $xml);
            fclose($fp);
        } else {
            $xml = file_get_contents($this->config->prosteer->sitemapFile);
        }
        $DomDocument = new DOMDocument();
        $DomDocument->preserveWhiteSpace = false;
        $DomDocument->loadXml($xml);
        $DomNodeList = $DomDocument->getElementsByTagName('loc');
        foreach($DomNodeList as $url) {
            if (preg_match('/\/uk\//', $url->nodeValue, $reg)) {
                $urls[] = $url->nodeValue;
            }

        }
        return $urls;
    }

    private function updatecode()
    {
        $alls = Items::find();
        foreach($alls as $item){
            $item->code = strtolower(preg_replace("/\W/", '', $item->name));
            $item->code .= strtolower(preg_replace("/\W/", '', preg_replace("/.*\//", '', $item->url)));
            $item->code = substr($item->code,0,150);
            $item->save();
        }
    }

}