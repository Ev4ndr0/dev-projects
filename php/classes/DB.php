<?php

    class DB
    {
        private $conn = null;

        public function __construct()
        {
            $this->conn = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);
            $this->conn->set_charset('utf8');
        }

        public function __destruct()
        {
            $this->conn->close();
        }
        
        public function protegerString(string $valor)
        {
            return $this->conn->real_escape_string($valor);
        }

        public function query(string $sql, bool $single = false)
        {
            $resultados = $this->conn->query($sql);
            if ($single) {
                return $resultados->fetch_assoc();
            }
            else {
                return $resultados->fetch_all(MYSQLI_ASSOC);
            }
        }

        public function executar(string $sql)
        {
            $resultado = $this->conn->query($sql);
            return $resultado;
        }
    }