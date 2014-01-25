<?php

class User{
    public $id;
    public $id_str;
    public $id_screen;
    public $nick_name;
    protected $pass_hash;

    public $name;
    public $sex;
    public $point;
    public $ids;
    public $term;

    public $knots;
    public $lectures;
    public $classes;

    private $calendars;

    public function get_pass(){
        return $this->pass;
    }
    //    private $

    public function __construct($user_data, $compact = false){
        //        echo "<pre>";
        $this->id        = $user_data['id'];
        $this->id_str    = convertIdToStr($this->id);
        $this->id_screen = $user_data['id_screen'];
        $this->nick_name = $user_data['nick_name'];
        $this->name      = $user_data['name'];

        if(!$compact){
            $this->pass  = $user_data['password'];
            $this->sex   = $user_data['sex'];
            $this->point = $user_data['point'];
            $this->term  = $user_data['term'];
            $this->setIds();
            $this->set_knots();
            $this->set_classes();
        }
        //        $this->calendars = new Calendars($this->id);
    }

    public function setIds() {
        $this->ids = User::getIds($this->id);
    }

    public function setOptional() {
        global $dbo;
        if(!empty($this->name) && !empty($this->sex) && !empty($this->point)) return;
        $condition = array('id' => $this->id);
        $row = $dbo->getRow($table_name = 'denpa_user', $condition, $limit = 1);
        $this->pass  = $row['password'];
        $this->sex   = $row['sex'];
        $this->point = $row['point'];
        $this->term  = $row['term'];
        $this->setIds();
        $this->set_knots();
        $this->set_classes();
    }

    public static function getIds($id_student) {
        global $dbo;
        $ids = array();
        $condition = array('id_student' => $id_student);
        $rows = $dbo->getRow($table_name = 'denpa_id', $condition);
        foreach ($rows as $row) {
            $ids[$row['service']]['value']   = $row['value'];
            $ids[$row['service']]['private'] = $row['private'];
            //             $service_name = convertServiceToString($row['service']);
            //             if($service_name !== false && property_exists(__CLASS__, $service_name)) {
            //                 $this->$$service_name = $row['value'];
            //             }
        }
        return $ids;
    }

    public function isAttendKnot($knot_id) {
        if(empty($this->knots))$this->set_knots();
        foreach($this->knots as $k) {
            if($k->id == $knot_id)return true;
        }
        return false;
    }
    public function isAttendLecture($id_lecture) {
        if(empty($this->classes))$this->set_classes();
        foreach($this->classes as $c) {
            if($c->lecture->id == $id_lecture)return true;
        }
        return false;
    }
    public function isAttendClass($id_class) {
        if(empty($this->classes))$this->set_classes();
        foreach($this->classes as $c) {
            if($c->id == $class_id)return true;
        }
        return false;
    }

    public function get_calendars() {
        return ($this->calendars)? $this->calendaars : $this->calendars = new Calendars($this->id);
    }

    public function set_knots() {
        if(!empty($this->knots)) return;
        $this->knots = User::getAttendedKnots($this->id);
    }
    public function set_classes() {
        if(!empty($this->classes)) return;
        $this->classes = User::getAttendedClasses($this->id);
    }

    public static function getAttendedKnots($id) {
        global $dbo;
        $condition = array('id_student' => $id);
        $recos = $dbo->getRow($table_name = 'denpa_attend_knot', $condition);
        $datas = array();
        foreach ($recos as $r) {
            $data = getKnot($r['id_knot'], true);
            $data->set_private($r['private']);
            $datas[] = $data;
        }
        return $datas;
    }
    public static function getAttendedClasses($id) {
        global $dbo;
        $condition = array('id_student' => $id);
        $recos = $dbo->getRow($table_name = 'denpa_attend_class', $condition, 1);
        $datas = array();
        foreach($recos as $r) {
            $datas[] = getClass($r['id_class'], true);
        }
        return $datas;
    }

    public function get_serialize(){
        return serialize($this);
    }


    public function setSchedules() {

    }

    public function getReload(){
        return getUser($this->id, $this->get_pass(), true);
    }

    public function getScheduleClass($p, $l, $t) {
        if(empty($this->calendars)) {
            $this->get_calendars();
        }
        return $this->calendars->getScheduleClassCal($p, $l, $t);
    }
    
    
}

?>