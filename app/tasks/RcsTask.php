<?php

use Phalcon\Cli\Task;

class RcsTask extends Task {

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
            $this->config->rcs->sitemapFile,
            $this->config->rcs->priceUrls,
            $this->config->rcs->priceList,
        ];
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    private function sitemap()
    {
        $sitemapUrl = $this->config->rcs->sitemapUrl;
        $links = $this->sitemapXmlParse($sitemapUrl);
        if ($fp = fopen($this->config->rcs->priceUrls,'w')) {
            foreach ($links as $link) {
                fputs($fp, $link . PHP_EOL);
            }
            fclose($fp);
        }
    }

    private function gethtml()
    {
        if (!file_exists($this->config->rcs->htmlsDir)) {
            mkdir($this->config->rcs->htmlsDir);
        }
        $urls = file($this->config->rcs->priceUrls);
        foreach ($urls as $url) {
            $url = trim(strtolower($url));
            $md5 = md5($url);
            $htmlFile = $this->config->rcs->htmlsDir . $md5 . '.html';
            if (!file_exists($htmlFile) && $fp = fopen($htmlFile,'w')) {
                echo ++$i."\t".$md5."\t".$url."\n";
                $html = file_get_contents($url);
                fputs($fp,$html);
                fclose($fp);
            }
        }
    }

    private function parse()
    {
        echo $this->config->rcs->priceUrls."\n";
        $urls = file($this->config->rcs->priceUrls);
        $i = 0;
        $fp = fopen($this->config->rcs->priceList,'w');
        foreach ($urls as $url) {
            $url = trim(strtolower($url));
            $md5 = md5($url);
            $i++;
            $htmlFile = $this->config->rcs->htmlsDir . $md5 . '.html';
            if (file_exists($htmlFile)) {
                $data=[];
                $data['price']='';
                if (preg_match('/(\d+)\.html/',$url,$reg)) {
                    $data['product_id'] = $reg[1];
                }
                $html = file($htmlFile);
                foreach($html as $i => $line){
                    if (preg_match('/itemprop="(\w+)" content="(.*?)">/',$line,$reg)) {
                        $data[$reg[1]] = $reg[2];
                    } elseif (preg_match('/itemprop="(\w+)">(.*?)\</',$line,$reg)) {
                        $data[$reg[1]] = $reg[2];
                    } elseif (preg_match('/1\+\&nbsp;<\/td><td><b>(.+?)\&/',$line,$reg)) {
                        $data['price'] = $reg[1];
                        break;
                    }
                }
                $data['url'] = $url;
                if(isset($data['name']) && $data['price']) {
                    $data['name'] = trim(substr($data['name'], 26));
                    if (isset($data['product_id'])) {
                        echo $i . "\t" . $md5 . "\t" . $url . "\n";
                        $line = $data['product_id'] . "\t" . $data['name'] . "\t" . $url . "\t" . $data['price'] . "\n";
                        fputs($fp, $line);
                    }
                }
            }
        }
        fclose($fp);
    }

    private function update()
    {
        $site = Sites::findFirstByName('rcs');
        $lines = file($this->config->rcs->priceList);
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

    private function sitemapXmlParse($url, $page='')
    {
        echo $page."\t".$url."\n";
        $urls = array();
        $sitemapFile = sprintf($this->config->rcs->sitemapFile, $page);
        echo $sitemapFile."\n";
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
            if ($page=='' && preg_match('/sitemap_rcsproducts_page(\d+)/',$url, $reg)) {
                $urls +=  $this->sitemapXmlParse($url, $reg[1]);
            } elseif (!in_array($url, $urls)) {
                $urls[] = $url;
            }
        }
        return $urls;
    }

}
