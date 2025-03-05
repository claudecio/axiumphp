<?php
    namespace app\Core;

    use PDO;
    use PDOStatement;
    use PDOException;

    class DB {
        private static ?PDO $dbh = null;

        /**
         * Inicializa a conexão com o banco de dados.
         */
        private static function init() {
            if(Self::$dbh === null) {
                try {
                    // Credênciais Banco de Dados
                    $host = $_ENV["DATABASE_HOST"] ?? "localhost";
                    $dbschema = $_ENV["DATABASE_SCHEMA"] ?? "test";
                    $username = $_ENV["DATABASE_USERNAME"] ?? "root";
                    $password = $_ENV["DATABASE_PASSWORD"] ?? "";
                    $port = $_ENV["DATABASE_PORT"] ?? "3306";
                    $charset = $_ENV["DATABASE_CHARSET"] ?? "utf8mb4";

                    $dsn = "mysql:host={$host};port={$port};dbname={$dbschema};charset={$charset}";
                    $options = [
                        PDO::ATTR_PERSISTENT => true,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ];

                    Self::$dbh = new PDO(dsn: $dsn, username: $username, password: $password, options: $options);
                } catch (PDOException $e) {
                    die("Erro ao conectar ao banco de dados: {$e->getMessage()}");
                }
            }
        }

        /**
         * Prepara uma consulta SQL para execução.
         */
        public static function prepare(string $sql):PDOStatement {
            Self::init();
            return Self::$dbh->prepare(query: $sql);
        }

        /**
         * Executa um comando SQL sem retorno de dados.
         */
        public static function execute(string $sql, array $params = []):bool {
            $stmt = Self::prepare(sql: $sql);
            return $stmt->execute(params: $params);
        }

        /**
         * Obtém um único resultado da consulta.
         */
        public static function fetchOne(string $sql, array $params = []):?array {
            $stmt = Self::prepare(sql: $sql);
            $stmt->execute(params: $params);
            return $stmt->fetch() ?: null;
        }

        /**
         * Obtém todos os resultados da consulta.
         */
        public static function fetchAll(string $sql, array $params = []):array {
            $stmt = Self::prepare(sql: $sql);
            $stmt->execute(params: $params);
            return $stmt->fetchAll();
        }

        /**
         * Inicia uma transação.
         */
        public static function beginTransaction():void {
            Self::init();
            Self::$dbh->beginTransaction();
        }

        /**
         * Verifica se há uma transação ativa.
         */
        public static function inTransaction():bool {
            Self::init();
            return Self::$dbh->inTransaction();
        }

        /**
         * Confirma uma transação.
         */
        public static function commit():void {
            if (Self::inTransaction()) {
                Self::$dbh->commit();
            }
        }

        /**
         * Reverte uma transação.
         */
        public static function rollBack():void {
            if (Self::inTransaction()) {
                Self::$dbh->rollBack();
            }
        }

        /**
         * Obtém o ID do último registro inserido.
         */
        public static function lastInsertId():string {
            Self::init();
            return Self::$dbh->lastInsertId();
        }
    }