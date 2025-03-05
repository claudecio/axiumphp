<?php
    namespace app\Core;

    class Registry {
        private static array $data = [];

        /**
         * Define um valor para uma chave específica no array de dados estático.
         *
         * Este método estático armazena um valor associado a uma chave no array
         * estático `$data`.
         *
         * @param string $key   A chave para armazenar o valor.
         * @param mixed  $value O valor a ser armazenado.
         *
         * @return void
         */
        public static function set(string $key, mixed $value):void {
            self::$data[$key] = $value;
        }

        /**
         * Retorna o valor associado a uma chave específica no array de dados estático.
         *
         * Este método estático retorna o valor associado à chave fornecida no
         * array estático `$data`. Se a chave não existir, o método retorna `null`.
         *
         * @param string $key A chave para recuperar o valor.
         *
         * @return mixed O valor associado à chave, ou null se a chave não existir.
         */
        public static function get(string $key):mixed {
            return self::$data[$key] ?? null;
        }
    }