<?php
namespace Rdb;

abstract class Sql {

    protected $where;
    protected $sqlString = "";
    protected $bindValues;
    protected $tableName;
    protected $isBuild = true;

    /**
     * make sqlString and append bindValues
     */
    abstract public function build();

    /**
     * @return string :sql string with ?
     */
    public function sqlStr() {
        if(!$this->sqlString) {
            throw new Exception\SqlException("sql must build before use");
        }
        return $this->sqlString;
    }

    public function setTableName($tableName) {
        $this->tableName = $tableName;
    }

    public function getTableName() {
        return $this->tableName;
    }

    /**
     * @return array :array($value...)
     */
    public function binds() {
        if($this->bindValues===null) {
            throw new Exception\SqlException("sql must build before use");
        }
        return $this->bindValues;
    }

    /**
     * @return string :sql after replace ? to $value
     */
    public function __toString() {
        $binds = $this->binds();
        $sql = $this->sqlStr();
        $sqlFragments = explode("?", $sql);

        $countBind = count($binds);
        $countSqlFragMents = count($sqlFragments);
        $sqlString = $sqlFragments[0];
        for ($i=1; $i < $countSqlFragMents; $i++) { 
            $ichar = isset($binds[$i-1]) ? (is_string($binds[$i-1]) ? "'".$binds[$i-1]."'" : $binds[$i-1]) : "?";
            $sqlString .= $ichar .$sqlFragments[$i];
        }
        return $sqlString;
    }

    public function setWhere(Where $where) {
        $this->where = $where;
    }

    public function getWhere() {
        return $this->where;
    }
}