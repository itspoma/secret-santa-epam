<?php
namespace app\models;

/**
 *
 */
class Database {

    /**
     *
     * @param \Silex\Application $app
     */
    public function __construct(\Silex\Application $app) {
        $this->app = $app;
    }

    /**
     *
     * @return boolean
     */
    public function save($email) {
        $data = array(
            date('Y-m-d H:i:s'),
            $_SERVER['REMOTE_ADDR'],
            $email,
        );

        $f = fopen($this->app['db.filename'], 'aw');
        fwrite($f, join("\t\t", $data)."\n");
        fclose($f);

        return true;
    }

    /**
     *
     * @return boolean
     */
    public function exists($email) {
        $database = file_get_contents($this->app['db.filename']);

        if (false !== strpos(strtolower($database), strtolower($email))) {
            return true;
        }

        return false;
    }
}