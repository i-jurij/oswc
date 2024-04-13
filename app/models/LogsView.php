<?php

namespace App\Models;

class LogsView extends Adm
{
    use \App\Lib\Traits\FilesInDir;

    public array $logs;
    protected function dbQuery()
    {
        $this->data['page_db_data'] = array(array("page_alias" => "LogsView",
                                                "page_title" => "Просмотр логов",
                                                "page_meta_description" => "Просмотр логов",
                                                "page_robots" => "NOINDEX, NOFOLLOW",
                                                "page_h1" => "Просмотр логов",
                                                "page_access" => "admin"));

        $logs = filesInDirScan(ROOT.DS.'log');
        if (!empty($logs)) {
            foreach ($logs as $file) {
                $f = pathinfo($file, PATHINFO_FILENAME);
                if (filesize($file) > 10 * 1024 * 1024) {
                    $this->data['logs'][] = "Файл $f больше 10МБ. Перед открытием почистите логи.";
                } else {
                    $this->data['logs'][] = $f.'#'.$file;
                }
            }
        }
    }

    public function view()
    {
        $this->data['name'] = 'Текст';
        $this->data['res'] = '';
        if (!empty($_POST['log']) && is_string($_POST['log'])) {
            $lines = file(test_input($_POST['log']));
            $this->data['res'] .= '<p class="text_center">Файл: <b>"'.pathinfo(test_input($_POST['log']), PATHINFO_BASENAME).'"</b></p>';
            foreach ($lines as $line_num => $line) {
                $this->data['res'] .= htmlspecialchars($line) . "\n";
                if ($line_num == 3) {
                    $this->data['res'] .= "<br />";
                }
            }
        } else {
            $this->data['res'] .= "Файл не выбран.<br />";
        }
        return $this->data;
    }
}
