<style>
	button.btn_select_all, button.btn_select_del, button.btn_select_wishlist { border:0 }
	button.btn_ea_modify, button.btn_option_modify { border:0 }
	.goods_name {width:150px; text-align:left; word-wrap:break-word; } 
	<!--[[so]]-->
	.goods_option {padding-top:3px;font-color:#848484}   
	.goods_input {padding-top:3px;font-color:#848484}
	.sub_bg {background-color:#f7f7f7}

	.goods_option_table td {
	    color: #ffffff !important;
	    font-size:13px !important;
	}

	.goods_quantity_table .option_text {
	    color: #ffffff !important;
	    font-size:13px !important;
	}

	.goods_quantity_table .quantity_cell {
	    color: #ffffff !important;
	    font-size:13px !important;
	}
#fade {
	display: none;
	position:fixed;
	top: 0%;
	left: 0%;
	width: 100%;
	height: 100%;
	background-color: #e7e7e7;
	z-index: 9998 !important;
	-moz-opacity: 0.8;
	opacity: .70;
	filter: alpha(opacity=80);
}
	
#modal {
	display: none;
	position: fixed;
	top: 35%;
	left: 50%;
	margin-left:-40px;
	width: 80px;
	height: 80px;
	padding:30px 15px 0px;
	z-index: 9999 !important;
	text-align:center;
	overflow: auto;
}
</style>
{is_file_facebook_tag}

<script type="text/javascript">
	{?  APP_LIKE_TYPE == 'API' }
	try{ 
		window.fbAsyncInit = function() {
			FB.init({
			appId      : plus_app_id, //App ID
			status     : true, // check login status
			cookie     : true, // enable cookies to allow the server to access the session
			xfbml      : true,  // parse XFBML,
			oauth : true,
			version    : 'v{APP_VER}'
			});  
			{? cfg.order.fblike_ordertype && fblikesale }
				FB.getLoginStatus(function(response) {
					$("#fbloginlay").hide();
						{ ? !fbuser }
							$.ajax({'url' : '../sns_process/facebooklogincknone', 'type' : 'post'});
						{ / }
					if (response.status === 'connected') {
						var uid = response.authResponse.userID;
						var accessToken = response.authResponse.accessToken;
					} else if (response.status === 'not_authorized') {
						$("#fbloginlay").show();
					} else {
						$("#fbloginlay").show();
					}
				});
			{ / }
			{? APP_USE == 'f' } 
				// like 이벤트가 발생할때 호출된다.
				FB.Event.subscribe('edge.create', function(response) {
					$("#facebook_mgs").html("페이스북과 정보를 교환 중에 있습니다. 잠시만 기다려 주세요.");
					{? ( _SERVER.HTTP_HOST==APP_DOMAIN ) }
						$.ajax({'url' : '../sns_process/facebooklikeck', 'type' : 'post', 'data' : {'mode':'like', 'product_url':response}, 'dataType': 'json','success': function(result){$("#facebook_mgs").html("");order_price_calculate();}});
					{:}
						var url = 'http://{config_system.subDomain}/sns_process/facebooklikeck?mode=like&firstmallcartid={firstmallcartid}&product_url='+response;
						$.getJSON(url + "&jsoncallback=?", function(res) {$("#facebook_mgs").html("");order_price_calculate();}); 
					{/}
				});

				// unlike 이벤트가 발생할때 호출된다.
				FB.Event.subscribe('edge.remove', function(response) {
					$("#facebook_mgs").html("페이스북과 정보를 교환 중에 있습니다. 잠시만 기다려 주세요.");
					{? ( _SERVER.HTTP_HOST==APP_DOMAIN ) }
						$.ajax({'url' : '../sns_process/facebooklikeck', 'type' : 'post', 'data' : {'mode':'unlike', 'product_url':response}, 'dataType': 'json','success': function(result){$("#facebook_mgs").html("");order_price_calculate();}});
					{:}
						var url = 'http://{config_system.subDomain}/sns_process/facebooklikeck?mode=unlike&firstmallcartid={firstmallcartid}&product_url='+response;
						$.getJSON(url + "&jsoncallback=?", function(res) {$("#facebook_mgs").html("");order_price_calculate();}); 
					{/}
				});//
			{ / }
		}
	} catch (facebookjsok) {
	}
	{ / }
</script>

<!--
<br style="line-height:30px;" />
-->

<div class="tabs">
	<ul class="tab-links" style="display:inline">
		<a href = "#tab1">
			<li class="tab_onclick" id="tab_li_1" style="display:inline; line-height:2.5">
				기본배송&nbsp;<div class="tab_goods_count" style="display:inline-block;" id="regular_count">{regular_count}</div>
			</li>
		</a>
		<a href = "#tab2">
			<li class="tab_unclick" id="tab_li_2" style="display:inline; line-height:2.5; float:right">
				정기배송&nbsp;<div class="tab_goods_count"  style="display:inline-block;" id="subscription_count" >{subscription_count}</div>
			</li>
		</a>
	</ul>
</div>

<!-- tab-contents -->

<!-- tab1 start -->
<div class="tab-content">
	<div id = "tab1" class="tab active">
		

<div id="pinnn" style="background-color:#4C4C4C; color:#fff; width:440px; height:30px; padding-left:10px; padding-top:10px; position:fixed; font-weight:lighter; font-size:13.5px; line-height:15px; z-index:9999 !important"> 
	원하시는 시간에 배달해드립니다. <span style="font-size:11px;">*40,000원 이상 결제시 배송비 무료</span> <div id="quickcart_x" class="hand" style="padding-left:64px; display:inline-block" onclick="parent.cart_disappear();"><span style="font-size:25px;">x</span></div>
</div> 

<form name="orderFrm" id="orderFrm" method="post" action="cacluate" target="actionFrame"> 
</form>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:56px; background-color:#fff; z-index:9999 !important">
	<tr>
		<td>
			<!-- 타이틀 -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dn">
				<tr><td height="10"></td></tr>
				<tr><td><span class="desc">장바구니에 담은 상품은 일주일동안 보관됩니다. 일주일 후에는 상품이 삭제될 수 있음을 알려드립니다.</span></td></tr>
			</table> 

				<form name="cart_form" id="cart_form" method="post" target="actionFrame" action="order">
				<input type="hidden" name="cart_version" value='2' /> 
				<input type="hidden" name="subscription" value='0' />

				<!-- Button -->
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dn"> 
				<tr>
					<td>
						<button type="button" class="btn_select_all btn_small_whiteb hand" value="전체선택">
						전체선택
						<button type="button" class="btn_select_del btn_small_whiteb hand">
						선택삭제
						</button>
						<!--{ ? member_seq }--> <!--not using wishlist for now-->
						<button type="button" class="btn_select_wishlist dn"><img src="../images/design/btn_select_wishlist.gif" /></button>
						<!--{ / }-->
					</td>
				</tr>
				</table>
				<!-- /Button -->

				<br style="line-height:8px;" />

				<!-- Cart List -->  
				<table style="width:440px; height:440px !important; margin-top:25px; overflow-y:auto; overflow-x:hidden;" border="0" cellpadding="0" cellspacing="0" class="quick_cart_list" id="cart_list_regular"> 
				<thead class="dn"> 
				<tr class="dn">
					<th width="40" class="dn">선택</th> 
					<th width="150">주문상품</th>
					<th width="100">수량</th>
					<th width="90" class="dn">상품금액</th>
					<th width="100" class="dn">할인</th>
					<th width="90">가격</th><!--원래는 '할인가격'-->
					<th width="100" class="dn">적립</th>
					<th width="90" class="goods_delivery_info dn">배송비</th>
					<th width="90" > 삭제 </th> 
				</tr>
				</thead>
				<tbody style="display:block; height:420px; overflow-y:auto; overflow-x:hidden ">  


				<!--{ ? shipping_cart_list }-->
					<!--{ @ shipping_cart_list }-->

						<!--{ @ .value_ }--> 
						{? ..subscription == 0}
				<tr id="{..cart_option_seq}" class="{..goods_seq} tr_regular" style="height:80px !important;">
					<td class="cell dn" height="80" >
						<input type="checkbox" name="cart_option_seq[]" subscription="0" value="{..cart_option_seq}" />
					</td>
					<td class="cell">
						<table border="0" cellpadding="0" cellspacing="0"> 
						<tr>
							<td class="left noborder" width="80" valign="top" style="border-width:0px;">
							<a href="../goods/view?no={..goods_seq}" title="{..goods_name}" class="image_anchor" onclick="parent.image_anchor({..goods_seq});"> 
								<img src="{..image}" align="absmiddle" hspace="5" style="border:0px solid #ddd;" onerror="this.src='../images/common/noimage_list.gif'" width="60" />
							</a>
							</td>
							<td class="left noborder" valign="middle" style="width:160px; min-width:160px; max-width:160px;"> 
								<div class="goods_name">
									<a href="../goods/view?no={..goods_seq}" title="{..goods_name}" class="view_anchor" onclick="parent.view_anchor({..goods_seq});">{..goods_name}</a>&nbsp;<br> 
									<span id="item_price_id_{..goods_seq}" class="goods_price item_price_{..cart_option_seq}" style="font-size:11px">{..price}</span> <span style="font-size:11px">원 / </span> <span style="font-size:11px">{..summary}</span>
									<!--[[nick]] max_purchase cnt-->
									<p id="max_purchase_{..goods_seq}" style="display:none">{..max_purchase_ea}</p>
									<!--{? ..eventEnd }-->
									<div class="soloEventTd{..cart_option_seq}" style="font-face:Dotum; font-size:11px;">
										<img src="../images/common/icon_clock.gif" style="padding-bottom:2px;">남은시간
										<span style="background-color:#c61515; color:#ffffff; padding:2px; font-weight:bold;">
										<span id="soloday{..cart_option_seq}" style="color:#ffffff; font-weight:bold;">0</span>일
										<span id="solohour{..cart_option_seq}" style="color:#ffffff; font-weight:bold;">00</span>:
										<span id="solomin{..cart_option_seq}" style="color:#ffffff; font-weight:bold;">00</span>:
										<span id="solosecond{..cart_option_seq}" style="color:#ffffff; font-weight:bold;">00</span>
										</span>
									</div>
									<script>
										$(function() {
											timeInterval{..cart_option_seq} = setInterval(function(){
												var time{..cart_option_seq} = showClockTime('text', '{..eventEnd.year}', '{..eventEnd.month}', '{..eventEnd.day}', '{..eventEnd.hour}', '{..eventEnd.min}', '{..eventEnd.second}', 'soloday{..cart_option_seq}', 'solohour{..cart_option_seq}', 'solomin{..cart_option_seq}', 'solosecond{..cart_option_seq}', '{..cart_option_seq}');
												if(time{..cart_option_seq} == 0){
													clearInterval(timeInterval{.cart_option_seq});
													$(".soloEventTd{..cart_option_seq}").html("단독 이벤트 종료");
												}
											},1000);
										});
									</script>
									<!--{ / }-->
								</div>
								<!--{ ? ..option1 }-->
								<div class="goods_option">
									<img src="../images/common/icon_option.gif" />
									{?..title1}{..title1}:{ / }{..option1}
									{ ? ..option2 }{?..title2}{..title2}:{ / }{..option2} { / }
									{ ? ..option3 }{?..title3}{..title3}:{ / }{..option3} { / }
									{ ? ..option4 }{?..title4}{..title4}:{ / }{..option4} { / }
									{ ? ..option5 }{?..title5}{..title5}:{ / }{..option5} { / }
								</div>
								<!--{ / }-->
								<!--{ ? ..cart_inputs }-->
									<!--{ @ ..cart_inputs }-->
										<!--{ ? ...input_value }-->
										<div class="goods_input">
											<img src="../images/common/icon_input.gif" />
											{?...type=='file'}
												{?...input_title}{...input_title}:{ / } <a href="/mypage_process/filedown?file={...input_value}" target="actionFrame"><img src="/mypage_process/filedown?file={...input_value}" width="13" height="13" title="크게 보기" align="absmiddle" /> <span class="desc">{...input_value}</span></a>
											{:}
												{?...input_title}{...input_title}:{ / }{...input_value}
											{/}
										</div>
										<!--{ / }-->
									<!--{ / }-->
								<!--{ / }-->
								<!--{? cfg.order.fblike_ordertype && fblikesale //회원, 비회원체크 }-->
								<div class="fblikelay" style="padding-top:10px">
									{=snsLikeButton(..goods_seq,'button_count')}
								</div>
								<!--{ / }-->
							</td>
						</tr>
						</table>
					</td>
					<!--<td class="cell" width="140">
						<div id="cart_option_ea_{..cart_option_seq}">{=number_format(..ea)}</div>
						<div><button type="button" class="btn_ea_modify btn_small_whiteb hand" style="border: 1px solid #007E41;" id="{..cart_option_seq}">변경</button></div>
					</td>[[nick]] original 변경 code-->
					<td class="cell" width="140">
					<div class="quick_plus hand up_count_{..goods_seq}" style="width:30px; height:30px; color:#007e41; text-align:center; line-height:25px" onclick="quick_plus_timer({..cart_option_seq},{..goods_seq});" id="{..cart_option_seq}"  alt="up_count_btn_{..goods_seq}">
					  &and;
					  <button type="button" class="quick_plus dn" style="width:30px; height:30px;" onclick="quick_plus({..cart_option_seq},{..goods_seq}) ;" id="{..cart_option_seq}"></button>
					</div>

					<div  id="cart_option_ea_{..cart_option_seq}" style ="text-align:center; width:30px; padding-left:3px;" class="goods_seq_{..goods_seq}">{=number_format(..ea)}</div>

					<div class="quick_minus hand" style="width:30px; height:30px; color:#007e41; line-height:25px; text-align:center;" onclick="quick_minus_timer({..cart_option_seq},{..goods_seq}, 'regular');" id="{..cart_option_seq}">
					  &or;
					  <button type="button" class="quick_minus dn" style="width:30px; height:30px;" onclick="quick_minus({..cart_option_seq},{..goods_seq}) ;" id="{..cart_option_seq}">-</button>
					</div>

					</td>
					<td class="cell right dn"><span class="cart_option_orgprice_{..cart_option_seq}"><p>{=number_format(..price*..ea)}</p></span>원</td>
					<td class="cell center dn">
						<div id="cart_option_sale_total_{..cart_option_seq}">
							<!--{ ? ..sales.total_sale_price > 0 }-->
							{=number_format(..sales.total_sale_price)}원
							<!--{ : }-->
							-
							<!--{ / }-->
						</div>
						<div id="cart_option_sale_detail_{..cart_option_seq}" { ? ..sales.total_sale_price > 0 }{ : }class="hide"{ / }>
							<img src="../images/common/icon_dc_list.gif" alt="할인내역" class="price_area" class="hand" />
							<div class="absolute">
								<div class="sale_price_layer hide" style="width:200px;">
									<div class="title_line">할인내역</div>
									<br style="line-height:10px;" />
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<col width="78" />
									<tr>
										<th>구분</th>
										<th class="bolds ends">할인</th>
									</tr>
									<!--{ @ ..sales.title_list }-->
									<tr id="cart_option_{...key_}_saletr_{..cart_option_seq}" { ? ..sales.sale_list[...key_] > 0 }{ : }class="hide"{ / }>
										<td class="gr">{..sales.title_list[...key_]}</td>
										<td class="bolds ends prices">
											<span id="cart_option_{...key_}_saleprice_{..cart_option_seq}">{=number_format(..sales.sale_list[...key_])}</span>원
										</td>
									</tr>
									<!--{ / }-->
									</table>
								</div>
							</div>
						</div>
					</td>
					<td class="cell right min90" width="120">
						<span id="cart_option_price_id_{..goods_seq}" class="item_prices_regular cart_option_price_{..cart_option_seq}" style="width:120px;">{=number_format(..sales.result_price)}</span>원
					</td>
					<td class="cell dn">
						<table align="center" border="0"> 
						<col />
						<col width="50" />   
						<tr>
							<td><img src="../images/common/icon_ord_emn.gif" title="적립금" /></td>
							<td class="right"><span id="option_reserve_{..cart_option_seq}">{=number_format(..reserve)}</span>원</td>
						</tr>
						<!--{ ? isplusfreenot && isplusfreenot.ispoint}-->
						<tr>
							<td><img src="../images/common/icon_ord_point.gif" title="포인트" /></td>
							<td class="right"><span id="option_point_{..cart_option_seq}">{=number_format(..point)}</span>P</td>
						</tr>
						<!--{ / }-->
						</table>
					</td>
					<!--{ ?..index_ == 0 }-->
					<td class="cell goods_delivery_info dn" rowspan="{..rowspan}">
						<!--{ ? ..goods_kind == 'coupon' }-->
						쿠폰
						<!--{: ..shipping_policy == 'shop'}-->
						기본배송비
						<!--{ : }-->
						개별배송비
						<div id="goods_each_delivery_{..goods_seq}" class="desc">{=number_format(data_goods[..goods_seq].goods_shipping)}</div>
						<!--{ / }-->
					</td>
					<!--{ / }-->
					<td width="70"> <!--new td by [[so]] 한개씩 삭제 가능하게 -->
						<button type="button" class="hand" style="border-width:0px; background-color:#fff;"  onclick="quick_delete({..cart_option_seq},{..goods_seq}, 'regular') ;">x</button> 
					</td>
					<td width="10">&nbsp;</td>
				</tr>
						{ / }
				<!--{ @ ..cart_suboptions }-->
				<tr>
					<td class="cell sub_bg"></td>
					<td class="cell sub_bg">
						<table width="100%" border="0" cellpadding="0" cellspacing="0"> 
						<tr>
							<td class="right" width="80">
								<div style="padding-right:5px;"><img src="../images/common/icon_add_arrow.gif" /></div>
							</td>
							<td class="left"  valign="top">
								<img src="../images/common/icon_add.gif" />
								{ ? ...suboption }
									{ ? ...suboption_title }{...suboption_title}:{ / }{...suboption}
								{ / }
							</td>
						</tr>
						</table>
					</td>
					<td class="cell sub_bg"><div>{=number_format(...ea)}</div></td>
					<td class="cell sub_bg right">{=number_format(...price*...ea)}원</td>
					<td class="cell sub_bg center">
						<div id="cart_suboption_sale_total_{...cart_suboption_seq}">
							<!--{ ? ...sales.total_sale_price > 0 }-->
							{=number_format(...sales.total_sale_price)}원
							<!--{ : }-->
							-
							<!--{ / }-->
						</div>
						<div id="cart_suboption_sale_detail_{...cart_suboption_seq}" { ? ...sales.total_sale_price > 0 }{ : }class="hide"{ / }>
							<img src="../images/common/icon_dc_list.gif" alt="할인내역" class="price_area" class="hand" />
							<div class="absolute">
								<div class="sale_price_layer hide">
									<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#000000" >
									<col width="80" />
									<tr>
										<td class="left pdl5" height="25">
											<strong style="color:#fff;font-size:12px;">할인내역</strong>
										</td>
										<td class="right" height="25"></td>
									</tr>
									<!--{ @ ...sales.sale_list }-->
									<tr id="cart_suboption_{....key_}_saletr_{...cart_suboption_seq}" bgcolor="#ffffff" height="25" { ? ....value_ > 0 }{ : }class="hide"{ / }>
										<td class="left pdl5">
											<img src="../images/common/arrow_right.gif" />
											{...sales.title_list[....key_]}
										</td>
										<td class="right pdr5">
											<span id="cart_suboption_{....key_}_saleprice_{...cart_suboption_seq}">{=number_format(....value_)}</span>원
										</td>
									</tr>
									<!--{ / }-->
									</table>
								</div>
							</div>
						</div>
					</td>
					<td class="cell sub_bg right">
						<span id="cart_suboption_price_{...cart_suboption_seq}">{=number_format(...sales.result_price)}</span>원
					</td>
					<td class="cell sub_bg">
						<table align="center" border="0">
						<col />
						<col width="50" />
						<tr>
							<td><img src="../images/common/icon_ord_emn.gif" title="적립금" /></td>
							<td class="right"><span id="suboption_reserve_{...cart_suboption_seq}">{=number_format(...reserve)}</span>원</td>
						</tr>
						<!--{ ? isplusfreenot && isplusfreenot.ispoint}-->
						<tr>
							<td><img src="../images/common/icon_ord_point.gif" title="포인트" /></td>
							<td class="right"><span id="suboption_point_{...cart_suboption_seq}">{=number_format(...point)}</span>P</td>
						</tr>
						<!--{ / }-->
						</table>
					</td>
				</tr>
				<!--{ / }-->
				<!--{ / }-->
				<!--{ / }-->
				<!--{ : }-->
				<!--			<tr>
					<td class="cell" colspan="8"  height="80">장바구니에 담긴 상품이 없습니다.</td>
				</tr>
				-->
				<!--{ / }-->

				</tbody>
				
				</table>
				</form>

				

				<div class="cboth"></div>
				<div style="height:10px" class="dn"></div>
				<div align="center" id="facebook_mgs">{? APP_LIKE_TYPE == 'API' }페이스북과 정보를 교환 중에 있습니다. 잠시만 기다려 주세요.{/}</div>
				<div style="height:10px" class="dn"></div>

				<table align="right" height="80" border="0" cellpadding="0" cellspacing="0" class="dn">
					<tr>
						<td>
							<table width="80" border="0" cellpadding="0" cellspacing="0">
							<tr><td align="center" height="30">총 수량</td></tr>
							<tr><td align="center" height="25" class="fx16">{=number_format(total_ea)}</td></tr>
							</table>
						</td>
						<td>
							<table width="120" border="0" cellpadding="0" cellspacing="0">
							<tr><td align="center" height="30">총 판매가격</td></tr>
							<tr><td align="center" height="25" class="fx16">{=number_format(total)}</td></tr>
							</table>
						</td>

						<td class="goods_delivery_info">
							<table width="120" border="0" cellpadding="0" cellspacing="0">
							<tr><td align="center" height="30">배송비</td></tr>
							<tr><td align="center" height="25" class="fx16"><span id="total_shipping_price">{=number_format(array_sum(shipping_price))}</span></td></tr>
							</table>
						</td>
						<td>
							<table width="120" border="0" cellpadding="0" cellspacing="0">
							<tr><td align="center" height="30">총 할인</td></tr>
							<tr><td align="center" height="25" class="fx16"><span id="total_sale">{=number_format(total_sale)}</span></td></tr>
							</table>
						</td>
						<td>
							<table width="120" border="0" cellpadding="0" cellspacing="0">
							<tr><td align="center" height="30">총 결제금액</td></tr>
							<tr><td align="center" height="25" class="fx16"><span class="settle_price bold">{=number_format(total_price)}</span></td></tr>
							</table>
						</td>
							<td>
								<table width="120" border="0" cellpadding="0" cellspacing="0">
								<tr><td align="center" height="30">예상적립금</td></tr>
								<tr><td align="center" height="25" class="fx16"><span class="total_reserve" id="total_reserve">{=number_format(total_reserve)}</span></td></tr>
								</table>
							</td>

							<!--{ ? isplusfreenot && isplusfreenot.ispoint}-->
							<td>
								<table width="120" border="0" cellpadding="0" cellspacing="0">
								<tr><td align="center" height="30">예상포인트</td></tr>
								<tr><td align="center" height="25" class="fx16"><span class="total_point" id="total_point">{=number_format(total_point)}</span></td></tr>
								</table>
							</td>
							<!--{ / }-->
					</tr>
				</table>

			<div id="summary" style="position:fixed; top:465px; background-color:rgba(66, 66, 66, 0.57); z-index:9999 !importnat"> 
				<table width="100%" border="0" cellpadding="0" cellspacing="0" style="position:fixed; background-color:rgba(66, 66, 66, 0.57); color:#fff; padding-bottom:30px; padding-bottom:5px; padding-right:15px;">
				<tr class="domestic">
					<td height="34" align="right" style="padding:0 0px;" > 
						<div class="goods_delivery_info" id="goods_delivery_price_regular">
							기본배송비: {=number_format(shipping_price.shop)}원
							<!--{ ? shipping_price.goods > 0 }-->
							<p style="padding-left:20px;">개별배송비 : {=number_format(shipping_price.goods)}원</p>
							<!--{ / }-->
						</div>
					</td>
				</tr>
				
				<tr class="domestic">
					<td height="20" align="right" style="padding:0 0px;" > 
						<div style="display:block !important" class="goods_delivery_info" id="goods_delivery_total_regular" >
							총 결제금액: {=number_format(total_price)}원
	                    </div>
	                  </td>
	                </tr>
				</table>


	            <div class="dn" style="text-align:right; font-size:12px; position:fixed;">
	                <span>*40,000원 이상 결제시 배송비 무료</span>
	            </div>        

				<div style="width:100%; position:fixed; z-index:9999 !important">
					<span class="btn_all_order"  style="position:fixed; top:524px;">   
					
						<button type="submit" class="green_noborder hand" style="width:440px; border-radius:0px; " value="전체상품 주문하기" onclick="mixpanel_order();">주문하기
						</button>

					</span>
					<span class="btn_selected_order dn">
						<button type="submit" class="btn_whitebackground hand" value="선택상품 주문하기">선택상품 주문하기</button>
					</span>
				</div>

			</div>

				{navercheckout_tpl}

			<!-- 본문내용 끝 -->
		</td>
		<td width="40"></td>
	</tr>
</table>

</div>
<!-- tab1 end -->
    
<!-- tab2 start -->
<div id="tab2" class="tab">
	
<div id="pinnn" style="background-color:#4C4C4C; color:#fff; width:440px; height:30px; padding-left:10px; padding-top:10px; position:fixed; font-weight:lighter; font-size:13.5px; line-height:21px; z-index:9999 !important"> 
	<div style="float:left;">
		아래 상품들이 매주 고객님이 원하시는 시간에 배달됩니다. &nbsp;
		
		<button id="subscription_page" class="btn_small_whiteb" style=" font-size:11px; margin-top: -3px;">더 알아보기</button>
	</div>
	<div id="quickcart_x" class="hand" style="float:right; margin-right:20px; line-height:15px;" onclick="parent.cart_disappear();">
		<span style="font-size:25px;">x</span>
	</div>
</div> 

<form name="orderFrmSubscription" id="orderFrmSubscription" method="post" action="cacluate" target="actionFrame">
</form>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:56px; background-color:#fff; z-index:9999 !important">
<tr>
	<td>
		<!-- 타이틀 -->


			<form name="subscription_cart_form" id="subscription_cart_form" method="post" target="actionFrame" action="order">
			<input type="hidden" name="cart_version" value='2' /> 
			<input type="hidden" name="subscription" value='1' />

			<!-- Button -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dn"> 
				<tr>
					<td>
						<button type="button" class="btn_select_all btn_small_whiteb hand" value="전체선택">
						전체선택
						<button type="button" class="btn_select_del btn_small_whiteb hand">
						선택삭제
						</button>
						<!--{ ? member_seq }--> <!--not using wishlist for now-->
						<button type="button" class="btn_select_wishlist dn"><img src="../images/design/btn_select_wishlist.gif" /></button>
						<!--{ / }-->
					</td>
				</tr>
			</table>
			<!-- /Button -->

			<br style="line-height:8px;" />

			<!-- Cart List -->  
			<table style="width:440px; height:440px !important; margin-top:25px; overflow-y:auto; overflow-x:hidden;" border="0" cellpadding="0" cellspacing="0" class="quick_cart_list" id="cart_list_subscription"> 
			<thead class="dn"> 
			<tr class="dn">
				<th width="40" class="dn">선택</th> 
				<th width="150">주문상품</th>
				<th width="100">수량</th>
				<th width="90" class="dn">상품금액</th>
				<th width="100" class="dn">할인</th>
				<th width="90">가격</th><!--원래는 '할인가격'-->
				<th width="100" class="dn">적립</th>
				<th width="90" class="goods_delivery_info dn">배송비</th>
				<th width="90" > 삭제 </th> 
			</tr>
			</thead>
			<tbody style="display:block; height:420px; overflow-y:auto; overflow-x:hidden ">   
			<!--{ ? shipping_cart_list }-->
				<!--{ @ shipping_cart_list }-->
					<!--{ @ .value_ }-->
					{? ..subscription == 1}
			<tr id="{..cart_option_seq}" class="{..goods_seq} tr_subscription" style="height:80px !important;">
				<td class="cell dn" height="80" >
					<input type="checkbox" name="cart_option_seq[]" subscription="1" value="{..cart_option_seq}" />
				</td>
				<td class="cell">
					<table border="0" cellpadding="0" cellspacing="0"> 
					<tr>
						<td class="left noborder" width="80" valign="top" style="border-width:0px;">
						<a href="../goods/view?no={..goods_seq}" title="{..goods_name}" class="image_anchor" onclick="parent.image_anchor({..goods_seq});"> 
							<img src="{..image}" align="absmiddle" hspace="5" style="border:0px solid #ddd;" onerror="this.src='../images/common/noimage_list.gif'" width="60" />
						</a>
						</td>
						<td class="left noborder" valign="middle" style="width:160px; min-width:160px; max-width:160px;"> 
							<div class="goods_name">
								<a href="../goods/view?no={..goods_seq}" title="{..goods_name}" class="view_anchor" onclick="parent.view_anchor({..goods_seq});">{..goods_name}</a>&nbsp;<br> 
								<span id="item_price_id_subscription_{..goods_seq}" class="goods_price_subscription item_price_{..cart_option_seq}" style="font-size:11px">{..price}</span> <span style="font-size:11px">원 / </span> <span style="font-size:11px">{..summary}</span>
								<!--[[nick]] max_purchase cnt-->
								<p id="max_purchase_{..goods_seq}" style="display:none">{..max_purchase_ea}</p>
								<!--{? ..eventEnd }-->
								<div class="soloEventTd{..cart_option_seq}" style="font-face:Dotum; font-size:11px;">
									<img src="../images/common/icon_clock.gif" style="padding-bottom:2px;">남은시간
									<span style="background-color:#c61515; color:#ffffff; padding:2px; font-weight:bold;">
									<span id="soloday{..cart_option_seq}" style="color:#ffffff; font-weight:bold;">0</span>일
									<span id="solohour{..cart_option_seq}" style="color:#ffffff; font-weight:bold;">00</span>:
									<span id="solomin{..cart_option_seq}" style="color:#ffffff; font-weight:bold;">00</span>:
									<span id="solosecond{..cart_option_seq}" style="color:#ffffff; font-weight:bold;">00</span>
									</span>
								</div>
								<script>
									$(function() {
										timeInterval{..cart_option_seq} = setInterval(function(){
											var time{..cart_option_seq} = showClockTime('text', '{..eventEnd.year}', '{..eventEnd.month}', '{..eventEnd.day}', '{..eventEnd.hour}', '{..eventEnd.min}', '{..eventEnd.second}', 'soloday{..cart_option_seq}', 'solohour{..cart_option_seq}', 'solomin{..cart_option_seq}', 'solosecond{..cart_option_seq}', '{..cart_option_seq}');
											if(time{..cart_option_seq} == 0){
												clearInterval(timeInterval{.cart_option_seq});
												$(".soloEventTd{..cart_option_seq}").html("단독 이벤트 종료");
											}
										},1000);
									});
								</script>
								<!--{ / }-->
							</div>
							<!--{ ? ..option1 }-->
							<div class="goods_option">
								<img src="../images/common/icon_option.gif" />
								{?..title1}{..title1}:{ / }{..option1}
								{ ? ..option2 }{?..title2}{..title2}:{ / }{..option2} { / }
								{ ? ..option3 }{?..title3}{..title3}:{ / }{..option3} { / }
								{ ? ..option4 }{?..title4}{..title4}:{ / }{..option4} { / }
								{ ? ..option5 }{?..title5}{..title5}:{ / }{..option5} { / }
							</div>
							<!--{ / }-->
							<!--{ ? ..cart_inputs }-->
								<!--{ @ ..cart_inputs }-->
									<!--{ ? ...input_value }-->
									<div class="goods_input">
										<img src="../images/common/icon_input.gif" />
										{?...type=='file'}
											{?...input_title}{...input_title}:{ / } <a href="/mypage_process/filedown?file={...input_value}" target="actionFrame"><img src="/mypage_process/filedown?file={...input_value}" width="13" height="13" title="크게 보기" align="absmiddle" /> <span class="desc">{...input_value}</span></a>
										{:}
											{?...input_title}{...input_title}:{ / }{...input_value}
										{/}
									</div>
									<!--{ / }-->
								<!--{ / }-->
							<!--{ / }-->
							<!--{? cfg.order.fblike_ordertype && fblikesale //회원, 비회원체크 }-->
							<div class="fblikelay" style="padding-top:10px">
								{=snsLikeButton(..goods_seq,'button_count')}
							</div>
							<!--{ / }-->
						</td>
					</tr>
					</table>
				</td>
				<!--				<td class="cell" width="140">
					<div id="cart_option_ea_{..cart_option_seq}">{=number_format(..ea)}</div>
					<div><button type="button" class="btn_ea_modify btn_small_whiteb hand" style="border: 1px solid #007E41;" id="{..cart_option_seq}">변경</button></div>
				</td>[[nick]] original 변경 code-->
				<td class="cell" width="140">
				<div class="quick_plus hand" style="width:30px; height:30px; color:#007e41; text-align:center; line-height:25px" onclick="quick_plus_timer({..cart_option_seq},{..goods_seq}, 'subscription');" id="{..cart_option_seq}">
				  &and;
				  <button type="button" class="quick_plus dn" style="width:30px; height:30px;" onclick="quick_plus({..cart_option_seq},{..goods_seq}) ;" id="{..cart_option_seq}"></button>
				</div>

				<div  id="cart_option_ea_{..cart_option_seq}" style ="text-align:center; width:30px; padding-left:3px;" class="goods_seq_subscription_{..goods_seq}">{=number_format(..ea)}</div>

				<div class="quick_minus hand" style="width:30px; height:30px; color:#007e41; line-height:25px; text-align:center;" onclick="quick_minus_timer({..cart_option_seq},{..goods_seq});" id="{..cart_option_seq}">
				  &or;
				  <button type="button" class="quick_minus dn" style="width:30px; height:30px;" onclick="quick_minus({..cart_option_seq},{..goods_seq}) ;" id="{..cart_option_seq}">-</button>
				</div>

				</td>
				<td class="cell right dn"><span class="cart_option_orgprice_{..cart_option_seq}"><p>{=number_format(..price*..ea)}</p></span>원</td>
				<td class="cell center dn">
					<div id="cart_option_sale_total_{..cart_option_seq}">
						<!--{ ? ..sales.total_sale_price > 0 }-->
						{=number_format(..sales.total_sale_price)}원
						<!--{ : }-->
						-
						<!--{ / }-->
					</div>
					<div id="cart_option_sale_detail_{..cart_option_seq}" { ? ..sales.total_sale_price > 0 }{ : }class="hide"{ / }>
						<img src="../images/common/icon_dc_list.gif" alt="할인내역" class="price_area" class="hand" />
						<div class="absolute">
							<div class="sale_price_layer hide" style="width:200px;">
								<div class="title_line">할인내역</div>
								<br style="line-height:10px;" />
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<col width="78" />
								<tr>
									<th>구분</th>
									<th class="bolds ends">할인</th>
								</tr>
								<!--{ @ ..sales.title_list }-->
								<tr id="cart_option_{...key_}_saletr_{..cart_option_seq}" { ? ..sales.sale_list[...key_] > 0 }{ : }class="hide"{ / }>
									<td class="gr">{..sales.title_list[...key_]}</td>
									<td class="bolds ends prices">
										<span id="cart_option_{...key_}_saleprice_{..cart_option_seq}">{=number_format(..sales.sale_list[...key_])}</span>원
									</td>
								</tr>
								<!--{ / }-->
								</table>
							</div>
						</div>
					</div>
				</td>
				<td class="cell right min90" width="120">
					<span id="cart_option_price_id_subscription{..goods_seq}" class="item_prices_subscription cart_option_price_{..cart_option_seq}" style="width:120px;">{=number_format(..sales.result_price)}</span>원
				</td>
				<td class="cell dn">
					<table align="center" border="0"> 
					<col />
					<col width="50" />   
					<tr>
						<td><img src="../images/common/icon_ord_emn.gif" title="적립금" /></td>
						<td class="right"><span id="option_reserve_{..cart_option_seq}">{=number_format(..reserve)}</span>원</td>
					</tr>
					<!--{ ? isplusfreenot && isplusfreenot.ispoint}-->
					<tr>
						<td><img src="../images/common/icon_ord_point.gif" title="포인트" /></td>
						<td class="right"><span id="option_point_{..cart_option_seq}">{=number_format(..point)}</span>P</td>
					</tr>
					<!--{ / }-->
					</table>
				</td>
				<!--{ ?..index_ == 0 }-->
				<td class="cell goods_delivery_info dn" rowspan="{..rowspan}">
					<!--{ ? ..goods_kind == 'coupon' }-->
					쿠폰
					<!--{: ..shipping_policy == 'shop'}-->
					기본배송비
					<!--{ : }-->
					개별배송비
					<div id="goods_each_delivery_{..goods_seq}" class="desc">{=number_format(data_goods[..goods_seq].goods_shipping)}</div>
					<!--{ / }-->
				</td>
				<!--{ / }-->
				<td width="70"> <!--new td by [[so]] 한개씩 삭제 가능하게 -->
					<button type="button" class="hand" style="border-width:0px; background-color:#fff;"  onclick="quick_delete({..cart_option_seq},{..goods_seq},'subscription') ;">x</button> 
				</td>
				<td width="10">&nbsp;</td>
			</tr>
					{ / }
			<!--{ @ ..cart_suboptions }-->
			<tr>
				<td class="cell sub_bg"></td>
				<td class="cell sub_bg">
					<table width="100%" border="0" cellpadding="0" cellspacing="0"> 
					<tr>
						<td class="right" width="80">
							<div style="padding-right:5px;"><img src="../images/common/icon_add_arrow.gif" /></div>
						</td>
						<td class="left"  valign="top">
							<img src="../images/common/icon_add.gif" />
							{ ? ...suboption }
								{ ? ...suboption_title }{...suboption_title}:{ / }{...suboption}
							{ / }
						</td>
					</tr>
					</table>
				</td>
				<td class="cell sub_bg"><div>{=number_format(...ea)}</div></td>
				<td class="cell sub_bg right">{=number_format(...price*...ea)}원</td>
				<td class="cell sub_bg center">
					<div id="cart_suboption_sale_total_{...cart_suboption_seq}">
						<!--{ ? ...sales.total_sale_price > 0 }-->
						{=number_format(...sales.total_sale_price)}원
						<!--{ : }-->
						-
						<!--{ / }-->
					</div>
					<div id="cart_suboption_sale_detail_{...cart_suboption_seq}" { ? ...sales.total_sale_price > 0 }{ : }class="hide"{ / }>
						<img src="../images/common/icon_dc_list.gif" alt="할인내역" class="price_area" class="hand" />
						<div class="absolute">
							<div class="sale_price_layer hide">
								<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#000000" >
								<col width="80" />
								<tr>
									<td class="left pdl5" height="25">
										<strong style="color:#fff;font-size:12px;">할인내역</strong>
									</td>
									<td class="right" height="25"></td>
								</tr>
								<!--{ @ ...sales.sale_list }-->
								<tr id="cart_suboption_{....key_}_saletr_{...cart_suboption_seq}" bgcolor="#ffffff" height="25" { ? ....value_ > 0 }{ : }class="hide"{ / }>
									<td class="left pdl5">
										<img src="../images/common/arrow_right.gif" />
										{...sales.title_list[....key_]}
									</td>
									<td class="right pdr5">
										<span id="cart_suboption_{....key_}_saleprice_{...cart_suboption_seq}">{=number_format(....value_)}</span>원
									</td>
								</tr>
								<!--{ / }-->
								</table>
							</div>
						</div>
					</div>
				</td>
				<td class="cell sub_bg right">
					<span id="cart_suboption_price_{...cart_suboption_seq}">{=number_format(...sales.result_price)}</span>원
				</td>
				<td class="cell sub_bg">
					<table align="center" border="0">
					<col />
					<col width="50" />
					<tr>
						<td><img src="../images/common/icon_ord_emn.gif" title="적립금" /></td>
						<td class="right"><span id="suboption_reserve_{...cart_suboption_seq}">{=number_format(...reserve)}</span>원</td>
					</tr>
					<!--{ ? isplusfreenot && isplusfreenot.ispoint}-->
					<tr>
						<td><img src="../images/common/icon_ord_point.gif" title="포인트" /></td>
						<td class="right"><span id="suboption_point_{...cart_suboption_seq}">{=number_format(...point)}</span>P</td>
					</tr>
					<!--{ / }-->
					</table>
				</td>
			</tr>
			<!--{ / }-->
			<!--{ / }-->
			<!--{ / }-->
			<!--{ : }-->
<!--			<tr>
				<td class="cell" colspan="8"  height="80">장바구니에 담긴 상품이 없습니다.</td>
			</tr>
-->
			<!--{ / }-->

			</tbody>
			
			</table>
			</form>

			

			<div class="cboth"></div>
			<div style="height:10px" class="dn"></div>
			<div align="center" id="facebook_mgs">{? APP_LIKE_TYPE == 'API' }페이스북과 정보를 교환 중에 있습니다. 잠시만 기다려 주세요.{/}</div>
			<div style="height:10px" class="dn"></div>

			<table align="right" height="80" border="0" cellpadding="0" cellspacing="0" class="dn">
				<tr>
					<td>
						<table width="80" border="0" cellpadding="0" cellspacing="0">
						<tr><td align="center" height="30">총 수량</td></tr>
						<tr><td align="center" height="25" class="fx16">{=number_format(total_ea)}</td></tr>
						</table>
					</td>
					<td>
						<table width="120" border="0" cellpadding="0" cellspacing="0">
						<tr><td align="center" height="30">총 판매가격</td></tr>
						<tr><td align="center" height="25" class="fx16">{=number_format(total)}</td></tr>
						</table>
					</td>

					<td class="goods_delivery_info">
						<table width="120" border="0" cellpadding="0" cellspacing="0">
						<tr><td align="center" height="30">배송비</td></tr>
						<tr><td align="center" height="25" class="fx16"><span id="total_shipping_price">{=number_format(array_sum(shipping_price))}</span></td></tr>
						</table>
					</td>
					<td>
						<table width="120" border="0" cellpadding="0" cellspacing="0">
						<tr><td align="center" height="30">총 할인</td></tr>
						<tr><td align="center" height="25" class="fx16"><span id="total_sale">{=number_format(total_sale)}</span></td></tr>
						</table>
					</td>
					<td>
						<table width="120" border="0" cellpadding="0" cellspacing="0">
						<tr><td align="center" height="30">총 결제금액</td></tr>
						<tr><td align="center" height="25" class="fx16"><span class="settle_price bold">{=number_format(total_price)}</span></td></tr>
						</table>
					</td>
						<td>
							<table width="120" border="0" cellpadding="0" cellspacing="0">
							<tr><td align="center" height="30">예상적립금</td></tr>
							<tr><td align="center" height="25" class="fx16"><span class="total_reserve" id="total_reserve">{=number_format(total_reserve)}</span></td></tr>
							</table>
						</td>

						<!--{ ? isplusfreenot && isplusfreenot.ispoint}-->
						<td>
							<table width="120" border="0" cellpadding="0" cellspacing="0">
							<tr><td align="center" height="30">예상포인트</td></tr>
							<tr><td align="center" height="25" class="fx16"><span class="total_point" id="total_point">{=number_format(total_point)}</span></td></tr>
							</table>
						</td>
						<!--{ / }-->
				</tr>
			</table>

		<div id="summary2" style="position:fixed; top:465px; background-color:rgba(66, 66, 66, 0.57); z-index:9999 !important"> 
			<table width="100%" border="0" cellpadding="0" cellspacing="0" style="position:fixed; background-color:rgba(66, 66, 66, 0.57); color:#fff; padding-bottom:30px; padding-bottom:5px; padding-right:15px;">
			<tr class="domestic">
				<td height="34" align="right" style="padding:0 0px;" > 
					<div class="goods_delivery_info" id="goods_delivery_price_subscription">
						기본배송비: {=number_format(shipping_price.shop)}원
						<!--{ ? shipping_price.goods > 0 }-->
						<p style="padding-left:20px;">개별배송비 : {=number_format(shipping_price.goods)}원</p>
						<!--{ / }-->
					</div>
				</td>
			</tr>
			
	        <tr class="domestic">
				<td height="24" align="right" style="padding:0 0px;" > 
						<div style="display:block !important" class="goods_delivery_info" id="goods_delivery_total_subscription" >
							주 결제금액: {=number_format(total_price)}원
	                    </div>
	                </td>
	            </tr>
			 <!--by [[so]] ; cycle == 4 weeks -->
	<!--            <tr class="domestic">-->
	<!--				<td height="28" align="right" >-->
	<!--					<div class="goods_delivery_info" id="subscription_per_cycle">-->
	<!--					x 4 = _-->
	<!--					</div>-->
	<!--					-->
	<!--				</td>-->
	<!--			</tr>-->
			</table>        

			<div style="width:100%; position:fixed; z-index:9999 !important">
				<span id="subscription_pay"  style="position:fixed; top:524px;">   
				
					<button type="submit" class="green_noborder hand" style="width:440px; border-radius:0px; " value="전체상품 주문하기" onclick="mixpanel_order();">결제하기 (정기결제)
					</button>

				</span>
				<span class="btn_selected_order dn">
					<button type="submit" class="btn_whitebackground hand" value="선택상품 주문하기">선택상품 주문하기</button>
				</span>
			</div>

		</div>

			{navercheckout_tpl}

		<!-- 본문내용 끝 -->
	</td>
	<td width="40"></td>
</tr>
</table>
</div>
		
	</div>
	<!-- tab2 end -->
</div>


<div id="optional_changes_dialog" style="display:none;"></div>
<!--{? APP_LIKE_TYPE == 'API' }-->
	<!-- 좋아요할인 : 삭제하지 말아주세요 -->
	<!--{ ? !( strstr(_SERVER.HTTP_HOST,'.firstmall.kr') || _SERVER.HTTP_HOST==APP_DOMAIN ) }-->
	<iframe name="snsiframe" src="http://{config_system.subDomain}/admin/sns/subdomainfacebookck" frameborder="0" width="0" height="0"></iframe>
	<script language="JavaScript" src="http://{config_system.subDomain}/order/fblike_opengraph_firstmallplus?firstmallcartid={firstmallcartid}"></script>
	<script language="JavaScript" src="http://{config_system.subDomain}/order/fbopengraph?firstmallcartid={firstmallcartid}"></script>
	<!--{ / }-->
	<script language="JavaScript" src="../order/fblike_opengraph"></script>
	<!-- 좋아요할인 : 삭제하지 말아주세요 -->
<!--{ / }-->

<div id="fade"></div>
<div id="modal">
    <img id="loader" src="../images/box.gif" alt="신선함을 로딩중"/> 
</div>

<!-- //ip 주소 가져오기 -->
<script type="text/javascript">
  var userip="0000";
</script>      
<!--<script type="text/javascript" src="https://l2.io/ip.js?var=userip"></script>
-->

<script type="text/javascript">

	$('.ui-draggable').first().ready(function(){
		$(".optionTitle").first().attr("style", "color:#ffffff"); 
	});
	var count = 0;
	var counting = false;
	var timer;
	$(document).ready(function() {
		console.log("cart loaded");

		// 전체 선택
		$("form#cart_form .btn_select_all").toggle(function() {
			$("input[name='cart_option_seq[]']").attr("checked",true);
		}, function() {
			$("input[name='cart_option_seq[]']").attr("checked",false);
		});

		// 선택삭제 -- 모바일 차용 by [[so]] 
		/*
		$(".btn_select_del").click(function() {

			var selected_order = $(this).val();  
			$("input[name='cart_option_seq[]']").attr("checked",false);
			$("input[name='cart_option_seq[]'][value='"+selected_order+"']").attr("checked", true);

			$("form#cart_form").attr("action","del");
			$("form#cart_form").attr("target","actionFrame");
			$("form#cart_form")[0].submit();
		});  

		*/

		/*
		$(".btn_select_del").click(function() {
			var selected_order = '';
			$("input[name='cart_option_seq[]']").each(function(e, el) {
				if( $(el).attr('checked') == 'checked' ){
					selected_order += $(el).val() + ",";
				}
			});
			if(!selected_order){
				openDialogAlert('삭제할 상품을 선택해 주세요.','400','140');
				return false;
			}
			$("form#cart_form").attr("action","del");
			$("form#cart_form").attr("target","actionFrame");
			$("form#cart_form")[0].submit();
		});

		*/

		// 수량변경
		$("button.btn_ea_modify").bind("click",function() {
			closeDialog("optional_changes_dialog");//diglog 초기화
			var url = "optional_changes?option_catalog=1&no="+$(this).attr("id");
			$.get(url, function(data) {
				$("div#optional_changes_dialog").html(data);
			});
			openDialog("수량 조절하기", "optional_changes_dialog", {"width":450,"height":350}); //[[so]] [[wip]]work in progress
		});

		// 선택 주문
		$(".btn_selected_order").bind("click",function() {
			var selected_order = '';
			$("input[name='cart_option_seq[]']").each(function(e, el) {
				if( $(el).attr('checked') == 'checked' ){
					selected_order += $(el).val() + ",";
				}
			});

			if(!selected_order){
				openDialogAlert('주문할 상품을 선택해 주세요.','400','140');
				return false;
			}

			$("form#cart_form").attr("action","addsettle?mode=choice");
			$("form#cart_form").attr("target","");
			$("form#cart_form")[0].submit();
			$("form#cart_form").attr("target","actionFrame");
		});

		// 전체 주문 (일반 카트)
		$(".btn_all_order").bind("click",function() {
		
			$("input[name='cart_option_seq[]']").each(function(e, el) {
				if ( $(el).attr('subscription') == '0'  ) {
					 $(el).attr('checked', 'checked');
				}
			});
		
			$("form#cart_form").attr("action","addsettle?mode=choice&subscription=0");
			$("form#cart_form").attr("target","");
			$("form#cart_form")[0].submit();
			$("form#cart_form").attr("target","actionFrame"); 
		});

		// 전체 주문 (정기구매 카트)
		$("#subscription_pay").bind("click",function() {
		
			$("input[name='cart_option_seq[]']").each(function(e, el) {
				if ( $(el).attr('subscription') == '1'  ) { 
					 $(el).attr('checked', 'checked');
					//console.log('subscription or not: ');
					//console.log($(el).attr('subscription'));
				}
			});
		
			$("form#subscription_cart_form").attr("action","addsettle?mode=choice&subscription=1");
			$("form#subscription_cart_form").attr("target","");
			$("form#subscription_cart_form")[0].submit();
			$("form#subscription_cart_form").attr("target","actionFrame");
		});

		// 선택 위시리스트 저장
		$("button.btn_select_wishlist").bind("click",function(){
			$("form#cart_form").attr("action","../mypage/wish_add?mode=cart");
			$("form#cart_form").attr("target","actionFrame");
			$("form#cart_form")[0].submit();
		}); 

		$(".price_area").bind("mouseover",function(){
			$(this).parent().find(".sale_price_layer").show();
		}).bind("mouseout",function(){
			$(this).parent().find(".sale_price_layer").hide();
		});
		
		updateTotalPrice('same', 'regular');
		parent.loaded_notify();
		update_goods_prices(); // [[so]]
		console.log("cart finished loading");
//		openModal();
//		setTimeout(closeModal,1000);
		/*
		$('.goods_price').each(function() {
			console.log('for loop running');
			var oldprice = $(this).innerHTML(); 
			oldprice = toLocaleStringNew(oldprice);
			$(this).text(""+oldprice);
		});
		
		*/ 
  
	//	order_price_calculate();


	// [[bo]] cart tabview	
    $('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = $(this).attr('href');
 
 		console.log(currentAttrValue);

 		var tab_num = currentAttrValue.substring(4,5);
 		console.log(tab_num);
  
 		if(tab_num == '1'){
 			$('#tab1').show();
 			$('#tab2').hide();
 			$('#tab_li_1').addClass('tab_onclick');
 			$('#tab_li_1').removeClass('tab_unclick');
 			$('#tab_li_2').addClass('tab_unclick');
 			$('#tab_li_2').removeClass('tab_onclick');
 		}else if(tab_num == '2'){
			$('#tab2').show();
 			$('#tab1').hide();
 			$('#tab_li_2').addClass('tab_onclick');
 			$('#tab_li_2').removeClass('tab_unclick');
 			$('#tab_li_1').addClass('tab_unclick');
 			$('#tab_li_1').removeClass('tab_onclick');
 		}

        // // Show/Hide Tabs
        // $('.tabs ' + currentAttrValue).show().siblings().hide();
 
        // // Change/remove current tab to active
        // $(this).parent('li').addClass('active').siblings().removeClass('active');
 
    });

  

	});

	//[[so]]
	function update_goods_prices() {
		var goods_prices_list = $('.goods_price');
		var goods_prices_list_subscription = $('.goods_price_subscription');
		//console.log('update_goods_prices() : ');
		//console.log(goods_prices_list);
		for (i=0; i<goods_prices_list.length; i++) {
			var oldprice = parseInt(goods_prices_list[i].innerHTML);
			var goods_seq = (goods_prices_list[i].id).substring(14);
			oldprice = toLocaleStringNew(oldprice);
			//console.log(oldprice+" "+goods_seq);
			$('#item_price_id_'+goods_seq).text(""+oldprice);
		}
		//console.log(goods_prices_list_subscription);
		for (i=0; i<goods_prices_list_subscription.length; i++) {
			var oldprice = parseInt(goods_prices_list_subscription[i].innerHTML);
			var goods_seq = (goods_prices_list_subscription[i].id).substring(27);
			oldprice = toLocaleStringNew(oldprice);
			$('#item_price_id_subscription_'+goods_seq).text(""+oldprice); // [[so]]
		}
	
	}	//[[so]]

	// facebook 라이크 할인 적용 및 오픈그라피
	function getfblikeopengraph(){
		$.get('../order/fblike_opengraph', function(data) {
			$("#facebook_mgs").html("");
		});
	}

	function order_price_calculate(){
		var f = $("form#orderFrm");
		f.attr("action","calculate?mode=cart");
		f.attr("target","actionFrame");
		f[0].submit();
	}
	function alsocalled(){
		console.log("iamalsocalled");
	}
 	//[[bo]] cart에서 최고수량이 됬을 때
	function max_goods_black(goods_seq){
		var classname = "up_count_"+goods_seq;					
		$('.'+classname).attr('style','width:30px; height:30px; text-align:center; line-height:25px; color:#eeeeee');	
	}
	 //[[bo]] cart에서 최고수량이 풀렸을 때
	function max_goods_green(goods_seq){
		var classname = "up_count_"+goods_seq;					
		$('.'+classname).attr('style','width:30px; height:30px; text-align:center; line-height:25px; color:#007e41');	
	}

	// *********** [[so]] *********** 
	function quick_delete(cart_option_seq, goods_seq, flag) { 
			//console.log(cart_option_seq);
			//$('#pinnn').html(cart_option_seq);
			//post to order/del
			openModal();
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			 } else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			 }
			var url = "../order/del";
			xmlhttp.open("POST", url, true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");//Fixed
			xmlhttp.onreadystatechange=function(){
               if (xmlhttp.readyState==4) {
                   if(xmlhttp.status==200){
                       //console.log(xmlhttp.responseText);
                         //console.log("im here");
                         //$('#pinnn').attr('style', 'display:none'); 
                        //var placer=0;
						var subscription = false;
                        if ( $('.cart_option_price_'+cart_option_seq).hasClass("item_prices_regular") ) {
                        	subscription = false;
                        	console.log('subscription == false');
                        } else if ( $('.cart_option_price_'+cart_option_seq).hasClass("item_prices_subscription") ){
                        	subscription = true;
                        	console.log('subscription == true');
                        }
                        if (flag=='subscription') {
                        	subscription = true;
                        }
						
						setTimeout(closeModal,500);
 						$('#'+cart_option_seq+'').attr('style', 'display:none');
						
						var cntprev;
						var cntprev_s;
						if (subscription) {
							cntprev = $('#subscription_count').text();
							cntprev = cntprev * 1;
							cntprev = cntprev - 1 ;
							//$('#subscription_count').text(cntprev);
						} else {
							cntprev_s = $('#regular_count').text();
							cntprev_s = cntprev_s * 1;
							cntprev_s = cntprev_s - 1 ;
							//$('#regular_count').text(cntprev_s);
						}
						
						//console.log('quick_delete, subscription count now : ' + cntprev);
						//console.log('quick_delete, regular count now : ' + cntprev_s);
						
						parent.quick_delete_cart(goods_seq);
						$('.cart_option_price_'+cart_option_seq).text("0");
						
						if (subscription){
							updateTotalPrice('minus','subscription');
						} else {
							updateTotalPrice('minus','regular');
						}
						
						update_cart_badge(); //[[so]] 
					} else {
                         //console.log("im not here");
						$('#pinnn').html('claire de lune');
						closeModal();
					}
               } else {
               }
           };
           xmlhttp.send("cart_option_seq[]="+cart_option_seq); //cart_version=2&
   }
   function quick_plus_timer(cart_option_seq, goods_seq, flag){
    		console.log("quick_remove_timer: "+count);
			   
			//[[nick]] max_cnt handling
		    var ea = parseInt($('#cart_option_ea_'+cart_option_seq).text());
			var max_cnt = document.getElementById("max_purchase_"+goods_seq).innerHTML;
		
			if (max_cnt > 0) {
				if (ea >= max_cnt) {
					var classname = "up_count_"+goods_seq;					
					$('.'+classname).attr('style','width:30px; height:30px; text-align:center; line-height:25px; color:#eeeeee');	
					parent.max_goods_black_hover(goods_seq);				
					return;
				}
			}
			//[[nick]]
			
			var subs = $(this).parent().parent().hasClass("tr_subscription");
			console.log('cart.html/quick_plus_timer, subscription? : '+ flag);
		    if (flag=='subscription'){
				parent.plus_one(goods_seq, "subscription");
			} else { // regular
				parent.plus_one(goods_seq, "regular");
			}

//			parent.plus_one(goods_seq);
			var previous = parseInt($('#cart_option_ea_'+cart_option_seq).text())+1;
			$('#cart_option_ea_'+cart_option_seq).text(previous);
			itemUpdatePrice(cart_option_seq);
			
			if (counting) {
			    clearTimeout(timer);
			    count++;
			    timer = setTimeout( function(){
					q_plus(cart_option_seq, goods_seq, previous);
				}, 500);			        
			} else {
			    counting = true;
			    count++;
			    timer = setTimeout( function(){
					q_plus(cart_option_seq, goods_seq, previous);
				}, 500);			        
			}			
   }
   function quick_minus_timer(cart_option_seq, goods_seq, flag){
    		console.log("quick_remove_timer: "+count);
		    var ea = parseInt($('#cart_option_ea_'+cart_option_seq).text());
			var max_cnt = document.getElementById("max_purchase_"+goods_seq).innerHTML;

			console.log('ea : ' + ea + 'max : ' + max_cnt);
			if(ea <= max_cnt){
				var classname = "up_count_"+goods_seq;					
					$('.'+classname).attr('style','width:30px; height:30px; text-align:center; line-height:25px; color:#007e41');			
					parent.max_goods_green_hover(goods_seq);	
			}

			if (ea>1) {
				
				var subscription = false;
				if (flag=='subscription') {
					subscription = true;
				}
				//subscription = $(this).parent().parent().hasClass("tr_subscription"); 
				if (subscription) {
					parent.minus_one(goods_seq, "subscription");
				} else {
					parent.minus_one(goods_seq, "regular");
				}
				
//				parent.minus_one(goods_seq);
				var newea = ea-1;
				$('#cart_option_ea_'+cart_option_seq).text(newea);
				itemUpdatePrice(cart_option_seq);
				
				if (counting) {
					clearTimeout(timer);
					count++;
					timer = setTimeout( function(){
						q_minus(cart_option_seq, goods_seq, newea);
					}, 500);			        
				} else {
					counting = true;
					count++;
					timer = setTimeout( function(){
						q_minus(cart_option_seq, goods_seq, newea);
					}, 500);			        
				}	

			}
			
   }
	function quick_plus(cart_option_seq, goods_seq){
		q_plus(cart_option_seq, goods_seq);
	}
	function quick_minus(cart_option_seq, goods_seq){	
		var previous = parseInt($('#cart_option_ea_'+cart_option_seq).text());
		if (previous != 1) {
			q_minus(cart_option_seq, goods_seq);
		}
	}
	function q_plus_parent(good_seq, flag){
		if (flag=="subscription") {
			var ea = parseInt($('.goods_seq_subscription_'+good_seq).text());
			$('.goods_seq_subscription_'+good_seq).text(ea+1);
		} else if (flag=="regular"){
			var ea = parseInt($('.goods_seq_'+good_seq).text());
			$('.goods_seq_'+good_seq).text(ea+1);
		}
		
		itemUpdatePriceGoods(good_seq);
	}
	function q_minus_parent(good_seq, flag){	
		if (flag=="subscription") {	
			var ea = parseInt($('.goods_seq_subscription_'+good_seq).text());
			$('.goods_seq_subscription_'+good_seq).text(ea-1);
		} else if (flag=="regular"){
			var ea = parseInt($('.goods_seq_'+good_seq).text());
			$('.goods_seq_'+good_seq).text(ea-1);
		}
		itemUpdatePriceGoods(good_seq);
	}
	function quick_delete_parent(cart_option_seq,flag){ // flag only for the purpose of regular_count and subscription_count [[so]]
		console.log('quick_delete_parent called');
		
 		$('#'+cart_option_seq+'').attr('style', 'display:none');		
		$('.cart_option_price_'+cart_option_seq).text("0");
		if (flag=='subscription'){
			updateTotalPrice('minus','subscription');
		} else {
			updateTotalPrice('minus','regular');
		}
		 // already 'minus'ed .. wow such bad coding [[so]]
		//updateItemCount(); // [[so]]
	}

	function q_plus(cart_option_seq, good_seq, ea) {
		console.log(good_seq);
		count = 0;
		counting = false;
	    if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		 }     
		 var url = "../order/quick_modify";
		 xmlhttp.open("POST", url, true);
		 xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");//Fixed
		 xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4) {
				if(xmlhttp.status==200){
					console.log("success");	
				 } else {
					console.log("fail");						
				 }
			} else {
					console.log("fail");						
			}
		};
		xmlhttp.send("cart_option_seq="+cart_option_seq+"&ea="+ea); //cart_version=2&
	}
	
	function q_minus(cart_option_seq, good_seq, ea) {
		console.log(good_seq);
		count = 0;
		counting = false;
		
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		 } else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		 }     
		 var url = "../order/quick_modify";
		 xmlhttp.open("POST", url, true);
		 xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");//Fixed
		 xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4) {
				if(xmlhttp.status==200){
					console.log("success");	
				 } else {
					console.log("fail");						
				 }
			} else {
					console.log("fail");						
			}
		};
		xmlhttp.send("cart_option_seq="+cart_option_seq+"&ea="+ea); //cart_version=2&

    }
   
   
   
   	function update_cart_list() { 
   		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
		}     
		var url = "../order/getlist_ver2";
		
		xmlhttp.open("POST", url, true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");//Fixed
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4) {
				if(xmlhttp.status==200){
					//console.log(xmlhttp.responseText);
					items = JSON.parse(xmlhttp.responseText);
					console.log(items);
					
					var table = $('#cart_list_regular');
					for(i=0; i<items.length; i++){
							//$('item_cart, .'+items[i][0]).text(items[i][1]);
							//$('count, .'+items[i][0]).text(items[i][1]);
							//$(items[i][0]+', .add-cart-large').text("+");
							//$(items[i][0]+', .remove-cart-large').text("-");
							
							
					}
					
				}
			} else {
//						$('add-cart-large, .'+good_seq).prop("disabled", false);						
			}
		};
		xmlhttp.send("display_lattice=1");
			
   	}
	function itemUpdatePrice(cart_option_seq){
		//update price of cart_option_seq with new count		
		var ea = parseInt($('#cart_option_ea_'+cart_option_seq).text());
		var price = parseInt($('.item_price_'+cart_option_seq).text().replace(/\,/g,''));
		var newprice = ea*price;
		$('.cart_option_price_'+cart_option_seq).text(""+toLocaleStringNew(newprice));
		updateTotalPrice('same','regular');
	}
	function itemUpdatePriceGoods(goods_seq){
		var ea_regular = parseInt($('.goods_seq_'+goods_seq).text());
		var price_regular = parseInt($('#item_price_id_'+goods_seq).text().replace(/\,/g,''));
		var newprice_regular = ea_regular*price_regular;
		$('#cart_option_price_id_'+goods_seq).text(""+toLocaleStringNew(newprice_regular));		

		var ea_subscription = parseInt($('.goods_seq_subscription_'+goods_seq).text());
		var price_subscription = parseInt($('#item_price_id_subscription_'+goods_seq).text().replace(/\,/g,''));
		var newprice_subscription = ea_subscription*price_subscription;
		$('#cart_option_price_id_subscription'+goods_seq).text(""+toLocaleStringNew(newprice_subscription));		
		updateTotalPrice('same','regular');
		//updateItemCount(); // [[so]]

		//itemUpdatePriceGoods
	}


	function updateTotalPrice(count_change,flag){
		console.log('updateTotalPrice called');
		//get all prices & add them up
		var list_regular = $('.item_prices_regular');
		var list_subscription = $('.item_prices_subscription');
		var tot_regular = 0;
		var tot_subscription = 0;

		for(i=0; i<list_regular.length; i++){
			count_regular += 1;
			var cost = list_regular[i].innerHTML;
			tot_regular += parseInt(cost.replace(/,/g, ''), 10);
		}
		for(i=0; i<list_subscription.length; i++){
			count_subscription += 1;
			var cost = list_subscription[i].innerHTML; 
			tot_subscription += parseInt(cost.replace(/,/g, ''), 10); 
		}
		if (tot_regular>=40000) { //[[nick]] 배송비 0원 이벤트 [[so]] back to 40,000 이상 무료배
			$('#goods_delivery_price_regular').text("기본배송비: 0원");
			$('#goods_delivery_total_regular').text("총 결제금액: "+toLocaleStringNew(tot_regular)+"원");
		} else if(tot_regular==0){
			//console.log("no items");
			$('#goods_delivery_price_regular').text("");
			$('#goods_delivery_total_regular').text("장바구니에 담긴 상품이 없습니다.");
		} else {
			$('#goods_delivery_price_regular').text("기본배송비: 3,000원");
			tot_regular+=3000;
			$('#goods_delivery_total_regular').text("총 결제금액: "+toLocaleStringNew(tot_regular)+"원");			
		}

		if (tot_subscription>=40000) { // [[so]] subscription 
			$('#goods_delivery_price_subscription').text("기본배송비: 0원");
			$('#goods_delivery_total_subscription').text("주 결제금액: "+toLocaleStringNew(tot_subscription)+"원");
		} else if(tot_subscription==0){
			$('#goods_delivery_price_subscription').text("");
			$('#goods_delivery_total_subscription').text("장바구니에 담긴 상품이 없습니다.");				
		} else {
			$('#goods_delivery_price_subscription').text("기본배송비: 3,000원");
			tot_subscription+=3000;
			$('#goods_delivery_total_subscription').text("주 결제금액: "+toLocaleStringNew(tot_subscription)+"원");			
		}

		//var subscription_per_cycle = tot_subscription*4;  // [[so]] 2016/03/31
		//var txt = "최종 결제금액: " + toLocaleStringNew(tot_subscription) + "원 x 4주 = " + toLocaleStringNew(subscription_per_cycle)+"원"; // [[so]] 2016/03/31
		//$('#subscription_per_cycle').text(txt); // [[so]]


		//$('#regular_count').text(count_regular);
		//$('#subscription_count').text(count_subscription);


		var count_regular = $('#regular_count').text() * 1; // [[so]]
		console.log('regular count :' + count_regular);
		var count_subscription = $('#subscription_count').text() * 1;
		console.log('subscription count :' + count_subscription);

		console.log('count_change = '+count_change);
		//$('#regular_count').text("42");
		//updateItemCount(); // [[so]] 묻어가기.. ㅎㅎ

		/*if (count_change=='plus'){
			if (flag=='subscription'){
				$('#subscription_count').text(count_subscription+1);
			} else {
				$('#regular_count').text(count_regular+1);
			}
		} else */
		if (count_change=='minus'){

			$('#regular_count').text("3");
			if (flag=='subscription'){
				$('#subscription_count').text(count_subscription-1);
			} else {
				var ttt = count_regular-1;
				console.log('heyyyy '+ttt);
				$('#regular_count').text(""+ttt);
			}
		}
	}
    function openModal() {
		console.log("modal open");
        document.getElementById('modal').style.display = 'block';
        document.getElementById('fade').style.display = 'block';
    }

    function closeModal() {
		console.log("modal close");
        document.getElementById('modal').style.display = 'none';
        document.getElementById('fade').style.display = 'none';
    }
	function closeModalTimeOut(){
		setTimeout(closeModal,50);		
	}
	function toLocaleStringNew(x, sep, grp) {
		var sx = (''+x).split('.'), s = '', i, j;
		sep || (sep = ','); // default seperator
		grp || grp === 0 || (grp = 3); // default grouping
		i = sx[0].length;
		while (i > grp) {
			j = i - grp;
			s = sep + sx[0].slice(j, i) + s;
			i = j;
		}
		s = sx[0].slice(0, i) + s;
		sx[0] = s;
		return sx.join('.')
	}
  
	function mixpanel_order(){


		  var userip_str = '' + userip;
		  var epicerie_ip = '58.142.96.133';
		  var member_seq = "{userInfo.member_seq}";

		  console.log(member_seq);

		  if(userip_str == epicerie_ip || userip_str == '122.35.173.159' || member_seq == '52' || member_seq == '3' || member_seq == '104' || member_seq == '2821'){
		    console.log('bobo');        


		  }else {
		    console.log('notbo');

			// start mixpanel
			var user_name = "{userInfo.user_name}";
			console.log(user_name);

		    mixpanel.track("Order", {
		          "pc/mobile": "pc",
		          "user_name" : user_name,
		          "order_step" : "cart"
			 });
		}
	}
	$('#subscription_page').click(function(){ // [[so]] 더 알아보기 누르면 goods/subscription 페이지로 이동 
			window.top.location.href = '../goods/subscription';
	});
	// *********** [[so]] *********** 



</script>

