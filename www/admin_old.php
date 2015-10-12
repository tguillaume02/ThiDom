<div data-role="page" class="type-home conteneur" id="div_admin" data-theme="a">
	<div data-role="header" id="header" data-id="header_gen" class="ui-fixed-hidden" data-position="fixed" data-theme="b">
		<div>
			<table id="table_header" style="width:100%">
				<tr>
					<td>
						<a href="#" id="btn_back" class="ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-left ui-corner-all ui-btn-b" onclick="window.history.back();LoadMaison();" data-icon="custom" >Back</a>
					</td>
					<td>
						<span>Administration</span>
					</td>
				</tr>
			</table>
			<!-- NavBar-->
			<div data-role="navbar" class="ui-bar-b" id="navbar" data-grid="c">
				<ul>
					<li><a href="#Add_app" onclick="$('.admin').hide();$('.Add_app').show();" style="white-space: normal !important;">Ajouter un appareil</a></li>
					<li><a href="#delete_app" onclick="$('.admin').hide();$('.delete_app').show();" style="white-space: normal !important;">Supprimer un appareil</a></li>
					<li><a href="#Add_piece" onclick="$('.admin').hide();$('.Add_piece').show();"  style="white-space: normal !important;">Ajouter une piéce </a></li>
					<li><a href="#delete_piece" onclick="$('.admin').hide();$('.delete_piece').show();" style="white-space: normal !important;" >Supprimer une piéce </a></li>
				</ul>
			</div>
		</div>
	</div>
	<br>
	<div data-role="content" id="Add_app" class="Add_app admin">	
					<div class="content-secondary"> 
						<div id="a" class="roundedImage inversePair" style="background:url(http://static.debray-jerome.fr/48/images/slideshow/1.jpg) no-repeat 0px 0px;display: inline-block;"></div>
						<div id="b" class="inversePair">Ajouter equipement</div>
					</div>
					
					<div class="content-primary"> 
						<div id="a" class="roundedImage inversePair" style="background:url(http://static.debray-jerome.fr/48/images/slideshow/1.jpg) no-repeat 0px 0px;display: inline-block;"></div>
						<div id="b" class="inversePair">Supprimer un equipement</div>
					</div>
					
					<div class="content-primary"> 
						<div id="a" class="roundedImage inversePair" style="background:url(http://static.debray-jerome.fr/48/images/slideshow/1.jpg) no-repeat 0px 0px;display: inline-block;"></div>
						<div id="b" class="inversePair">ajouter une piéce </div>
					</div>
					
					<div class="content-secondary"> 
						<div id="a" class="roundedImage inversePair" style="background:url(http://static.debray-jerome.fr/48/images/slideshow/1.jpg) no-repeat 0px 0px;display: inline-block;"></div>
						<div id="b" class="inversePair">supprimer une piéce </div>
					</div>
					
					<div class="content-primary"> 
						<div id="a" class="roundedImage inversePair" style="background:url(http://static.debray-jerome.fr/48/images/slideshow/1.jpg) no-repeat 0px 0px;display: inline-block;"></div>
						<div id="b" class="inversePair">supprimer une piéce </div>
					</div>
				
	</div>
		
</div>

<style>
.inversePair {
    /*border: 1px solid black;*/
	border : none;
    display: inline-block;    
    position: relative;    
    height: 100px;
    text-align: center;
    line-height: 100px;
    vertical-align: middle;
	font-size: 13.5pt;
	font-family: sofiaregular, sans-serif;
	color:white;
}

.circle {
    width: 100px;
    border-radius: 50px;
    background: grey;
    z-index: 1;
}

.circle_name {
    width: 200px;
    font-size:15pt;
    text-align: left;
    /* need to play with margin/padding adjustment
       based on your desired "gap" */
    padding-left: 30px;
    margin-left: -30px;
    /* real borders */
    border: none;
}

</style>