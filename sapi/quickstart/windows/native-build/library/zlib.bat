@echo off

echo %~dp0
cd %~dp0
cd ..\..\..\..\..\

set __PROJECT__=%cd%
echo %cd%
cd %__PROJECT__%
mkdir  build /S /Q


cd thirdparty\zlib
echo %cd%
dir

mkdir  build /S /Q

cd build
dir
echo %cd%

cmake .. ^
-DCMAKE_INSTALL_PREFIX="%__PROJECT__%\build\zlib" ^
-DCMAKE_BUILD_TYPE=Release  ^
-DBUILD_SHARED_LIBS=OFF  ^
-DBUILD_STATIC_LIBS=ON

cmake --build . --config Release --target install

cd %__PROJECT__%
