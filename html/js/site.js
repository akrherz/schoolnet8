Ext.onReady(function(){

var tempRenderer = new function(v){ return v +" F"; }

var form = new Ext.FormPanel({
    layout    : 'form',
    title     : 'Live Observation',
    renderTo  : 'datagrid',
    id        : 'propGrid',
    width     : 300,
    url       : '/json/get-site?site='+ nwsli,
    reader    : new Ext.data.JsonReader({
	    root : 'data'
	    },
	    Ext.data.Record.create([
		  {name: 'ts'},
		  {name: 'tmpf', renderer: tempRenderer},
		  {name: 'dwpf'},
		  {name: 'relh'},
		  {name: 'xtmpf'},
		  {name: 'pres'},
		  {name: 'feel'},
		  {name: 'xsrad'},
		  {name: 'srad'},
		  {name: 'xdrct'},
		  {name: 'xsped'},
		  {name: 'sped'},
		  {name: 'drct'},
		  {name: 'pday'},
		  {name: 'pmonth'},
		  {name: 'ntmpf'}
	    ])
	),
    labelWidth: 145,
    defaults  : {
        readOnly   : true,
        xtype      : 'textfield',
        style      : 'border:0px; background:#fff; width: 125px;'
    },
    items     : [
      {fieldLabel : 'Timestamp', id : 'ts'},
      {fieldLabel : 'Air Temperature [F]', id : 'tmpf'},
      {fieldLabel : 'Dew Point Temp [F]', id : 'dwpf'},
      {fieldLabel : 'Relative Humidity [%]', id : 'relh'},
      {fieldLabel : "Feels Like [F]", id : 'feel'},
      {fieldLabel : "Pressure [inch]", id: 'pres'},
      {fieldLabel : "Wind Speed [mph]", id : 'sped'},
      {fieldLabel : "Wind Direction", id : 'drct'},
      {fieldLabel : "High Wind Speed [mph]", id : 'xsped'},
      {fieldLabel : "High Wind Speed Dir", id : 'xdrct'},
      {fieldLabel : 'High Temperature [F]', id : 'xtmpf', 
        style: 'color: #f00; border:0px; background:#fff;'},
      {fieldLabel : 'Low Temperature [F]', id : 'ntmpf',
        style: 'color: #00f; border:0px; background:#fff;'},
      {fieldLabel : 'Solar Radiation [W/m**2]', id : 'srad'},
      {fieldLabel : 'High Solar Radiation [W/m**2]', id : 'xsrad'},
      {fieldLabel : 'Today Rainfall [inch]', id : 'pday'},
      {fieldLabel : 'Monthly Rainfall [inch]', id : 'pmonth'}
    ]
});

var task = {
  run: function(){
       form.load();
  },
  interval: 7000
}
Ext.TaskMgr.start(task);

});
