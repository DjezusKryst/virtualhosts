<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>UniHost</title>
{{stylesheet_link("https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.min.css")}}
{{stylesheet_link("https://cdnjs.cloudflare.com/ajax/libs/prism/1.5.1/themes/prism-okaidia.min.css")}}
{{stylesheet_link("public/css/styles.css")}}
{{javascript_include("https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js")}}
{{stylesheet_link("public/css/bootstrap.min.css")}}
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" style="color:red;"href="/virtualhosts/">UNIHOST</a>
    </div>
     <div class="collapse navbar-collapse" id="myNavbar">
    <ul class="nav navbar-nav">

      {{ q["secondary"] }}
    </ul>
             <ul class="nav navbar-nav navbar-right">
                 {% if session.get('user') == null %}
                     <li><a href="/virtualhosts/"><span class="fa fa-user-circle"></span>Connexion</a></li>
                 {% else %}
                     <li><a href="/virtualhosts/Sign/SignOut"><span class="fa fa-user-circle"></span> Déconnexion </a></li>
                 {% endif %}
             </ul>


  </div>
</nav>

	<a href="../cloud/">
	<div class="bs-docs-header" style="margin-top:50px;">
		<div class="container">
			<div class="header">
			<h1><font color="#990099">U</font><font color="#a80783">n</font><font color="#b60f6d">i</font><font color="#c51657">H</font><font color="#d31d42">o</font><font color="#e2242c">s</font><font color="#f02c16">t</font></h1></a>
				<p>One of da best virtualhosts services for Unicorns.</p>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>


	<div id="main-container" class="ui container">
		<div id="tools-container">
			{{ q["tools"] }}
		</div>
		<div id="content-container" class="ui segment">{{ content() }}</div>
	</div>
	<footer>
		<div class="ui container">Mentions légales :
			<ul>
				<h5> La licorne appartient à GITHUB ! </h5>
			</ul>
		</div>
	</footer>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<!-- Latest compiled and minified JavaScript -->
	{{javascript_include("https://cdnjs.cloudflare.com/ajax/libs/prism/1.5.1/prism.min.js")}}
	{{javascript_include("https://cdnjs.cloudflare.com/ajax/libs/prism/1.5.1/components/prism-apacheconf.min.js")}}
	{{javascript_include("https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.min.js")}}
	{% if script_foot is defined %}
	{{ script_foot }}
	{% endif %}
</body>
</html>
