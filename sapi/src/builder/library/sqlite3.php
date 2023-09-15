<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $sqlite3_prefix = SQLITE3_PREFIX;
    $p->addLibrary(
        (new Library('sqlite3'))
            ->withHomePage('https://www.sqlite.org/index.html')
            ->withLicense('https://www.sqlite.org/copyright.html', Library::LICENSE_SPEC)
            ->withManual('https://www.sqlite.org/docs.html')
            ->withUrl('https://www.sqlite.org/2023/sqlite-autoconf-3430100.tar.gz')
            ->withPrefix($sqlite3_prefix)
            ->withConfigure(
                <<<EOF
                ./configure --help
                CFLAGS="-DSQLITE_ENABLE_COLUMN_METADATA=1" \
                ./configure \
                --prefix={$sqlite3_prefix} \
                --enable-shared=no \
                --enable-static=yes

EOF
            )
            ->withBinPath($sqlite3_prefix)
            ->withPkgName('sqlite3')
            ->withBinPath($sqlite3_prefix . '/bin/')
    );
};
