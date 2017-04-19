<?php

use Phalcon\Cli\Task;

class GtestTask extends Task {

    public function mainAction()
    {
        echo "Reset data\n";
        $this-reset();
        echo "Import sitemap\n";
        $this-sitemap();
        echo "Downdload htmls\n";
        $this-gethtml();
        echo "Parse html\n";
        $this-parse();
        echo "Update data\n";
        $this->update();
    }

    private function reset()
    {
        $files = [
            $this->config->gtest->sitemapFile,
            $this->config->gtest->priceUrls,
            $this->config->gtest->priceList,
        ];
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    private function sitemap()
    {
        $sitemapUrl = $this->config->gtest->sitemapUrl;
        $links = $this->sitemapXmlParse($sitemapUrl);
        if ($fp = fopen($this->config->gtest->priceUrls,'w')) {
            foreach ($links as $link) {
                fputs($fp, $link . PHP_EOL);
            }
            fclose($fp);
        }
    }

    private function gethtml()
    {
        $urls = file(dirname($this->config->gtest->priceUrls));
        if (!file_exists($this->config->gtest->htmlsDir)) {
            mkdir($this->config->gtest->htmlsDir);
        }
        $i = 0;
        foreach ($urls as $url) {
            $url = trim(strtolower($url));
            $md5 = md5($url);
            $htmlFile = $this->config->gtest->htmlsDir . $md5 . '.html';
            $i++;
            if (!file_exists($htmlFile) && $fp = fopen($htmlFile,'w')) {
                echo $i."\t".$md5."\t".$url."\n";
                $html = file_get_contents($url);
                fputs($fp,$html);
                fclose($fp);
            }
        }
    }

    private function parse()
    {
        echo $this->config->gtest->priceUrls."\n";
        $urls = file($this->config->gtest->priceUrls);
        $i = 0;
        $fp = fopen($this->config->gtest->priceList,'w');
        foreach ($urls as $url) {
            $url = trim(strtolower($url));
            $md5 = md5($url);
            $i++;
            $htmlFile = $this->config->gtest->htmlsDir . $md5 . '.html';
            if (file_exists($htmlFile)) {
                $data=[];
                $html = file($htmlFile);
                foreach($html as $i => $line){
                    //
                    if (preg_match('/<title>(.*?) купить/',$line,$reg)) {
                        $data['title'] = $reg[1];
                    }
                    if (preg_match('/input type="hidden" name="(\w+)".*?value="(.+?)"/',$line,$reg)) {
                        $data[$reg[1]] = $reg[2];
                    }
                }
                if (isset($data['product_id'])) {
                    echo $i."\t".$md5."\t".$url."\n";
                    $line = $data['product_id']."\t".$data['title']."\t".$url."\t".$data['price_value']."\n";
                    fputs($fp,$line);
                }
            }
        }
        fclose($fp);
    }

    private function update()
    {
        $site = Sites::findFirstByName('gtest');
        $lines = file($this->config->gtest->priceList);
        foreach ($lines as $line) {
            echo $line."\n";
            list($product_id, $name, $url, $price) = explode("\t", $line);
            echo "$product_id, $name, $url, $price\n";
            $item = new Items();
            $item->product_id = $product_id;
            $item->site_id = $site->id;
            $item->name = $name;
            $item->url = $url;
            $item->price = (double) $price;
            echo $item->price."\n";
            $item->create();
        }
    }

    private function sitemapXmlParse($url)
    {
        $urls = array();
        $sitemapFile = $this->config->gtest->sitemapFile;
        if (!file_exists($sitemapFile) && $fp = fopen($sitemapFile,'w')) {
            $xml = file_get_contents($url);
            fputs($fp, $xml);
            fclose($fp);
        } else {
            $xml = file_get_contents($sitemapFile);
        }
        $DomDocument = new DOMDocument();
        $DomDocument->preserveWhiteSpace = false;
        $DomDocument->loadXml($xml);
        $DomNodeList = $DomDocument->getElementsByTagName('loc');
        foreach($DomNodeList as $noda) {
            $url = htmlspecialchars_decode($noda->nodeValue);
            if (!in_array($url, $urls)) {
                $urls[] = $url;
            }
        }
        return $urls;
    }

}
