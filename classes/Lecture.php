<?php

class Lecture {
    public $id;
    public $name;
    public $detail;
    private $classes;


    public function __construct($lecture_data, $compact = false){
        $this->id         = $lecture_data['id_lecture'];
        $this->name       = $lecture_data['name'];
        $this->classes    = array();

        if(!$compact) {
            $this->detail = $lecture_data['detail'];
        }
    }



    public function getArrayParams() {
        $parameter = array();
        $parameter['name']   = $this->name;
        $parameter['detail'] = $this->getDetail();
        return $parameter;
    }

    public function getDetail() {
        if ( !empty($this->detail)) return $this->detail;
        return $this->detail = DB::getData($table_name = "denpa_knot_lecture", "detail", array("id_lecture", $this->id));
    }

    public function getClasses () {
        return (empty($this->classes)) ? $this->classes : $this->classes = $this->getChildClasses($this->id);
    }

    public static function getChildClasses($id_lecture) {
        $condition = array(
                'id_root' => $id_lecture,
                );
        $recs = Db::getTable($table_name = 'denpa_knot_class', null, $condition);
        $datas = array();
        foreach ($recos as $r)
            $datas[] = new LClass($r, $t);
        return $datas;
    }
}



class LClass {
    public $id;
    public $schedules;
    public $schedules_d;
    public $schedules_t;
    public $room;
    public $teacher;
    public $lecture;

    public $limit;
    public $textbook;
    public $measurement;
    public $prepare;

    public $term_y;
    public $term_t;
    public $term_y_str;
    public $term_t_str;
    public $state;

    public function __construct($class_data, $compact = false) {
//        print_r($class_data);
        $this->id          = $class_data['id_class'];
        $this->schedules   = explode(",", $class_data['schedules']);
        $this->schedules_d = array();
        $this->schedules_t = array();
        for($i = 0; $i < count($this->schedules); $i++) {
            $this->schedules_d[$i] = substr($this->schedules[$i], 0, 1);
            $this->schedules_t[$i] = substr($this->schedules[$i], 1, 1);
        }
        $this->room        = $class_data['room'];
        $this->teacher     = $class_data['teacher'];
        $this->lecture     = getLecture($class_data['id_root']);

        $this->limit       = $class_data['limit'];
        $this->textbook    = $class_data['textbook'];
        $this->measurement = $class_data['measurement'];
        $this->prepare     = $class_data['prepare'];

        $term = $class_data['term'];
        $this->state      = $class_data['state'];
        $this->term_y     = substr($term, 0, 2);
        $this->term_t     = substr($term, 2, 2);
        $this->term_y_str = "20{$this->term_y}年";
        $this->term_t_str = convertTermToStr($this->term_t, true)."期";
    }

    public function getTerm() {
        return $this->term_y.$this->term_t;
    }

    public function getArrayParams() {
        $parameter = array();
        $parameter['teacher']     = $this->teacher;
        $paraemter['schedule']    = $this->schedules;
        $parameter['room']        = $this->room;
        $parameter['limit']       = $this->limit;
        $parameter['textbook']    = $this->textbook;
        $parameter['measurement'] = $this->measurement;
        $parameter['prepare']     = $this->prepare;
        $parameter['term']        = $this->getTerm();
        return $parameter;
    }
}

?>