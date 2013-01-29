<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<title>{title}</title>
{meta}
{style}
<link href="http://www.caplin.com/assets/css/fonts.css" rel="stylesheet">
{literal}
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
        font-family:'HelveticaNeueW01-55Roma',Helvetica,Arial,sans-serif;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 700px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
    </style>
{/literal}
</head>

<body>
    <div class="container-narrow">

      <div class="masthead">
        <ul class="nav nav-pills pull-right">
          <li class="active"><a href="/">Home</a></li>
          <li><a href="#">About</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
        <h3 class="muted">Vagando Networks</h3>
      </div>
      <hr>
    {content}
      <hr>
    {footer}
    </div> 
{script}
<script src="http://malsup.github.com/jquery.form.js"></script> 
{literal}
    <script> 
        // wait for the DOM to be loaded 
        $(document).ready(function() { 
            // bind 'myForm' and provide a simple callback function 
            $('#myForm').ajaxForm({
                dataType: 'script',
            }); 
        }); 
    </script> 
{/literal}
</body>
</html>