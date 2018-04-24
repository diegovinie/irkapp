#!/bin/sh

# A falta de un módulo de gapi (googleapis) este script descarga la
# librería y la exporta como módulo permitiendo su accesso a través
# de require('$libdir/$gapifile')

libdir=lib
gapifile=gapi.js
gapiurl="https://apis.google.com/js/api.js"

echo 'Descargando gapi (googleapis)'
echo

if [ ! -d "$libdir" ]; then
  echo "Creando directorio '$libdir'"
  mkdir $libdir
else
  echo "No crea directorio '$libdir'"
fi
echo

echo "Entrando en '$libdir'"
cd $libdir
echo

echo "Descargando 'gapi'"
fetched=`curl $gapiurl`
echo 'export' $fetched > $gapifile
echo

echo "Saliendo de '$libdir'"
cd ..
echo

echo "gapi descargado con éxito"
echo
