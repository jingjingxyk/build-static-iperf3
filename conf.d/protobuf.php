<?php

use SwooleCli\Preprocessor;
use SwooleCli\Extension;

return function (Preprocessor $p) {
    $p->addExtension(
        (new Extension('protobuf'))
            ->withOptions('--enable-protobuf')
            ->withPeclVersion('3.22.0')
            ->disableDownloadWithMirrorURL()
            ->withLicense('https://github.com/protocolbuffers/protobuf/blob/main/LICENSE', Extension::LICENSE_BSD)
            ->withHomePage('https://developers.google.com/protocol-buffers')
            ->withManual('https://protobuf.dev/reference/php/php-generated/')
    );

    $p->setExtCallback('protobuf', function (Preprocessor $p) {
        // compatible with redis
        if ($p->getOsType() === 'macos') {
            echo `sed -i '.bak' 's/arginfo_void,/arginfo_void_protobuf,/g' ext/protobuf/*.c ext/protobuf/*.h ext/protobuf/*.inc`;
            echo `find ext/protobuf/ -name \*.bak | xargs rm -f`;
        } else {
            echo `sed -i 's/arginfo_void,/arginfo_void_protobuf,/g' ext/protobuf/*.c ext/protobuf/*.h ext/protobuf/*.inc`;
        }
    });


    $p->setExtHook('protobuf', function (Preprocessor $p) {

        // compatible with redis
        $workdir= $p->getWorkDir();
        if ($p->getOsType() === 'macos') {
            $cmd = <<<EOF
                cd {$workdir}
                sed -i '.bak' 's/arginfo_void,/arginfo_void_protobuf,/g' ext/protobuf/*.c ext/protobuf/*.h ext/protobuf/*.inc
                find ext/protobuf/ -name \*.bak | xargs rm -f

EOF;
        } else {
            $cmd = <<<EOF
                cd {$workdir}
                sed -i 's/arginfo_void,/arginfo_void_protobuf,/g' ext/protobuf/*.c ext/protobuf/*.h ext/protobuf/*.inc

EOF;
        }
        return $cmd;
    });
};
