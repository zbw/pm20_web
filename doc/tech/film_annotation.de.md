---
title: "Annotationen | ZBW Pressearchiv"
etr: doc
---

# Annotationen für Filme - Anleitung (Entwurf)

Damit BenutzerInnen Metadaten zu den auf den Filmen enthaltenen Mappen und
Dokumenten erfassen können, wird der Non-profit Webservice
[Hypothes.is](https://web.hypothes.is/) eingebunden. Im Filmviewer werden die
Hypothes.is-Icons oben am rechten Fensterrand angezeigt. User, die Annotationen
erfassen wollen, müssen sich bei dem Service mit einer Email-Adresse
registrieren und anmelden. Alle Annotationen, die in Hypothes.is als "Public"
erfasst wurden, werden für den Filmviewer ausgewertet.

In den Hypothes.is-Annotationen können beliebige Texte erfasst werden. Um
sie maschinell parsen und die Anzeige im Filmviewer damit anreichern zu können,
wird hier ein standardisiertes Format für Kommentare definiert.

Wenn die erste Zeile der Annotation mit einem Feld-Marker beginnt, wird
diese Zeile als Feldstruktur verstanden. Folgefelder sind durch mindestens ein
Leerzeichen und einen anderen Feld-Marker getrennt. Feld-Marker dürfen nicht
mehrfach auftreten. Ihre Reihenfolge ist nicht entscheidend, sollte jedoch aus
Gründen der Lesbarkeit der untenstehenden Liste folgen.

Weitere Informationen können formlos folgen (Leerzeile als optischen Trenner
setzen), werden aber nicht automatisiert ausgewertet.

Folgende Feld-Marker werden erkannt:

- <tt>$m</tt> - neue Mappe / folder start mark
- <tt>$a</tt> - neuer Artikel (bei neuer Mappe nicht erforderlich) / article start (implied with new folder)
- <tt>$r</tt> - rechte Seite (ansonsten links) / right page (left page implied)
- <tt>$s</tt> - Signatur der Mappe (Format noch festzulegen / signature of the folder (format tbd)
- <tt>$d</tt> - Datum der Publikation (JJJJ-MM-TT, JJJJ-MM, JJJJ) / date of publication (YYYY-MM-DD, YYYY-MM, YYYY)
- <tt>$p</tt> - reserviert für Name (oder Code) der Publikation / reserved for name (or code) of publication, format tbd
- <tt>$t</tt> - reserviert für Autor_in des Textes / reserved for author of the text, format tbd (WD QID, or "x" for named, but not identified?)
- <tt>$i</tt> - reserviert für Autor_in der Abbildung / reserved for author of the image, format tbd (WD QID, or "x" for named, but not identified?)
- <tt>$x</tt> - skip, Trennseite / skip, delimiter page

Beispieleinträge:

<pre>
$m $s A10 f2_Sm5 $d 1920-12-01

$a

$m $s E28
</pre>

Die Annotationen können auf Seitenebene erfasst werden (Notiz-Symbol am
rechten Bildschirmrand), es ist also nicht erforderlich, einen Teil des Textes
auszuwählen.

[zurück zum Film-Überblick](.)

