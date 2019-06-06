<?php

namespace App\Util;

use Doctrine\Migrations\Configuration\Exception\FileNotFound;

/**
 * Description of SQLFileLoader
 *
 * @author Draeius
 */
class SQLFileLoader {

    const DIRECTORY = '../SQL/';

    public static function getSQLFileContent($filename) {
        $fullPath = self::DIRECTORY . $filename . '.sql';
        if (file_exists($fullPath)) {
            return file_get_contents($fullPath);
        }
        throw new FileNotFound('SQL-File ' . $filename . '.sql  @' . $fullPath . ' wurde nicht gefunden.');
    }

}
