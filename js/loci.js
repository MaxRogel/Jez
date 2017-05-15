
var lc;
var gLocId;


$(document).ready(function(){

	//dropdown area selection. This list was populated in loci.php 	

	 $(".area_dropdown li a").click(function(){	 
		 	
		//show selected area                   
		$("#areas_dropdown_toggle").html($(this).text()+' <span class="caret"></span>');
		
		//clear loci list
		$('.loci_dropdown').children().remove(); 
		$("#loci_dropdown_toggle").html("Locus" +' <span class="caret"></span>');
		
		//populate loci dropdown
		getLocusListBS($(this).text());
    
    });


 
	//attach click handler to loci navigation buttons
	$(".arrow-nav").click(function(){
		var area, area_name, yyyy, loc_id, loc_no, area_id;
		var req = $(this).attr('id');
		
		
		//alert("loc is "+ $("#loci-list").val()+ " req is "+ $(this).attr('id'));
		if(req == "bGo") {
			if(loc_id == ""){
				return;
			}else {
				//on GO - send loc_no and area_id
				loc_no = parseInt($("#loci_dropdown_toggle").text());
				area = $("#areas_dropdown_toggle").text()
				yyyy = area.split('.')[0];
				area_name = area.split('.')[1];	
				//alert("GO loc: " + loc_no + " yyyy: " + yyyy + " area_name: " + area_name);
			}
		} else 
		{
			//if not GO then we used the global gLocId
			loc_id = gLocId;
		}
		//alert("before ajax req: " + req + " loc_id: " + loc_id + " loc no: "+ loc_no + " yyyy: " + yyyy + " area_name: " + area_name);
		
		$.ajax({
		type: "POST",
		dataType: "json",
		url: "get_locus_info_bs.php",
		data: 'loc_id=' + loc_id + '&req='+ req + '&loc_no=' + loc_no + '&yyyy='+ yyyy + '&area_name='+ area_name,
		error: function(xhr, error)
		{
			alert("ajax error");
		},
		success: function(data){	
			displayLocusInfo(data);
		}
		
		});
	});
	
 $('#bFirst').trigger('click');
 
 });


function getLocusListBS(val) {

	YYYY = val.split('.')[0];
	area_name = val.split('.')[1];	
	//alert("getLocusListBS year: " + YYYY + ' name: ' + area_name);
	
	$.ajax({
	type: "POST",
	url: "get_loci_list.php",
	data:'YYYY=' + YYYY + '&area_name=' + area_name,
	success: function(data){

		$('.loci_dropdown').children().remove(); 
		
		$.each(JSON.parse(data), function(index, rec) {			
			$('#loci_list').append("<li><a href='#'>" + rec.Locus_no + "</a></li>");	
		});				
	}	
	});

	
	//attach click handler
	$(document).on('click', '.loci_dropdown li a', function(){
		//alert("loci click");
		
		
		                  
		$("#loci_dropdown_toggle").html($(this).text () +' <span class="caret"></span>');
		    


	});
	
	
}
function displayLocusInfo(data) {
	lc = data;
	gLocId = data["loc"].Locus_ID;
	gAreaID = data["loc"].Area_ID;
	//alert("loc id is "+ gLocId);	
	loc= data["loc"];	
//$("#msg").html(data["json"]);	

	$locName = loc.YYYY + '.' + loc.AreaName + '.' + pad(loc.Locus_no, 3);

	$("#locusID").val(loc.Locus_ID);
	$("#locusName").val($locName);
	$("#locus_name").val($locName);
	
	$("#date_opened").val(FormatDate(loc.Date_opened));
	$("#date_closed").val(FormatDate(loc.Date_closed));
	
	$("#square").val(loc.Square);
	$("#level_open").val(FormatString(loc.Open_Level));
	$("#level_closed").val(loc.Close_Level);
	
	$("#loc_above").val(loc.Locus_Above);
	$("#loc_below").val(loc.Locus_Below);
	$("#co_exist").val(loc.Locus_CoExisting);
	
	$("#above").val(loc.Locus_Above);
	$("#below").val(loc.Locus_Below);
	$("#co_existing").val(loc.Locus_CoExisting);
	
	
	$("#find_summary").val('PT(' + data.ptCnt + ') AR(' + data.arCnt + ') LB(' + data.lbCnt + ') FL(' + data.flCnt + ') GS('+ data.gsCnt + ') Images('+ data.imCnt + ')');

	$("#description").val(FormatString(loc.Description));
	$("#deposit").val(FormatString(loc.Deposit_description));
	$("#notes").val(FormatString(loc.Deposit_description));
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
	
	

	if(!$("#notes").val())
	{
		$("#notes").hide();
		$('label[for=notes], input#notes').hide();
	}
	else
	{	
		$("#notes").show();
		$('label[for=notes], input#notes').show();
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

	$('#pt_table td').remove();
	$('#ar_table td').remove();
	$('#lb_table td').remove();	
	$('#gs_table td').remove();	
	$('#fl_table td').remove();	
	
	if(ptCnt > 0) {
		$('#pt_table').show();		
		pt=data["pt"];
		
		var pt_row;
		
		
		trHTML = '<table style="width:100%" class="PT-table">';
		trHTML += '<tr><th>PT</th><th>Keep</th><th>Periods</th><th>Description</th><th>Notes</th><th>date</th><th>Lvl tp</th><th>Lvl bt</th></tr>';
		
		$.each(pt, function(index, rec) {
			
			trHTML += '<tr>';
			trHTML += '<td>' + rec.PT_no + '</td><td>' + rec.Keep + '</td><td>' + FormatString(rec.Pd_text) + '</td><td>' + FormatString(rec.Description) + '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.PT_date) + '</td><td>' + FormatString(rec.Top_Lv) + '</td><td>' + FormatString(rec.Bot_Lv) + '</td>';  
		   trHTML += '</tr>';
		   
		 pt_row += '<tr>';
		 pt_row += '<td>' + rec.PT_no + '</td><td>' + rec.Keep + '</td><td>' + FormatString(rec.Pd_text) + '</td><td>' + FormatString(rec.Description) + '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.PT_date) + '</td><td>' + FormatString(rec.Top_Lv) + '</td><td>' + FormatString(rec.Bot_Lv) + '</td>';  
		 pt_row += '</tr>';  
		   
		   
		   
		});
	
		trHTML += '</table>';
		pt_row += '</table>';
		
		 $('#pt_table').append(pt_row);
	}
	else
		$('#pt_table').show();		
	
	
	if(arCnt > 0) {
		$('#ar_table').show();		
		ar=data["ar"];

		var ar_row;
		
		trHTML += '<table style="width:100%" class="AR-table">';	
		trHTML += '<tr><th>AR</th><th>R/T pt</th><th>Description</th><th>Category</th><th>Notes</th><th>date</th><th>Level</th></tr>';
	
		$.each(ar, function(index, rec) {
			
		trHTML += '<tr>';
		trHTML += '<td>' + rec.AR_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + FormatString(rec.Description) + '</td><td>' + FormatString(rec.Category_Name) + '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.Date) + '</td><td>' + FormatString(rec.Level) + '</td>';  
		trHTML += '</tr>';
		
		
		ar_row += '<tr>';
		ar_row += '<td>' + rec.AR_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + FormatString(rec.Description) + '</td><td>' + FormatString(rec.Category_Name) + '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.Date) + '</td><td>' + FormatString(rec.Level) + '</td>';  
		ar_row += '</tr>';		
		
		});
		
		trHTML += '</table>';
		ar_row += '</table>';	
		
		$('#ar_table').append(ar_row);
	}
	else
		$('#ar_table').hide();	

	
	if(lbCnt > 0) {
		$('#lb_table').show();	
		lb=data["lb"];
		var lb_row;

		
		trHTML += '<table style="width:100%" class="LB-table">';	
		trHTML += '<tr><th>LB</th><th>R/T pt</th><th>Description</th><th>Category</th><th>Quantity</th><th>Notes</th><th>Date</th></tr>';
	
		$.each(lb, function(index, rec) {
			
		trHTML += '<tr>';
		trHTML += '<td>' + rec.LB_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + FormatString(rec.Description) + '</td><td>' + FormatString(rec.Category_Name) + '</td><td>' + FormatString(rec.Quantity) + '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.LB_date) + '</td>';  
		trHTML += '</tr>';
		
		lb_row += '<tr>';
		lb_row += '<td>' + rec.LB_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + FormatString(rec.Description) + '</td><td>' + FormatString(rec.Category_Name) + '</td><td>' + FormatString(rec.Quantity) + '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.LB_date) + '</td>';  
		lb_row += '</tr>';		
		
		});
		
		trHTML += '</table>';
		lb_row += '</table>';
		
		$('#lb_table').append(lb_row);
	}
	else
		$('#lb_table').hide();	
	
	if(flCnt > 0) {
		$('#fl_table').show();
		fl=data["fl"];
		var fl_row;
		
		trHTML += '<table style="width:100%" class="FL-table">';	
		trHTML += '<tr><th>FL</th><th>R/T pt</th><th>Weight</th><th>Description</th><th>Notes</th><th>Date</th></tr>';
	
		$.each(fl, function(index, rec) {
			
		trHTML += '<tr>';
		trHTML += '<td>' + rec.FL_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + rec.Wt_grams + '</td><td>' +  FormatString(rec.Description)+ '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.FL_date) + '</td>';  
		trHTML += '</tr>';
		
		fl_row += '<tr>';
		fl_row += '<td>' + rec.FL_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + rec.Wt_grams + '</td><td>' +  FormatString(rec.Description)+ '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.FL_date) + '</td>';  
		fl_row += '</tr>';		
		
		
		});
		
		trHTML += '</table>';	
		
		fl_row += '</table>';
		
		$('#fl_table').append(fl_row);
	}	
	else
		$('#fl_table').hide();

	if(gsCnt > 0) {
		$('#gs_table').show();
		gs=data["gs"];
		var gs_row;
		
		
		trHTML += '<table style="width:100%" class="GS-table">';	
		trHTML += '<tr><th>GS</th><th>R/T pt</th><th>No. of pieces</th><th>Description</th><th>Notes</th><th>Date</th></tr>';
	
		$.each(gs, function(index, rec) {
			
		trHTML += '<tr>';
		trHTML += '<td>' + rec.GS_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + rec.No_of_pieces + '</td><td>' +  FormatString(rec.Description)+ '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.GS_date) + '</td>';  
		trHTML += '</tr>';
		
		gs_row += '<tr>';
		gs_row += '<td>' + rec.GS_no + '</td><td>' + rec.Related_PT_no + '</td><td>' + rec.No_of_pieces + '</td><td>' +  FormatString(rec.Description)+ '</td><td>' + FormatString(rec.Notes) + '</td><td>' + FormatDate(rec.GS_date) + '</td>';  
		gs_row += '</tr>';				
		
		});
		
		trHTML += '</table>';
		gs_row += '</table>';
		
		$('#gs_table').append(gs_row);		
	}
	else
		$('#gs_table').hide();	
	

	
	$("#msg").html(trHTML);

	//Images
	$('#images-space').empty();	
	$('#image_row_1').empty();
	if(imCnt > 0) {
		
		im=data["im"];
		var imHtml;

		
		$.each(im, function(index, rec) {
			//$('#images-space').prepend($('<img>',{id: rec.Image_no, src: 'JZ_IMG_TN\\' + rec.Image_file_name + '.' + rec.File_type, width: '250px'}))
			
			
			imHtml = '<div class="col-lg-2"><a title="Image 1" href="JZ_IMG_FULL\\' + rec.Image_file_name + '" data-title="1st title" data-lightbox="finds-lightbox"> <img src="JZ_IMG_TN\\' + rec.Image_file_name + '" width="200px" class="img-thumbnail"></a></div>';
			$('#image_row_1').prepend(imHtml);
			
			
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



























