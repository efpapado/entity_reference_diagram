google.load("visualization", "1", {
  packages: ["orgchart"]
});
google.setOnLoadCallback(drawChart);

function drawChart() {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Name');
  data.addColumn('string', 'Parent');
  data.addColumn({
    'type': 'string',
    'role': 'tooltip'
  });
  var rows = [];
  var self_relations = [];

  /**
   * selves. As orgChat only accept 1 relationship between 2 entities. 
   * For those entity has multiple parents, we try make a duplicate.
   * @type type
   */
  var selves = [];
  var dummy_keys = [];
  var entity_relations = JSON.parse(drupalSettings.erd.entity_relations);
  
  for(var j in entity_relations) {
    var e = entity_relations[j];
    if (e.self === e.parent) {
      // self relation
      self_relations.push({
        name: e.self,
        desc: e.desc
      });
    } else {
      var row;
      if (selves.indexOf(e.self) >= 0) {
        //multi parents
        var i = 1;
        while (selves.indexOf(e.self + '_dummy_' + i) >= 0) {
          i++;
        }
        var dummy = e.self + '_dummy_' + i;
        selves.push(dummy);
        row = [{
          v: dummy,
          f: e.self + '<br />Duplicated dummy<br />' + e.desc.join('<br />')
        },
        e.parent, ''];
        dummy_keys.push(rows.length);
      } else {
        selves.push(e.self);
        row = [{
          v: e.self,
          f: e.self + '<br />' + e.desc.join('<br />')
        },
        e.parent, ''];
      }
      rows.push(row);
    }
  }
  //tidy up self relations
  self_relations.forEach(function(e, i, a) {
    var k = getNameKey(e.name, rows);
    if (k >= 0) {
      rows[k][0].f += '<br /><b>self:</b> ' + e.desc.join('<br />');
    } else {
      var row = [{
        v: e.name,
        f: e.name + '<br /><b>self:</b> ' + e.desc.join('<br />')
      }, '', ''];
      rows.push(row);
    }
  });

  data.addRows(rows);

  dummy_keys.forEach(function(e, i, a) {
    data.setRowProperty(e, 'style', 'background-color: #ccc;');
  });

  var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
  chart.draw(data, {
    allowHtml: true,
    tooltip: {
      isHtml: true
    },
    legend: 'none'
  });
}

function getNameKey(name, arr) {
  for (var i = 0; i < arr.length; i++) {
    if (arr[i][0].v === name) {
      return i;
    }
  }
  return -1;
}