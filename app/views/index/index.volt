{% if session.get('user') == null %}
<div class="page-header">
    <h1>UniHost - Connexion</h1>
</div>
<p>Rejoignez les licornes !</p>
<p>Cliquez sur le bouton ci-dessous afin de vous connecter à l'application.</p>
<div id="file"></div><br />
{{ q["btTmp"] }}
{% else %}
    <meta http-equiv="refresh" content="0; url=Tmp/index" />
{% endif %}
