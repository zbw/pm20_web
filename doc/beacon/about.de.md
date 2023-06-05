---
title: "Zugriff über GND und Beacon-Dateien"
backlink: ../about.de.html
backlink-title: Dokumentation
fn-stub: about
---

# Zugriff auf Pressemappen über die GND

Die meisten, aber nicht alle Mappen für Personen und Organisationen sind mit Identifikatoren aus der [Gemeinsamen Normdatei](https://de.wikipedia.org/wiki/Gemeinsame_Normdatei) versehen.


## Weiterleitungen von GND-IDs

Mit dem URL-Muster

    https://pm20.zbw.eu/gnd/{gnd-id}

können die Mappen für Personen und Organisationen aufgerufen werden. Bei Personen ist `{gnd-id}` die GND-ID _ohne_ den Bindestrich vor der Prüfziffer, bei Organisationen ist der Bindestrich erforderlich (z.B. https://pm20.zbw.eu/gnd/213147-X).


## BEACON-Dateien

Mechanismus für Verlinkung und Zugriff über GND (s.a. [Wikipedia:BEACON](https://de.wikipedia.org/wiki/Wikipedia:BEACON)).

- [Personen](BEACON-Personen.txt) (persistente URL: `http://purl.org/pressemappe20/beaconlist/pe`)
- [Körperschaften](BEACON-Institutionen.txt) (persistente URL: `http://purl.org/pressemappe20/beaconlist/co`)
- [Sachschlagworte](BEACON-Schlagworte.txt) (persistente URL: `http://purl.org/pressemappe20/beaconlist/sh`)
- [Waren-Sachschlagworte](BEACON-SchlagworteW.txt) (persistente URL: `http://purl.org/pressemappe20/beaconlist/wa`)
