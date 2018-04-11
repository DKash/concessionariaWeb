<?php

namespace libraries\drivers;

use libraries\Generico;

class ConexaoPDO extends Generico {

    public $pdo;
    public $host = "localhost";
    public $db = "concessionariaweb";
    public $usuario = "concessionariaweb";
    public $senha = "concessionariaweb123";
    public $nomeClasse;
    public $obj_class = array();
    private $class_banco;

    public function __construct($class_banco) {
        $this->pdo = $this->init();
        if ($class_banco) {
            $this->class_banco = $class_banco;
            $this->nomeClasse = (new \ReflectionClass($class_banco))->getShortName();

            $props = (new \ReflectionClass($class_banco))->getProperties();
            foreach ($props as $valor) {
                $valor->setAccessible(true);
                array_push($this->obj_class, $valor->getName());
            }
        }
    }

    protected function init() {
        if (!isset($this->pdo)) {
            try {
                return new \PDO("mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=UTF8", $this->usuario, $this->senha, array(
                    \PDO::ATTR_PERSISTENT => true,
                    \PDO::ATTR_AUTOCOMMIT => false
                ));
            } catch (\PDOException $e) {
                error_log("erro: " . $e->getMessage() . "<br/>");
            }
        }
    }

    public function insert($params, $values) {
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("insert into " . $this->nomeClasse . " (" .
                                        implode(", ", $params) . ") values (:" . implode(",:", $params) . ")");

            foreach ($params as $key => $value) {
                foreach ($values as $key1 => $value1) {
                    if ($key == $key1) {
                        $stmt->bindParam(":" . $params[$key], $values[$key]);
                    }
                }
            }
            $stmt->execute();
            $id = $this->pdo->lastInsertId();
            $this->pdo->commit();
            return $id;
        } catch (\PDOException $e) {
            error_log("erro: " . $e->getMessage() . " salvando classe -> " . $this->nomeClasse . "<br/>");
            throw $e;
        }

    }

    public function update($params, $values, $idValue) {
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        try {
            $this->pdo->beginTransaction();
            $sets = "";
            $cont = 0;
            foreach ($params as $chave) {
                if ($cont == 0) {
                    $sets .= $chave . " =:" . $chave;
                } else {
                    $sets .= "," . $chave . " =:" . $chave;
                }
                $cont++;
            }
            $stmt = $this->pdo->prepare("update `" . $this->nomeClasse . "` set " . $sets .
                                        " where id" . $this->nomeClasse . " =:id" . $this->nomeClasse);

            foreach ($values as $key => $valor) {
                $stmt->bindParam(":" . $params[$key], $values[$key]);
            }

            $stmt->bindParam(":id" . $this->nomeClasse, $idValue, \PDO::PARAM_INT);
            $stmt->execute();
            $this->pdo->commit();
        } catch (\PDOException $e) {
            error_log("erro " . $e->getMessage() . "update classe -> " . $this->nomeClasse . "<br/>");
            throw $e;
        }
    }

    public function delete($sql, $paramArray = array()) {
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            foreach ($paramArray as $key => $valor) {
                $stmt->bindParam(":" . $key, $paramArray[$key]);
            }
            $stmt->execute();
            $this->pdo->commit();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            error_log("erro:" . $e->getMessage() . " delete na classe ->" . $this->nomeClasse . "<br/>");
            throw $e;
        }
    }

    public function queryObject($sql, $paramArray = array(), $paramType = array()) {
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            foreach ($paramArray as $key => $valor) {
                if (sizeof($paramType) > 0) {
                    foreach ($paramType as $key_type => $valor_type) {
                        if ($key_type == $key) {
                            if ($paramType[$key_type] == "PARAM_INT") {
                                $v_int = intval($paramArray[$key]);
                            }
                            $stmt->bindParam(":" . $key, $v_int, \PDO::PARAM_INT);
                        }
                    }
                } else {
                    $stmt->bindParam(":" . $key, $paramArray[$key]);
                }
            }
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_CLASS, get_class($this->class_banco));
            $this->pdo->commit();
            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log("erro:" . $e->getMessage() . " queryObject na classe ->" . $this->nomeClasse . "<br/>");
            throw $e;
        }
    }

    public function queryList($sql, $paramArray = array(), $paramType = array()) {
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            foreach ($paramArray as $key => $valor) {
                if (sizeof($paramType) > 0) {
                    foreach ($paramType as $key_type => $valor_type) {
                        if ($key_type == $key) {
                            if ($paramType[$key_type] == "PARAM_INT") {
                                $v_int = intval($paramArray[$key]);
                            }
                            $stmt->bindParam(":" . $key, $v_int, \PDO::PARAM_INT);
                        }
                    }
                } else {
                    $stmt->bindParam(":" . $key, $paramArray[$key]);
                }
            }
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_CLASS, get_class($this->class_banco));
            $this->pdo->commit();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("erro:" . $e->getMessage() . " queryList na classe ->" . $this->nomeClasse . "<br/>");
            throw $e;
        }
    }

    public function queryAll($sql) {
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_CLASS, get_class($this->class_banco));
            $this->pdo->commit();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("erro:" . $e->getMessage() . " queryAll na classe ->" . $this->nomeClasse . "<br/>");
            throw $e;
        }
    }

    /**
     * Desconecta do banco de dados
     */
    public function desconectar() {
        error_log("executei metodo desconectar");
        try {
            if (isset($this->pdo)) {
                unset($this->pdo);
            }
        } catch (\PDOException $e) {
            error_log("erro fechar conexao:" . $e->getMessage() . "<br/>");
        }
    }
}