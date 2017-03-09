<h2 style="padding-top:10px">Propriétés</h2>

   <div class="ui message info" style="display:none;" id="info"></div>


<form id="frmConfig">
{% if q["div-idvh"] is defined %}
    {{ q["div-idvh"] }}
{% endif %}
{{ q["s-infos"] }}

</form>

{% if differences is not null %}
<form id="frmConfig2">
<br />
<h2>Proprietés non attribuées</h2>
{{ q["s-infos2"] }}
</form>
{% endif %}
<br />

{{ q["divDelete"] }}
{{ javascript_include("js/jquery.tablesort.min.js") }}
{{ script_foot }}