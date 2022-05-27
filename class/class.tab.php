<?
class Tab {
      var $tab   =array();                     //array contendo as tabs
      var $total =0;                           //total de tabs
      var $current_tab;                        //tab corrente
      var $color_background     ="#D3D3D3";    //cor do plano de fundo

	  var $img_background       ="bg_filete.jpg";           //imagem de fundo
	  
	  var $class_tab            ="active";

      var $color_tabselected    ="#EFEFEF";    //cor da tab selecionada
      var $color_tabunselected  ="#FFFFFF";    //cor da tab nao-selecionada
      var $color_tabhover       ="#BBCBFF";    //cor da tab ativa
      
      var $color_fontselected   ="#333333";    //cor da fonte na tab selecionada
      var $color_fontunselected ="#888888";    //cod da fonte na tab nao selecionada
      var $color_fonthover      ="#000000";    //cor da fonte ativa
      
      var $color_utabselected   ="#FFFFFF";    //cor da linha da tab selecionada
      var $color_utabunselected ="#C0C0C0";    //cor da linha da tab nao-selecionada
      var $color_utabhover      ="#000000";    //cor da linha ativa

      /********************
      * Interface Pública *
      *********************/
      function Tab() {
          $this->total=0;
      }

      function setTab($id, $icon, $href, $valor=0, $tooltip="") {
          $this->tab[$this->total++] =array($id, $icon, $href, $valor,$tooltip);
      }

      function getTab($indice) {
          return $this->tab[$indice];
      }
	  
	  function printTab($href) {
          $this->current_tab =$this->getTabSelected($href);
          ?>
          <div class="pl-4">
              <ul class="nav nav-tabs">
                  <?
                  for ($i=0; $i < $this->getTotal(); $i++) {
                      ?>
                      <li class="nav-item">
                          <a class="nav-link <?=$this->getTabClass($i)?>" href="<?=$this->getTabHref($i)?>"><?=$this->getTabIcon($i)." ".$this->getTabName($i).$this->getTabVal($i)?></a>
                      </li>
                      <?
                  }
                  ?>
              </ul>
          </div>
          <?
      }

      /********************
      * Interface Privada *
      *********************/

      function getTabName($indice){
          return $this->tab[$indice][0];
      }

      function getTabIcon($indice){
          return '<i class="'.$this->tab[$indice][1].'"></i>';
      }


      function getTabHref($indice){
          return $this->tab[$indice][2];
      }

      function getTabVal($indice){
          if ($this->tab[$indice][3]>0) return " <span class='tabspan'>" .$this->tab[$indice][2]. "</span>";
          return("");
      }
      function getTabToolTip($indice){
          return $this->tab[$indice][4];
      }
	  
	  
	  function getTabClass($id) {
          if ($id == $this->current_tab)
              return $this->class_tab;
          else
              return $this->color_untab;
      }


      function getUTabColor($id) {
          if ($id == $this->current_tab)
              return $this->color_utabselected;
          else
              return $this->color_utabunselected;
      }

      function getTabSelected($href){
          for ($i=0; $i < $this->getTotal(); $i++)
              if ($this->getTabHref($i) == $href)
                  return $i;
      }

      function getTotal() {
          return $this->total;
      }
}
?>
