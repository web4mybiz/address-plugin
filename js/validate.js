var jq = jQuery.noConflict();

jq(document).ready(function(){
	
	validate_billing_address();
	validate_shipping_address();
	
	//billing fields
	jq('#billing_email').focus();
	jq('#billing_address_1_field,#billing_city_field,#billing_country_field').css('visibility','hidden');
	jq('#billing_address_1_field,#billing_city_field,#billing_country_field').css('display','none'); 
	jq('#billing_address_1_field label,#billing_address_1').css('display','none');
	jq('#billing_city_field label,#billing_city').css('display','none');
	jq('#billing_country_field label,#billing_country').css('display','none');
	
	jq('#billing_address_2_1,#billing_address_3').on('input',validate_billing_address);
	jq('#billing_postcode').blur(validate_billing_address);
	
	//shipping fields
	
	jq('#shipping_address_1_field,#shipping_city_field,#shipping_country_field').css('visibility','hidden');
	jq('#shipping_address_1_field').css('display','none'); 
	jq('#shipping_city_field').css('display','none'); 
	jq('#shipping_country_field').css('display','none'); 
	jq('#shipping_address_1_field label,#shipping_address_1').css('display','none');
	jq('#shipping_city_field label,#shipping_city').css('display','none');
	jq('#shipping_country_field label,#shipping_country').css('display','none');
	
	jq('#shipping_address_2_1,#shipping_address_3').on('input',validate_shipping_address);
	jq('#shipping_postcode').blur(validate_shipping_address);
	
	jq('#shipping_address_1,#shipping_city,#billing_address_1,#billing_city').bind('input propertychange', function(){
		if(jq(this).val()){
			jq(this).css("border-color","green");
			jq(this).css("background-color","rgba(0, 255, 0, 0.08)");
		}else{
			jq(this).css("border-color","#b20000");
			jq(this).css("background-color","#ffeaea");
		}
	});
	jq('#shipping_address_1,#shipping_city,#billing_address_1,#billing_city').blur(function(){
		if(jq(this).val()){
			jq(this).css("border-color","green");
			jq(this).css("background-color","rgba(0, 255, 0, 0.08)");
		}else{
			jq(this).css("border-color","#b20000");
			jq(this).css("background-color","#ffeaea");
		}
	});
	jq('#billing_email').blur(function(){

		if(!jq(this).val()){
			jq(this).css("border-color","#b20000");
			jq(this).css("background-color","#ffeaea");
		}else if( !validateEmail(jq(this).val())){
				jq(this).css("border-color","#b20000");
				jq(this).css("background-color","#ffeaea");
			}else{
				jq(this).css("border-color","green");
				jq(this).css("background-color","rgba(0, 255, 0, 0.08)");
				
			}
	});
	
	jq('#billing_phone,#shipping_phone').blur(function(){

		if(!jq(this).val() || jq(this).val()==""){
			jq(this).css("border-color","#b20000");
			jq(this).css("background-color","#ffeaea");
		}else if( !validatePhone(jq(this).val())){
				jq(this).css("border-color","#b20000");
				jq(this).css("background-color","#ffeaea");
			}else if( validatePhone(jq(this).val())){
				jq(this).css("border-color","green");
				jq(this).css("background-color","rgba(0, 255, 0, 0.08)");
			}else{
				jq(this).css("border-color","#ddd");
				jq(this).css("background-color","#fff");
				
			}
	});
	
	jq('#billing_first_name,#billing_last_name,#shipping_first_name,#shipping_last_name').blur(function(){

		if(!jq(this).val()){
			jq(this).css("border-color","#b20000");
			jq(this).css("background-color","#ffeaea");
		}else{
			jq(this).css("border-color","green");
			jq(this).css("background-color","rgba(0, 255, 0, 0.08)");	
			}
	});
	
	jq('#billing_address_3,#billing_company,#shipping_company,#order_comments').blur(function(){//#billing_address_3,#shipping_address_3,

		if(!jq(this).val()){
			if( element !="#billing_address_3"){
				jq(this).css("border-color","#ddd");
				jq(this).css("background-color","#fff");
			}
		}else{
			jq(this).css("border-color","green");
			jq(this).css("background-color","rgba(0, 255, 0, 0.08)");	
			}
	});
	
	
	jq('#billing_postcode,#billing_address_2_1,#shipping_postcode,#shipping_address_2_1').blur(function(){

		if(!jq(this).val()){
			jq(this).css("border-color","#b20000");
			jq(this).css("background-color","#ffeaea");
		}else{
			jq(this).css("border-color","green");
			jq(this).css("background-color","rgba(0, 255, 0, 0.08)");	
			}
	});
	
});

function validateThis(element){
	
	if(!jq(element).val()){
		if( element !="#billing_address_3" && element !="#shipping_address_3"){
			jq(element).css("border-color","#b20000");
			jq(element).css("background-color","#ffeaea");
		}
	}else{
		jq(element).css("border-color","green");
		jq(element).css("background-color","rgba(0, 255, 0, 0.08)");	
		}
}


function validateEmail(email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( email );
}


function validatePhone(phone) {
  var phoneReg = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
  return phoneReg.test( phone );
}

function validate_billing_address(){

		var postcode=jq('#billing_postcode').val();
		postcode=postcode.replace(/\s/g, '');
		jq('#billing_postcode').val(postcode);
		var house_number=jq('#billing_address_2_1').val();
		var house_number_ext=jq('#billing_address_3').val();
        
		var address_2_formatted=house_number + ' ' + house_number_ext;
		
		//setting shipping_address_2_1 , shipping_address_3 same as billing by default when different shipping is unchecked, 
		//they can change the values with checking different address
		//commented on 16.03.2018
		//jq('#shipping_address_2_1').val(house_number);
		//jq('#shipping_address_3').val(house_number_ext);
		//jq('#shipping_postcode').val(postcode);
		
		if(house_number!='' && postcode!='' ){
			
			jq.ajax({
				url : postpostcode.ajax_url,
				type : 'post',
				data : {
					action : 'post_postcode',
					postcode : postcode,
					house_number : house_number
				},
				success : function( response ) {
					var snArray=new Array();
					var res = jq.parseJSON(response);
					var sn=res.streetnumber;

					//if (typeof sn !== 'undefined') {
					//	snArray=sn.split(':');
					//}
					
					validateThis('#billing_first_name');
					validateThis('#billing_last_name');
					validateThis('#billing_postcode');
					validateThis('#billing_address_2_1');
					validateThis('#billing_address_3');
					//validateThis('#billing_phone');
					
					//if(res[0]=="failure" || snArray.indexOf(house_number) == '-1'){ 
					
					if(typeof sn=='undefined' || sn==''){
						
						jq('#billing_address_1_field,#billing_city_field,#billing_country_field').css('visibility','visible');
						jq('#billing_address_1_field,#billing_city_field,#billing_country_field').css('display','block'); 							
						jq('#billing_address_1_field label,#billing_address_1').css('display','block');
						jq('#billing_city_field label,#billing_city').css('display','block');
						jq('#billing_country_field label,#billing_country').css('display','block');
						jq('#billing_address_1,#billing_city').prop("disabled", false);
						//jq('#billing_address_1').prop("disabled", false).val('').css("border-color","#b20000");
						//jq('#billing_city').prop("disabled", false).val('').css("border-color","#b20000");
						//jq('#billing_address_1').css("background-color","#ffeaea");
						//jq('#billing_city').css("background-color","#ffeaea");
						jq('.success_billing_address').remove();
						jq('input:hidden[name=billing_address_2]').val(address_2_formatted);
					}else{
						//alert(res.street_name + ' ' + res.city);
						//jq('#billing_postcode,#billing_address_2_1').css("border-color","green");
						//jq('#billing_postcode,#billing_address_2_1').css("background-color","rgba(0, 255, 0, 0.08)");					
						
						jq('#billing_address_1_field,#billing_city_field,#billing_country_field').css('visibility','hidden');
						jq('#billing_address_1_field,#billing_city_field,#billing_country_field').css('display','none'); 
						 
						jq('#billing_address_1_field label,#billing_address_1').css('display','none');
						jq('#billing_city_field label,#billing_city').css('display','none');
						jq('#billing_country_field label,#billing_country').css('display','none');
						//jq('#billing_address_1').val(res.street_name).prop("disabled", true).css("background-color","#EBEBE4");
						//jq('#billing_city').val(res.city).prop("disabled", true).css("background-color","#EBEBE4");
						//jq('#billing_address_1').css("border-color","#ddd");
						//jq('#billing_city').css("border-color","#ddd");
						jq('#billing_address_1').val(res.street);
						jq('input:hidden[name=billing_address_2]').val(address_2_formatted);
						jq('#billing_city').val(res.city);
						jq('#billing_country').val('NL');
						var country=jq('#billing_country option:selected').text();
						//write address
						jq('.success_billing_address').remove();
						var full_address= res.street +' '+ house_number + ' '+house_number_ext+ ', ' + res.city + ' - ' + country;
						jq('#billing_address_3_field').after('<p class="success_billing_address">'+ full_address +'</p>');
					}
					//end success function
				},
				error: function(jqXHR, textStatus, errorThrown){
					console.log(errorThrown);
				}
			}); //end .ajax
		}//end main if
	}


function validate_shipping_address(){
	if(jq('#shipping_postcode').val()){
		var postcode=jq('#shipping_postcode').val();
	}else{
		var postcode=jq('#billing_postcode').val();
	}
		
		postcode=postcode.replace(/\s/g, '');
		jq('#shipping_postcode').val(postcode);
		var house_number=jq('#shipping_address_2_1').val();
		var house_number_ext=jq('#shipping_address_3').val();
        
		var ship_address_2_formatted=house_number + ' ' + house_number_ext;
		
		if(house_number!='' && postcode!='' ){
			
			jq.ajax({
				url : postpostcode.ajax_url,
				type : 'post',
				data : {
					action : 'post_postcode',
					postcode : postcode,
					house_number : house_number
				},
				success : function( response ) {
					var snArray=new Array();
					var res = jq.parseJSON(response);
					var sn=res.streetnumber;
					//if (typeof sn !== 'undefined') {
						//snArray=sn.split(':');
					//}						
					validateThis('#shipping_first_name');
					validateThis('#shipping_last_name');
					validateThis('#shipping_postcode');
					validateThis('#shipping_address_2_1');
					validateThis('#shipping_address_3');
						
					//if(res[0]=="failure" || snArray.indexOf(house_number) == '-1'){
					if(typeof sn=='undefined' || sn==''){
						//jq('#shipping_postcode,#shipping_address_2_1').css("border-color","#b20000");
						//jq('#shipping_postcode,#shipping_address_2_1').css("background-color","#ffeaea");	
						
						jq('#shipping_address_1_field,#shipping_city_field,#shipping_country_field').css('visibility','visible');
						jq('#shipping_address_1_field,#shipping_city_field,#shipping_country_field').css('display','block'); 							
						jq('#shipping_address_1_field label,#shipping_address_1').css('display','block');
						jq('#shipping_city_field label,#shipping_city').css('display','block');
						jq('#shipping_country_field label,#shipping_country').css('display','block');
						jq('#shipping_address_1,#shipping_city').prop("disabled", false);
						//jq('#shipping_address_1').prop("disabled", false).val('').css("border-color","#b20000");
						//jq('#shipping_city').prop("disabled", false).val('').css("border-color","#b20000");
						//jq('#shipping_address_1').css("background-color","#ffeaea");
						//jq('#shipping_city').css("background-color","#ffeaea");
						jq('.success_shipping_address').remove();
						jq('input:hidden[name=shipping_address_2]').val(ship_address_2_formatted);
					}else{
						//alert(res.street_name + ' ' + res.city);
						
						//jq('#shipping_postcode,#shipping_address_2_1').css("border-color","green");
						//jq('#shipping_postcode,#shipping_address_2_1').css("background-color","rgba(0, 255, 0, 0.08)");
						
						jq('#shipping_address_1_field,#shipping_city_field,#shipping_country_field').css('visibility','hidden');
						jq('#shipping_address_1_field,#shipping_city_field,#shipping_country_field').css('display','none'); 
						 
						jq('#shipping_address_1_field label,#shipping_address_1').css('display','none');
						jq('#shipping_city_field label,#shipping_city').css('display','none');
						jq('#shipping_country_field label,#shipping_country').css('display','none');
						//jq('#shipping_address_1').val(res.street_name).prop("disabled", true).css("background-color","#EBEBE4");
						//jq('#shipping_city').val(res.city).prop("disabled", true).css("background-color","#EBEBE4");
						//jq('#shipping_address_1').css("border-color","#ddd");
						//jq('#shipping_city').css("border-color","#ddd");
						jq('#shipping_address_1').val(res.street);
						jq('input:hidden[name=shipping_address_2]').val(ship_address_2_formatted);
						jq('#shipping_city').val(res.city);
						jq('#shipping_country').val('NL');
						var country=jq('#shipping_country option:selected').text();
						//write address
						jq('.success_shipping_address').remove();
						var full_address= res.street +' '+ house_number + ' '+house_number_ext+ ', ' + res.city + ' - ' + country;
						jq('#shipping_address_3_field').after('<p class="success_shipping_address">'+ full_address +'</p>');
					}
				}//end success function
			}); //end .ajax
		}//end main if
	}