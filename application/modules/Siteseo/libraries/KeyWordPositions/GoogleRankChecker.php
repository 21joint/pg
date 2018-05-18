<?php
if( @ini_set('max_execution_time', 1200) !== false ) {
  @ini_set('max_execution_time', 1200);
}

if( !class_exists('GoogleRankChecker') ) {

  class GoogleRankChecker
  {

    private $_googleSearchUrl = 'https://www.google.com/search?q=%s&num=50&start=0';

    public function find($keyword, $useproxie = false, $proxies = array())
    {
      $results = [];
      $i = 1;

      $ua = [
        0 => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201',
        1 => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36',
        2 => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1',
      ];
      $start = rand(0, 3);
      if( $useproxie ) {
        $host = $proxies['host'];
        $port = $proxies['port'];
        $username = $proxies['username'];
        $password = $proxies['password'];

        if( !empty($username) ) {
          $auth = base64_encode($username . ':' . $password);
          $useauth = sprintf('Proxy-Authorization: Basic %s', $auth);
        } else {
          $useauth = '';
        }

        $options = [
          'http' => [
            'method' => 'GET',
            'header' => "Accept-language: en\r\n" .
            "Cookie: PHP Google Keyword Position\r\n" .
            "User-Agent: " . $ua[$start] . "\r\n" .
            $useauth,
            'proxy' => sprintf('tcp://%s:%s', $host, $port),
            'request_fulluri' => true
          ]
        ];
      } else {
        $options = [
          'http' => [
            'method' => 'GET',
            'header' => "Accept-language: en\r\n" .
            "Cookie: PHP Google Keyword Position\r\n" .
            "User-Agent: " . $ua[$start]
          ]
        ];
      }
      $arrayproxies = [];
      $keyword = str_replace(' ', '+', trim($keyword));
      $url = sprintf($this->_googleSearchUrl, $keyword);
      if( $this->_isCurlEnabled() ) {
        $data = $this->_curl($url, $useproxie, $arrayproxies);
      } else {
        $context = stream_context_create($options);
        $data = @file_get_contents($url, false, $context);
      }

      if( is_array($data) ) {
        $errmsg = $data['errmsg'];
        $results = ['rank' => 'zero', 'url' => $errmsg];
      } else {
        if( strpos($data, 'To continue, please type the characters below') !== false || $data == false || strpos($data, "We're sorry") !== false ) {
          $results = ['rank' => 'zero', 'url' => ''];
        } else {
          if( class_exists('DOMDocument') ) {
            $dom = new Zend_Dom_Query($data);
            $citeQuery = $dom->query('cite[@class="_Rm"]');
            foreach( $citeQuery as $citeNode ) {
              $fixed_url = '//' . preg_replace('#^.*://#', '', $citeNode->textContent);
              $results[] = ['rank' => $i++, 'url' => $fixed_url, 'goolgeUrl' => $url];
            }
          } else {
            $j = -1;
            while( ($j = stripos($data, '<cite class="_Rm">', $j + 1)) !== false ) {
              $k = stripos($data, '</cite>', $j);
              $link = strip_tags(substr($data, $j, $k - $j));
              $link = '//' . preg_replace('#^.*://#', '', $link);
              $results[] = ['rank' => $i++, 'url' => $link, 'goolgeUrl' => $url];
            }
          }
        }
      }

      return $results;
    }

    private function _isCurlEnabled()
    {
      return function_exists('curl_version');
    }

    private function _curl($url, $useproxie, $arrayproxies)
    {
      try {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246');
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 'all');

        if( $useproxie ) {
          if( !empty($arrayproxies) ) {
            foreach( $arrayproxies as $param => $val ) {
              curl_setopt($ch, $param, $val);
            }
          }
        }

        $content = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if( !$errno ) {
          return $content;
        } else {
          return [
            'errno' => $errno,
            'errmsg' => $error
          ];
        }
      } catch( Exception $e ) {
        return [
          'errno' => $e->getCode(),
          'errmsg' => $e->getMessage()
        ];
      }
    }

  }

}
