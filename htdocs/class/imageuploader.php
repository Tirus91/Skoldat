<?php
                  
class imageuploader{
      protected $registry;
      private $file_addres = array();
      private $file_name = array();
      private $photo_dir = 'photos/';
      private $max_width;
      private $max_height;
      
      function __construct($registry){
          $this->registry = $registry;
      }
      
      /**
      * ošetření vstupu 
      * převedení UTF 8 na ansii (zbavení diakritiky)
      * odstranění určitých znaků
      * 
      * @param mixed $string
      */
      private function treatment($string){
          $string = str_replace(' ','_',$string);
          $string = str_replace('"','',$string);
          $string = str_replace('\'','',$string);
          $string = str_replace('#','',$string);
          $string = str_replace('?','',$string);
          $string = str_replace('!','',$string);
          $string = iconv("utf-8", "ASCII//TRANSLIT", $string);
                                                          
          return $string;
      }
      
      /**
      * otestování zda existuje defaultní složka pro fotky      
      */
      public function exists_photo_folder(){
          $date = new DateTime();
          if(is_dir($this->photo_dir.$date->format('m_Y'))){
              $this->set_folder($this->photo_dir.$date->format('m_Y'));
              return true;
          }
          else{
              if($this->exists_folder()){
                if(mkdir($this->photo_dir.$date->format('m_Y'))){
                  $this->set_folder($this->photo_dir.$date->format('m_Y'));
                  return true;
                }
              }
              else{
                  if(mkdir($this->photo_dir))
                  {
                    if(mkdir($date->format('m_Y'))){
                      $this->set_folder($this->photo_dir.$date->format('m_Y'));
                      return true;
                    }
                  }
              }            
          }
          return false;
      }
      
      /**
      * otestování zda existuje výchozí složka pro fotky 
      */
      private function exists_folder(){
          if(is_dir($this->photo_dir)){
              return true;
          }
          else{
            if(mkdir($this->photo_dir)){
                return true;
            }
          }
          return false;
      }
      
      

      public function save_photo($tmp_name,$name){
          $date = new DateTime();
          if($name == NULL){
            $file_path = $tmp_name;
          }else{
            $file_path = $tmp_name.'/'.$name;
          }
          //$file_path = 'http://tirus.cz/image/04_2012/b_29_01_22_54_9943680d0.jpg';
          if(!file_exists($file_path)){
            return false;
          }
          
          
          if(($data = file_get_contents($file_path))==false){
              return false;
          }
          $image_info = getimagesize($file_path);
          $image_info_big = $this->resize($image_info,$this->max_width,$this->max_height);
          $image_info_small = $this->resize($image_info,100,100);
          $file_name = null;
          $file_name = $date->format('d_H_i_s').'_'.substr(microtime(),2,8).'.jpg'; 
          $small_photo['name'] = 's_'.$file_name;
          $small_photo['link'] = $this->photo_dir. $small_photo['name'];
          
          switch($image_info['mime']){
            case "image/jpeg":
                $orig_source = imagecreatefromjpeg($file_path); //jpeg file
            break;
            case "image/gif":
                $orig_source = imagecreatefromgif($file_path); //gif file
            break;
            case "image/png":
                $orig_source = imagecreatefrompng($file_path); //png file
            break;
            default:
                $orig_source=false;
            break;
          }
          if($orig_source === false ){
            return false;
          }
            
          $holder_s = imagecreatetruecolor($image_info_small[0], $image_info_small[1]);
          imagecopyresampled($holder_s,$orig_source, 0, 0, 0, 0, $image_info_small[0], $image_info_small[1], $image_info[0], $image_info[1]);
          imagejpeg($holder_s, $this->photo_dir. $small_photo['name'], 100);
          $small_photo['size'] = filesize($this->photo_dir. $small_photo['name']);
          $small_photo['width'] = $image_info_small[0];
          $small_photo['height'] = $image_info_small[1];
                   
          $big_photo['name'] = 'b_'.$file_name;
          $big_photo['link'] = $this->photo_dir.$big_photo['name'];
          $holder_b = imagecreatetruecolor($image_info_big[0], $image_info_big[1]);
          imagecopyresampled($holder_b,$orig_source, 0, 0, 0, 0, $image_info_big[0], $image_info_big[1], $image_info[0], $image_info[1]);
          imagejpeg($holder_b, $this->photo_dir. $big_photo['name'], 100);
          $big_photo['size'] = filesize($this->photo_dir. $big_photo['name']);
          $big_photo['width'] = $image_info_big[0];
          $big_photo['height'] = $image_info_big[1];
          
          return array('original'=>$big_photo,'smaller'=>$small_photo);
      }
      
      /**
      * nastavení výchozí složky pro fotky
      * @param mixed $folder
      */
      public function set_folder($folder){
          $folder = $this->treatment($folder);
          if(substr($folder,-1)== "/"){
              $this->photo_dir = $folder;
          }
          else{
              $this->photo_dir = $folder."/";
          }                                           
      }
      
      /**
      * Přepočítá nové rozlišení pro daný obrázek
      * @param mixed $image_info = array() -    getimagesize()
      * @param mixed $new_height
      * @param mixed $new_width
      */
      private function resize($image_info,$new_height,$new_width){
          if($image_info[1] > $image_info[0]){
              if($image_info[1] > $new_height){
                $height = $new_height;
                $counter = $image_info[1] / $new_height;
                $width = floor($image_info[0] /$counter);
              }else{
                 $height =  $image_info[1];    
                 $width = $image_info[0];              
              }
          }
          else{
             if($image_info[0]>$new_width){
              $width = $new_width;
              $counter = $image_info[0] / $new_width;
              $height = floor($image_info[1] / $counter);
             }else{
                $height =  $image_info[1];    
                $width = $image_info[0];
             }
          }
          $image_info[0]=$width;
          $image_info[1]=$height;
          $image_info[3]="width=\"$width\" height=\"$height\"";
          return $image_info;
      }
      
      public function set_resolution($width = 1024,$height = 768){
          $this->max_height = floor($height);
          $this->max_width = floor($width);
      }
      
      public function delete_photo($link){ 

          if(file_exists(APPROOT.$link)){
            unlink(APPROOT.$link);     
          }
          $small = str_replace('s_','b_',APPROOT.$link);
          if(file_exists($small)){
              unlink($small);              
          }
          return true;
      }
      
  }