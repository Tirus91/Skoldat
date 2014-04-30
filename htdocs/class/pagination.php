<?php

class pagination {

    public $first_row;
    private $current_page;
    private $items;
    private $link;
    public $totalcount;
    private $last_page;
    public $count_show_link_around = 4;

    function pagination($current_page = 1, $items = 10, $link = '/') {
        if ($current_page > 1) {
            $this->first_row = (($current_page - 1) * $items) ;
        } else {
            $this->first_row = 0;
        }
        $this->current_page = $current_page;
        $this->items = $items;
        $this->link = $link;
    }

    public function get_pagination() {
        $this->last_page = ceil($this->totalcount / $this->items);
      
        if($this->current_page > $this->last_page){
            $this->current_page = 1;
        }
        $before = $this->get_page_before_this();
        $after = $this->get_page_after_this();
        if(empty($after) && empty($before)){
            return null;
        }
        $i = 0;
        $b = null;
        foreach ($before as $page) {
            $i++;
            if ($this->count_show_link_around != null) {
                if ($i <= $this->count_show_link_around) {
                    $b = '<li><a href="' . $this->link . $page . '">' . $page . '</a> | </li>' . $b;
                }
            } else {
                $b = '<li><a href="' . $this->link . $page . '">' . $page . '</a> | </li>' . $b;
            }
        }
        $b .= '<li> <b>' . $this->current_page . '</b> | </li>';
        $a = null;
        $i = 0;
        foreach ($after as $page) {
            $i++;
            if ($this->count_show_link_around != null) {
                if ($i <= $this->count_show_link_around) {
                    $a .= '<li><a href="' . $this->link . $page . '">' . $page . '</a> | </li>';
                }
            } else {
                $a .= '<li><a href="' . $this->link . $page . '">' . $page . '</a> | </li>';
            }
        }

        $b .= $a;
        if ($this->count_show_link_around != null) {
            $pagination = '<li>';
            if ($this->current_page != 1) {
                $pagination .= '<a href="' . $this->link . '1">Prvni</a>';
            } else {
                $pagination .= "Prvni";
            }

            $pagination .= '</li>  .... | ' . $b . '....  <li>';
            if ($this->current_page != $this->last_page) {
                $pagination .= '<a href="' . $this->link . $this->last_page . '">Posledni</a>';
            } else {
                $pagination .= "Posledni";
            }
            $pagination.='</li>';
        } else {
            $pagination = substr($b, 0, -strlen(' | '));
        }

        return "<ul>" . $pagination . "</ul>";
    }

    private function get_page_before_this() {
        $page_bef = array();
        if ($this->current_page > 1 && $this->current_page <= $this->last_page) {
            $curr_page = $this->current_page - 1;
            for ($curr_page; $curr_page >= 1; $curr_page--) {
                $page_bef[$curr_page] = $curr_page;
            }
        }
        return $page_bef;
    }

    private function get_page_after_this() {
        $page_after = array();
        if ($this->current_page >= 1 && $this->current_page <= $this->last_page) {
            $curr_page = $this->current_page + 1;
            for ($curr_page; $curr_page <= $this->last_page; $curr_page++) {
                $page_after[$curr_page] = $curr_page;
            }
        }
        return $page_after;
    }

}

?>
