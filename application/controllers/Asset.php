<?php

header('Access-Control-Allow-Origin: *');

/**
 * @property  $uri
 */
class Asset extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    private function get_3dtiles_path( $asset_id){
        return FCPATH . 'assets/' . $asset_id . '.3dtiles';
    }

    private function response_tileset_json($asset_id) {
        $sqlite_3dtiles_path = $this->get_3dtiles_path($asset_id);

        $db = new PDO('sqlite:' . $sqlite_3dtiles_path,'','', array(PDO::ATTR_PERSISTENT => true));

        if( !isset($db) ) {
            header('Content-type: text/plain');
            print 'Incorrect tileset name: ' . 'tileset';
            exit;
        }

        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $query =  "SELECT content FROM media WHERE key='tileset.json'";

        $result = $db->query($query);

        if(!$result) {
            die("Execute query error, because: ". print_r($db->errorInfo(), true));
            return;
        }

        // When only one row is expected - to get that only row. For example,
        $row = $result->fetch(PDO::FETCH_ASSOC);

        $ret = gzdecode($row['content']);

        if($ret == false) {
            echo 'Failed to get tileset.json';
            return;
        }

        echo $ret;
    }

    private function response_3dtile($asset_id, $key) {
        $sqlite_3dtiles_path = $this->get_3dtiles_path($asset_id);

        $db = new PDO('sqlite:' . $sqlite_3dtiles_path,'','', array(PDO::ATTR_PERSISTENT => true));

        if( !isset($db) ) {
            header('Content-type: text/plain');
            print 'Incorrect tileset name: ' . 'tileset';
            exit;
        }

        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $query =  "SELECT content FROM media WHERE key=" . "'". $key . "'";

        $result = $db->query($query);

        if(!$result) {
            die("Execute query error, because: ". print_r($db->errorInfo(), true));
            return;
        }

        // When only one row is expected - to get that only row. For example,
        $row = $result->fetch(PDO::FETCH_ASSOC);

        $ret = gzdecode($row['content']);

        if($ret == false) {
            echo 'Failed to get tileset.json';
            return;
        }

        echo $ret;
    }

    public function index() {
        // asset id

        /** @noinspection PhpUndefinedMethodInspection */
        $asset_id = $this->uri->segment('2');

        /** @noinspection PhpUndefinedMethodInspection */
        $uri_segments = $this->uri->segment_array();

        if(count($uri_segments) == 3) {
            if($uri_segments['3'] == 'tileset.json') {
                $this->response_tileset_json($asset_id);
                return;
            }
        }

        $key = '';


        for ($i = 3; $i <= count($uri_segments); $i++) {

            if($i == 3)
                $key = $key . $uri_segments[strval($i)];
            else
                $key = $key . '/' .$uri_segments[strval($i)];
        }

        $this->response_3dtile($asset_id, $key);
    }
}
