<style>
#editIcon{
	float:right;
}
#editIcon:hover{
	color:red;
	cursor:pointer;
}
</style>

<h1>Configuration de l'hôte virtuel {{ virtualHost.getName() }} sur {{ server.getName() }}</h1>

<div class="ui top attached tabular menu">
  <a class="item active" data-tab="first"><i class="dashboard icon"></i>Récapitulatif</a>
  <a class="item" data-tab="second"><i class="settings icon"></i>Configuration</a>
</div>
<div class="ui bottom attached tab segment active" data-tab="first">
{{ title1 }}
{{ q["infos"] }}

<br />
{{ title2 }}
  <pre>
  {{ q["editIcon"] }}
  	<code class="language-apacheconf">
  		 {{ virtualHost.getConfig() }}
  	</code>  
  </pre>
</div>
<div class="ui bottom attached tab segment" data-tab="second">
  {{ q["modifier"] }}
  {{ q["importOrExport"] }}
  {{ q["generate"] }}

  <div id="uploadExport"></div>
  <div id="modification"></div>
  <pre>
  	<code class="language-apacheconf">
  		 {{ virtualHost.getConfig() }}
  	</code>
  
  </pre>
 
</div>
<!-- 
<div class="ui bottom attached tab segment" data-tab="third">
	{{ test }}
</div>-->



<script>
$('.menu .item')
.tab()
;
</script>
{{ script_foot }}