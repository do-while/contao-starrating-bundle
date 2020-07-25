Contao-Starrating-Bundle
=================

Die Erweiterung bietet die Möglichkeit einfach per Inserttag eine Sterne-bewertungs-Element nach belieben zu platzieren. Im Backend hat man dann die Möglichkeit ein oder mehrere Bewertungs-Konfigurationen anzulegen. Darin werden dann als Kind-Elmeente die dazugehörenden Seiten mit deren Bewertungen gespeichert.

**InsertTag für Abstimmung**
Als zweiter Paramter wird die ID oder der Alias der Konfiguration benötigt.
Der dritte Parameter ist optional und gibt den Seitenalias an.
`{{starrating::ID|ALIAS::ALIAS}}`


**InsertTag für Sterne-Anzeige**
Als zweiter Paramter wird die ID oder der Alias der Konfiguration benötigt.
Der dritte Parameter ist optional und gibt den Seitenalias an.
`{{starview::ID|ALIAS::ALIAS}}`


Original von Sven Rhinow:   https://gitlab.com/srhinow/contao-starrating-bundle
____
2020-07-25 Softleister
