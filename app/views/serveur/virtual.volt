<div id="divAction">

{% if  q["table4"] is defined %}
<div class="ui positive message">
{{ title1 }}
</div>
{{ q["table4"] }}
  {{ q["ajoutervirtual"]}}  
 <br/> <br/> 
  {{ q["frmDelete"]}}

{% else %}

<div class="ui positive message">
<h3> Il n'existe pas de virtualhost actuellement pour le serveur séléctionné.  </h3>
  {{ q["ajoutervirtual"]}}
  
  <br/>
  
  </div>
{% endif %}




 
</div>


{{ script_foot }}
