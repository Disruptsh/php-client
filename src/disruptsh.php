<?php

use PDO;

class Disruptsh{


    /**
     * @param string $service
     * @param string $mode
     * @return mixed
     */
    public static function getConfig($publish,$appName){

        try{
            if ($publish!='false')
                $file = '/home/app/config/disruptsh/'.$appName.'.json';
            else
                $file = '.disruptsh/'.$appName.'.json';
            $content = @file_get_contents($file);
            if (!$content) return null;

            $config = json_decode($content,true);
            return $config;
        }
        catch(Exception $e){
            return null;
        }
    }

    /**
     * @param $publish
     * @return mixed|null
     */
    public static function getParameters($publish){

        try{
            if ($publish!='false')
                $file = '/home/app/config/disruptsh/appConfiguration.json';
            else
                $file = '.disruptsh/appConfiguration.json';
            $content = @file_get_contents($file);
            if (!$content) return null;

            $params = json_decode($content);
            return $params;
        }
        catch(Exception $e){
            return null;
        }
    }

    /**
     * @param $publish
     * @param $json
     * @return mixed|null
     */
    public static function saveParameters($array,$publish)
    {
        try {
            if ($publish != 'false')
                $file = '/home/app/config/disruptsh/appConfiguration.json';
            else
                $file = '.disruptsh/appConfiguration.json';
            $json = json_encode($array);
            $content = @file_put_contents($file, $json);
            if (!$content) return false;
            return true;
        } catch (Exception $e) {
            return null;
        }
    }


    /**
     * @return array|string|string[]
     */
    public static function randomMongoDBID(){
        $id =  str_replace('.', '', uniqid('', true));
        return $id;
    }

    /**
     * @return string
     */
    public static function randomUUID(){
        $id =  self::gen_uuid();
        return $id;
    }

    /**
     * @param $config
     * @return mixed
     */
    public static function getContext($config){
        return $config['context']; // sandbox, preproduction, production
    }

    /**
     * @param $config
     * @return PDO|void
     */
    public static function  connectMysqlPDO($config){

        $db_host = self::getDBHost($config);
        $db_database = self::getDBDatabase($config);
        $db_user = self::getDBUser($config);
        $db_password = self::getDBPassword($config);
        $db_port = self::getDBPort($config);

        $dsn="mysql:dbname=".$db_database.";host=".$db_host;
        try{
            $connexion=new PDO($dsn,$db_user,$db_password);
            return $connexion;
        }
        catch(PDOException $e){
            printf("Échec de la connexion : %s\n", $e->getMessage());
            exit();
        }

    }

    /**
     * @param $message
     */
    public static function displayMessage($message){
        echo $message."\n";
    }

    /**
     * @param $message
     */
    public static function displayErrorMessageAndStop($message){
        echo $message;
        exit();
    }

    /**
     * @param $config
     * @return mixed
     */
    public static function getDomain($config){
        return $config['domain'];
    }

    /**
     * @param $config
     * @return mixed
     */
    public static function getDBDatabase($config){
        return $config['database']['db'];
    }

    /**
     * @param $config
     * @return mixed
     */
    public static function getDBHost($config){
        return $config['database']['host'];
    }

    /**
     * @param $config
     * @return mixed
     */
    public static function getDBUser($config){
        return $config['database']['user'];
    }

    /**
     * @param $config
     * @return mixed
     */
    public static function getDBPassword($config){
        return $config['database']['password'];
    }

    /**
     * @param $config
     * @return mixed
     */
    public static function getDBPort($config){
        return $config['database']['port'];
    }

    /**
     * @return string
     */
    public static function gen_uuid() {
        $uuid = array(
            'time_low'  => 0,
            'time_mid'  => 0,
            'time_hi'  => 0,
            'clock_seq_hi' => 0,
            'clock_seq_low' => 0,
            'node'   => array()
        );

        $uuid['time_low'] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
        $uuid['time_mid'] = mt_rand(0, 0xffff);
        $uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
        $uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
        $uuid['clock_seq_low'] = mt_rand(0, 255);

        for ($i = 0; $i < 6; $i++) {
            $uuid['node'][$i] = mt_rand(0, 255);
        }

        $uuid = sprintf('%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
            $uuid['time_low'],
            $uuid['time_mid'],
            $uuid['time_hi'],
            $uuid['clock_seq_hi'],
            $uuid['clock_seq_low'],
            $uuid['node'][0],
            $uuid['node'][1],
            $uuid['node'][2],
            $uuid['node'][3],
            $uuid['node'][4],
            $uuid['node'][5]
        );

        return $uuid;
    }

}
