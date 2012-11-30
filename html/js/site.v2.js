// Live site data updater, jquery this time! :)

function fetcher(){
	// Do the ajax fetching of our data!
	$.ajax({
		url: '/json/get-site?site='+ nwsli,
		dataType: 'json',
		success: function(data){
			//console.log(data);
			$('#ds_ts').text( data.data[0].ts );
			$('#ds_tmpf').text( data.data[0].tmpf );
			$('#ds_ntmpf').text( data.data[0].ntmpf );
			$('#ds_xtmpf').text( data.data[0].xtmpf );
			$('#ds_dwpf').text( data.data[0].dwpf );
			$('#ds_srad').text( data.data[0].srad );
			$('#ds_drct').text( data.data[0].drct );
			$('#ds_sped').text( data.data[0].sped );
			$('#ds_relh').text( data.data[0].relh );
			$('#ds_feel').text( data.data[0].feel );
			$('#ds_pday').text( data.data[0].pday );
			$('#ds_pmonth').text( data.data[0].pmonth );
			$('#ds_pres').text( data.data[0].pres );
			$('#ds_xsrad').text( data.data[0].xsrad );
			$('#ds_xsped').text( data.data[0].xsped );
			$('#ds_xdrct').text( data.data[0].xdrct );
		}
	});
}

$(function(){
	fetcher();
	setInterval(fetcher, 7000);
});
