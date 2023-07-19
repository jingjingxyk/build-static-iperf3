<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $workdir = $p->getBuildDir();
    $ovs_prefix = OVS_PREFIX;
    $ovn_prefix = OVN_PREFIX;
    $lib = new Library('ovn');
    $lib->withHomePage('https://github.com/ovn-org/ovn.git')
        ->withLicense('https://github.com/ovn-org/ovn/blob/main/LICENSE', Library::LICENSE_APACHE2)
        ->withUrl('https://github.com/ovn-org/ovn/archive/refs/tags/v23.06.0.tar.gz')
        ->withFile('ovn-v23.06.0.tar.gz')
        ->withManual('https://github.com/ovn-org/ovn/blob/main/Documentation/intro/install/general.rst')
        ->withPrefix($ovn_prefix)
        ->withCleanBuildDirectory()
        ->withCleanPreInstallDirectory($ovn_prefix)
        ->withBuildLibraryCached(false)
        ->withBuildScript(
            <<<EOF
        set -x
        sh ./boot.sh
        PACKAGES="openssl"
        CPPFLAGS="$(pkg-config  --cflags-only-I --static \$PACKAGES ) " \
        LDFLAGS="$(pkg-config   --libs-only-L   --static \$PACKAGES ) " \
        LIBS="$(pkg-config      --libs-only-l   --static \$PACKAGES ) " \
        ./configure  \
        --prefix={$ovn_prefix} \
        --enable-ssl \
        --enable-shared=no \
        --enable-static=yes \
        --with-ovs-source={$workdir}/ovs/ \
        --with-ovs-build={$workdir}/ovs/
        make -j {$p->maxJob}

        make dist-docs -j {$p->maxJob}
        make docs-check -j {$p->maxJob}
EOF
        )
        ->withPkgName('ovn')
        ->withDependentLibraries('ovs', 'openssl')
        ->withBinPath($ovn_prefix . '/bin/');

    $p->addLibrary($lib);
};
