/*
 Display SPARQL result files for PM20 reports
 */

"use strict"

// YASR settings and initialization

// merge fitting labels into uris, don't try to fetch from preflabel.org
YASR.plugins.table.defaults.mergeLabelsWithUris = true;
YASR.plugins.table.defaults.fetchTitlesFromPreflabel = false;
YASR.plugins.table.defaults.datatable.pageLength = 50;
YASR.plugins.pivot.defaults.mergeLabelsWithUris = true;
// don't load google content (protect privacy)
YASR.plugins.pivot.defaults.useGoogleCharts = false;
// disable persistency
YASR.defaults.persistency.prefix = false;

var yasr = YASR(document.getElementById("yasr"), {
  drawOutputSelector: false,
//  drawDownloadIcon: false,
  useGoogleCharts: false
});

// main function
var loadSparqlResult = function(args) {
  var reportFileName, reportUrl, title_stub;

  // get datafile name
  reportFileName = getParameterByName("jsonFile");
  document.getElementById("jsonFile").setAttribute("href", reportFileName);

  if (getParameterByName("main_title") !== '') {
    document.getElementById("main_title").innerHTML = getParameterByName("main_title");

    // prepend the title element (e.g. "ZBW Press Archives") with the page title
    title_stub = document.title;
    document.title = getParameterByName("main_title") + " | " + title_stub;
  }
    
  // load datafile
  jQuery.getJSON(reportFileName, function (data) {
    window.yasr.setResponse(data);

    // execute search from parameter
    if (getParameterByName('search') !== '') {
      YASR.$('.dataTables_filter input').val(getParameterByName('search')).keyup();
    }
  });
};


// helper function
function getParameterByName(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
  return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

