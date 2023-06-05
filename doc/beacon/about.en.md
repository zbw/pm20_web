---
title: "Access via GND and BEACON files"
backlink: ../about.en.html
backlink-title: Documentation
fn-stub: about
---

# Access to PM20 folders via GND 

Most, but not all folders for persons and organizations are attributed with identifiers from the [Integrated Authority File](https://en.wikipedia.org/wiki/Integrated_Authority_File).

## Redirects from GND IDs

With the URL pattern

    https://pm20.zbw.eu/gnd/{gnd-id}

the folder pages for persons and companies can be accessed. For persons, `{gnd-id}` is the GND ID _without_ the hyphen before the check digit, for companies the hyphen is required (e.g., https://pm20.zbw.eu/gnd/213147-X).


## BEACON files

Mechanism for inking and access via GND (see [BEACON](https://meta.wikimedia.org/wiki/Dynamic_links_to_external_resources)).

- [Persons](BEACON-Personen.txt) (persistent URL: `http://purl.org/pressemappe20/beaconlist/pe`)
- [Institutions](BEACON-Institutionen.txt) (persistent URL: `http://purl.org/pressemappe20/beaconlist/co`)
- [Subject headings](BEACON-Schlagworte.txt) (persistent URL: `http://purl.org/pressemappe20/beaconlist/sh`)
- [Wares](BEACON-SchlagworteW.txt) (persistent URL: `http://purl.org/pressemappe20/beaconlist/wa`)
