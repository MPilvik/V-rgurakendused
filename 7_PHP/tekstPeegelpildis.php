<?php

$tekst = "Kirjutada PHP programm, kus mingis muutujas olev tekst esitatakse peegelpildis.";

echo "Originaaltekst: $tekst \n";

$tekstiPikkus = strlen($tekst)-1;

echo "Tagurpidi tekst: ";
while ($tekstiPikkus>=0) {
    echo "$tekst[$tekstiPikkus]";
    $tekstiPikkus = $tekstiPikkus - 1;
};
?>