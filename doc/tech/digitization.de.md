---
title: "Notizen Digitalisierung"
backlink: ./about
backlink-title: Dokumentation / Documentation
---

# Notizen Digitalisierung

## Personenarchiv - nicht digitalisiertes Material

* [Liste von Personen mit Presseausschnitten ab 1949](https://pm20.zbw.eu/report/pm20_result.de.html?jsonFile=pe/persons_undigitized.json&main_title=Nicht+digitalisierte+Personenmappen) - _umfangreich, braucht 10-15 sec zum Laden!_
    - Spalten:
        - locations: Wirkungsort (Nationalität kann abweichen)
        - clip: Ausschnitte seit ...
        - wd: Wikidata-Link ([sehr unvollständig](https://tools.wmflabs.org/mix-n-match/?#/catalog/581), erst für weniger als die Hälfte der Einträge)
        - wm: Anzahl der Wikimedia-Seiten, Default-Sortierung ("Prominenz")
        - wp: Link auf deutsche Wikipedia-Seite
    - es kann nach allen Spalten sortiert werden
    - mit "Search" wird die Liste gefiltert, z.B. auf ein bestimmtes Land oder eine Berufsangabe

## Anleitungen, Background, etc.

* [GLAM Wiki: Digitization and Digitized Collection collaboration](https://outreach.wikimedia.org/wiki/GLAM/Digital_collections)
* [Data Roundtripping: a new frontier for GLAM-Wiki collaborations](https://space.wmflabs.org/2019/12/13/data-roundtripping-a-new-frontier-for-glam-wiki-collaborations/)
* [Help:Scanning](https://commons.wikimedia.org/wiki/Help:Scanning)
* [LoC Newspaper project](https://www.loc.gov/ndnp/guidelines/NDNP_202022TechNotes.pdf)


## Format

Papierarchiv 2004ff.: JPEG, 300 dpi, Graustufen, file output:

P000350000000000000000620000_0000_00000000HP_A.JPG: JPEG image data, JFIF standard 1.01, resolution (DPI), density 300x300, segment length 16, baseline, precision 8, 2480x3508, components 1

- [DFG Praxisregeln Digitalisierung](https://www.dfg.de/formulare/12_151/12_151_de.pdf)
    - unkomprimiertes TIFF oder JPEG2000 (mit 90%, max. 80% Kompression) (JPEG2000 auch bei LoC und BL)
    - PDF nur als zusätzliches / derivates Format
    - nur DFG-Viewer, keine Erwähnung von IIIF

* [Wikimedia Commons](https://commons.wikimedia.org/wiki/Commons:File_types)

    * Keine Unterstützung von JPEG2000
    * nur wenige TIFF-Varianten

* Webbrowser

    * JPEG2000 (bis auf Safari) [nicht unterstützt](https://caniuse.com/#feat=jpeg2000)

 
