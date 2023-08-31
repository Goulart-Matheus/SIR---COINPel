<? 

    class Layout
    {
        private $Title;
        private $Secretary;
        private $Module;
        private $Param;
        private $Context;

        public function __construct($title , $secretary , $module)
        {
            $this->Title        = $title;
            $this->Secretary    = $secretary;
            $this->Module       = $module;
            $this->Param        = http_build_query
                                  (
                                      array
                                      (
                                          "title"         => $this->Title       , 
                                          "module"        => $this->Module      ,
                                          "secretary"     => $this->Secretary   ,
                                      )
                                  );

            $this->Context      = stream_context_create
                                (
                                    array
                                    (
                                        'http' => 
                                                    array
                                                    (
                                                        'method'    => 'POST'                                                                                                        ,
                                                        'content'   => $this->Param                                                                                                  ,
                                                        'header'    => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen($this->Param) . "\r\n"                                                          ,
                                                    )
                                    )
                                );
        }

        public function getHeader()
        {
            return file_get_contents('https://cdn.coinpel.com.br/layout/PUBLIC_header.php', null, $this->Context);
        }

        public function getFooter()
        {
            return file_get_contents('https://cdn.coinpel.com.br/layout/PUBLIC_footer.php', null, $this->Context);
        }

    }

?>