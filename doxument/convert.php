<?php

if (empty($argv[4])) {
    echo 'Usage: php convert.php apiKey apiToken source_path_file.doc dest_path_file.pdf';
    exit;
}

$apiKey = $argv[1];
$apiToken = $argv[2];
$file_doc = $argv[3];
$file_pdf = $argv[4];

$paths = array(
	get_include_path(),
	dirname(__FILE__).'/doxument-sdk-php5/src/',
	dirname(__FILE__).'/doxument-sdk-php5/',
);

set_include_path(implode(PATH_SEPARATOR, $paths));

require_once 'Doxument/Loader.php';
spl_autoload_register(array('Doxument_Loader', 'autoload'));

try {
    
    //Start file upload
    $client = new Doxument_Api_Client($apiKey, $apiToken);
    
    $data = $client->upload($file_doc);
    $id = $data['payload']['doc']['id']; // document id of 'test.ppt' in Doxument system.
    print 'File uploaded with id: ' . $id . "\n";

    //Start conversion
    $client = new Doxument_Api_Client($apiKey, $apiToken);
    // see the complete list of supported formats here http://code.google.com/p/doxument/wiki/Documentation?tm=6#Formats
    $format = 'pdf'; 
    $data = $client->convert((string)$id, $format, null);
    $id2 = $data['payload']['doc']['id']; // document id of 'test.pdf' in Doxument system.
    print 'File converted with id: ' . $id2 . "\n";
    
    //Start download
    $client = new Doxument_Api_Client($apiKey, $apiToken);
    $client->download($id2, $file_pdf);
        
} catch(Doxument_Api_Exception $ex) {
        die($ex);
}
