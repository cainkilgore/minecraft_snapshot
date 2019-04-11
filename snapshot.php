<?php
    $server_path = "server/server.jar";

    $version_manifest = "https://launchermeta.mojang.com/mc/game/version_manifest.json";
    $version_manifest_download = file_get_contents($version_manifest);

    $version_manifest_json = json_decode($version_manifest_download);
    foreach($version_manifest_json->versions as $key => $version) {
        if($version->type == "snapshot") {
            $version_info = $version->url;
            $version_id = $version->id;
            break;
        }
    }

    if(isset($version_info)) {
        $version_info_download = file_get_contents($version_info);
        $version_info_json = json_decode($version_info_download);
        $server_jar = $version_info_json->downloads->server->url;

        if(@sha1_file($server_path) != $version_info_json->downloads->server->sha1) {
            echo "SHA mismatch, downloading newer version.\n";
            $download_jar = file_put_contents($server_path, fopen($server_jar, "r"));
            if($download_jar) {
                echo "New server jar for $version_id downloaded to $server_path. SHA: " . sha1_file($server_path) . "\n\n";
                return true;
            }
        } else {
            echo "Newest snapshot $version_id is already installed.\n";
        }
    }
?>