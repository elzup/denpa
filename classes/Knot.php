<?php

class Knot {
    public $id;
    public $name;
    public $tags;
    public $tag;
    public $id_category;

    public $detail;
    public $editer;

    private $prviate;
    private $psheet_params;

    public function __construct($knot_data, $compact = false) {
        $this->id          = $knot_data['id_knot'];
        $this->name        = $knot_data['name'];
        $this->tags        = $knot_data['tags'];
        $this->tag         = explode(",", $knot_data['tags']);
        $this->id_category = $knot_data['id_category'];

        if(!$compact) {
            $this->editer = $knot_data['editer'];
            $this->detail = $knot_data['detail'];
        }
    }

    public function getPsheet(){
        return (empty($this->psheet_params)) ?
        $this->psheet_params = getPsheet($this->id):
        $this->psheet_params;
    }

    public function getPsheet() {
        return (empty($this->psheet_params)) ?
        $this->psheet_params = getPsheet($this->id) :
        $this->psheet_params;
    }

    public function getArrayParams() {
        $parameter                = array();
        $parameter['id']          = $this->id;
        $parameter['name']        = $this->name;
        $parameter['tags']        = $this->tags;
        $parameter['id_category'] = $this->id_category;
        $parameter['detail']      = $this->detail;
        return $parameter;
    }

    public function set_private($p) {
        $this->private = $p;
    }
}

?>