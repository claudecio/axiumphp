<?php
    namespace app\Core;

    use Exception;

    class Loader {
        private $configFilePath;
        private $configData;

        public function __construct($configFilePath = 'app/system-ini.json') {
            $this->configFilePath = $configFilePath;
            $this->loadConfig();
        }

        // Carregar o arquivo JSON
        private function loadConfig() {
            $configPath = realpath(path: __DIR__ . "/../../{$this->configFilePath}");
            if (file_exists(filename: $configPath)) {
                $jsonContent = file_get_contents(filename: $configPath);
                $this->configData = json_decode(json: $jsonContent, associative: true);
            } else {
                throw new Exception(message: "Arquivo de configuração não encontrado: {$this->configFilePath}");
            }
        }

        // // Carregar módulos ativos, essenciais e core
        // public function loadModules() {
        //     $this->loadModulesFromArray(modules: $this->configData['Modules']['active'] ?? [], type: 'Ativos');
        //     $this->loadModulesFromArray(modules: $this->configData['Modules']['essentials'] ?? [], type: 'Essenciais');
        // }

        // // Função auxiliar para carregar módulos de um determinado grupo
        // private function loadModulesFromArray($modules, $type) {
        //     foreach ($modules as $module) {
        //         $this->loadModule(module: $module, type: $type);
        //     }
        // }
        

        // Carregar um módulo individualmente
        public function loadModule($module, $type) {
            $moduleDir = $module['dirName'];

            // Construir o caminho absoluto para o arquivo Routes.php do módulo
            $moduleFile = realpath(path: __DIR__ . "/../Modules/{$moduleDir}/bootstrap.php");

            // Verificar se o arquivo Routes.php existe
            if ($moduleFile && is_file(filename: $moduleFile)) {
                require_once $moduleFile;
            } else {
                echo "[$type] Arquivo do módulo não encontrado: {$moduleFile}\n";
            }
        }
    }