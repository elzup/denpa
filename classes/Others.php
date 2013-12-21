<?php


class Calendars {
    public $tables;

    public function __construct($id) {
        $this->tables = array();
        $datas = $this->getDatas($id);
//        echo "<pre>";
        foreach($datas as $data) {
            $c = getClass($data);
//            print_r($c);
//            echo($c->getTerm());
            for($i = 0; $i < count($c->schedules); $i++) {
                $this->tables[$c->getTerm()][$c->schedules_d[$i]][$c->schedules_t[$i]] = $c;
            }
        }
    }

    public function getScheduleClassCal($p, $d, $t) {
        return (!empty($this->tables[$p][$d][$t]))? $this->tables[$p][$d][$t]: false;
    }

    private function getDatas($id) {
        $condition = array('id_student' => $id);
        $recos = $dbo->getTable($table_name = 'denpa_attend_class', $column = array('id_class'), $condition);

        $datas = array();
        foreach($recos as $r) {
            $datas[] = $r['id_class'];
        }

        return $datas;
    }
}

?>