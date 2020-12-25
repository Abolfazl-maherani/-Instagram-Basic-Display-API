<?php
define("NAME_DB", "db_instagram.json");
define("PATH_DB", "./" . NAME_DB);
//create or open json db
function isdbinsta()
{
    if (!file_exists(PATH_DB)) {
        try {
            $handelDb = fopen(PATH_DB, "a");
            fclose($handelDb);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
//this func convert array to json and write
function writeJsonedb(array $data = [])
{

    $contentDb = readJsonedb();
    $data = (!empty($contentDb)) ? array_merge($contentDb, $data) : $data;
    $jsonDB = "";
    //check validate data
    if (is_array($data)) {
        if (!empty($data)) {
            $jsonDB = json_encode($data, JSON_UNESCAPED_UNICODE);
            try {
                $handel = fopen(PATH_DB, "w");
                fwrite($handel, $jsonDB);
                fclose($handel);
            } catch (Exception $e) {
                die($e->getMessage());
            }

        }
    }
}
//this func is read and convert content file to array
function readJsonedb($key = null)
{
    $data = file_get_contents(PATH_DB);
    if (isjson($data)) {
        if (!empty($data)) {
            //convert json to array
            $data = json_decode($data, true);
            $resultData ="";
            // check is string $key
            if (is_string($key)){
                if (array_key_exists($key,  $data)) {
                    $resultData = $data[$key];
                }
                else {
                    $resultData = false;
                }
            }
            // check is array $key
            if (is_array($key)) {
                foreach ($key as $item) {
                    $resultData = $data[$item];
                }
            }
        }
        return $resultData;
    }
return false;



}
//check for is json my db
function isjson($str)
{
    $result = json_decode($str, true);
    $result = (is_array($result)) ? true : false;
    return $result;
}
