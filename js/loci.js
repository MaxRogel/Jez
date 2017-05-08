
var lc;
var gLocId

$(document).ready(function(){

	//populate area list from $areas. This list was populated in loci.php 

	
	
	 $(".dropdown-menu li a").click(function(){
            $("#areas_dropdown").text($(this).text());
    });
	
	//attach click handler to nav buttons
	$(".Btn").click(function(){
		var loc_id, loc_no, area_id;
		var req = $(this).attr('id');
		
		
		//alert("loc is "+ $("#loci-list").val()+ " req is "+ $(this).attr('id'));
		if(req == "btnGo") {
			if(loc_id == ""){
				return;
			}else {
				//on GO - send loc_no and area_id
				loc_no = parseInt($("#loci-list option:selected").text());				
				area_id =  $("#area-list").val();
			}
		} else 
		{
			//if not GO then we used the global gLocId
			loc_id = gLocId;
		}
		
		//alert("sending loc_id: " + loc_id + " loc_no: " + loc_no + " area_id: " + area_id);
		
		$.ajax({
		type: "POST",
		dataType: "json",
		url: "get_locus_info.php",
		data: 'loc_id=' + loc_id + '&req='+ req + '&loc_no=' + loc_no + '&area_id='+ area_id,
		error: function(xhr, error)
		{
			alert("ajax error");
		},
		success: function(data){	
			displayLocusInfo(data);
		}
		
		});
	});
 
 $('#btnFirst').trigger('click');
 
 });

//Here and NOT in doc.ready (2 hours to realize this)
function getLocusList(val) {
	$.ajax({
	type: "POST",
	url: "get_loci_per_area.php",
	data:'area_id='+val,
	success: function(data){
		//populate loci-list according to area chosen
		$("#loci-list").html(data);
	}
	});
}

function displayLocusInfo(data) {
	lc = data;
	gLocId = data["loc"].Locus_ID;
	//alert("loc id is "+ gLocId);	
	loc= data["loc"];	
//$("#msg").html(data["json"]);	

	$locName = loc.YYYY + '.' + loc.AreaName + '.' + pad(loc.Locus_no, 3);

	$("#locusID").val(loc.Locus_ID);
	$("#locusName").val($locName);
	
	
	$("#date_opened").val(FormatDate(loc.Date_opened));
	$("#date_closed").val(FormatDate(loc.Date_closed));
	
	$("#square").val(loc.Square);
	$("#level_open").val(FormatString(loc.Open_Level));
	$("#level_closed").val(loc.Close_Level);
	$("#loc_above").val(loc.Locus_Above);
	$("#loc_below").val(loc.Locus_Below);
	$("#co_exist").val(loc.Locus_CoExisting);

	$("#find_summary").val('PT(' + data.ptCnt + ') AR(' + data.arCnt + ') LB(' + data.lbCnt + ') FL(' + data.flCnt + ') GS('+ data.gsCnt + ') Images('+ data.imCnt + ')');

	$("#description").val(FormatString(loc.Description));
	$("#deposit").val(FormatString(loc.Deposit_description));
	$("#registration").val(FormatString(loc.Registration_notes));

	if(!$("#description").val())
	{
		$("#description").hide();
		$('label[for=description], input#description').hide();
	}
	else
	{
		$("#description").show();
		$('label[for=description], input#description').show();
	}
	
	
	if(!$("#deposit").val())
	{
		$("#deposit").hide();
		$('label[for=deposit], input#deposit').hide();
	}
	else
	{	
		$("#deposit").show();
		$('label[for=deposit], input#deposit').show();
	}
	
	if(!$("#registration").val())
	{
		$("#registration").hide();
		$('label[for=registration], input#registration').hide();
	}
	else
	{	
		$("#registration").show();
		$('label[for=registration], input#registration').show();
	}

	//find tables
	
	ptCnt=data["ptCnt"];
	arCnt=data["arCnt"];
	lbCnt=data["lbCnt"];
	flCnt=data["flCnt"];
	gsCnt=data["gsCnt"];
	imCnt=data["imCnt"];
	
	if(ptCnt > 0) {
		pt=data["pt"];
		
		trHTML = '<table style="width:100%" class="PT-table">';
		trHTML += '<tr><th>PT</th><th>Keep</th><th>Periods</th><th>Description</th><th>Notes</th><th>date</th><th>Lvl tp</th><th>Lvl bt</th></tr>';
		
		$.each(pt, function(index, rec) {
			
			trHTML += '<tr>';
			trHTML += '<td>' + rec.PT_no + '</td><td>' + rec.Keep + '</td><td>' + FormatString(rec.Pd_text) + '</td><td>' + FormatString(rec.Description) + '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.PT_date) + '</td><td>' + FormatString(rec.Top_Lv) + '</td><td>' + FormatString(rec.Bot_Lv) + '</td>';  
		   trHTML += '</tr>';
		});
	
		trHTML += '</table>';

	}
	
	
	if(arCnt > 0) {
		ar=data["ar"];
	
		trHTML += '<table style="width:100%" class="AR-table">';	
		trHTML += '<tr><th>AR</th><th>R/T pt</th><th>Description</th><th>Category</th><th>Notes</th><th>date</th><th>Level</th></tr>';
	
		$.each(ar, function(index, rec) {
			
		trHTML += '<tr>';
		trHTML += '<td>' + rec.AR_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + FormatString(rec.Description) + '</td><td>' + FormatString(rec.Category_Name) + '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.Date) + '</td><td>' + FormatString(rec.Level) + '</td>';  
		trHTML += '</tr>';
		});
		
		trHTML += '</table>';		
	}
	

	
	if(lbCnt > 0) {
		lb=data["lb"];
	
		trHTML += '<table style="width:100%" class="LB-table">';	
		trHTML += '<tr><th>LB</th><th>R/T pt</th><th>Description</th><th>Category</th><th>Quantity</th><th>Notes</th><th>Date</th></tr>';
	
		$.each(lb, function(index, rec) {
			
		trHTML += '<tr>';
		trHTML += '<td>' + rec.LB_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + FormatString(rec.Description) + '</td><td>' + FormatString(rec.Category_Name) + '</td><td>' + FormatString(rec.Quantity) + '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.LB_date) + '</td>';  
		trHTML += '</tr>';
		});
		
		trHTML += '</table>';		
	}
	
	if(flCnt > 0) {
		fl=data["fl"];
	
		trHTML += '<table style="width:100%" class="FL-table">';	
		trHTML += '<tr><th>FL</th><th>R/T pt</th><th>Weight</th><th>Description</th><th>Notes</th><th>Date</th></tr>';
	
		$.each(fl, function(index, rec) {
			
		trHTML += '<tr>';
		trHTML += '<td>' + rec.FL_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + rec.Wt_grams + '</td><td>' +  FormatString(rec.Description)+ '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.FL_date) + '</td>';  
		trHTML += '</tr>';
		});
		
		trHTML += '</table>';		
	}	

	if(gsCnt > 0) {
		gs=data["gs"];
	
		trHTML += '<table style="width:100%" class="GS-table">';	
		trHTML += '<tr><th>GS</th><th>R/T pt</th><th>No. of pieces</th><th>Description</th><th>Notes</th><th>Date</th></tr>';
	
		$.each(gs, function(index, rec) {
			
		trHTML += '<tr>';
		trHTML += '<td>' + rec.GS_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + rec.No_of_pieces + '</td><td>' +  FormatString(rec.Description)+ '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.GS_date) + '</td>';  
		trHTML += '</tr>';
		});
		
		trHTML += '</table>';		
	}	
	

	
	$("#msg").html(trHTML);

	//Images
	$('#images-space').empty();	
	
	if(imCnt > 0) {
		
		im=data["im"];
		var imHtml;

		
		$.each(im, function(index, rec) {
			$('#images-space').prepend($('<img>',{id: rec.Image_no, src: 'JZ13\\' + rec.Image_file_name + '.' + rec.File_type, width: '250px'}))	
		});

	}		
}

function pad(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}

function FormatDate(raw_date) {
	
	var formatted_date;
	
    if(raw_date != null)
	{
    	formatted_date = raw_date.substring(0, 10);
	}
    else
	{
    	formatted_date = "";
	}
	//alert("date: " + formatted_date);   
    return formatted_date;
}

function FormatString(raw_string) {
	
	var formatted_string = (raw_string) ? raw_string : '';
	
    return formatted_string;
}



























