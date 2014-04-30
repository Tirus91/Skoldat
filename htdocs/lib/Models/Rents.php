<?php
class Models_Rents {
    private $registry;
    
    function __construct($registry) {
        $this->registry = $registry;
    }


    public function getRefiAndRent($id_refi_sel = null){
        $rents = null;
        foreach($this->registry['sk_rent']->getRents() as $key => $val){
            $this->registry['view']->set('list',$this->registry['sk_rent']->getRefiIndRent($key,$id_refi_sel));
            $this->registry['view']->set('optgroup',$val['first_name_rent'].' '.$val['surname_rent'].' ('.$val['phone_rent'].')');
            $rents .= $this->registry['view']->fetch('Subparts/subrent.tpl');
        }
        return $rents;
        
        
    }
    

}
