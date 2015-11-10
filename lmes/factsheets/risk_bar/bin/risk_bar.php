<html>
  <head>
    <style>
     table td,tr{
       font-family:Verdana, sans-serif;
       font-size:10px;
       width:10em;
       height:18px;
       margin:0;
       padding:0;
       max-width:10em;
       text-align:center;
     }
     a{
       color:#000
     }
    </style>
  </head>
  <body>

    <?php if ($_GET["risk"]!=''): ?>
    <div style="width:500px; margin: 0 auto;">
      <table>
        <tr >
          <td>Very low</td><td>Low</td><td>Medium</td><td>High</td><td>Very High</td>
        </tr>
        <tr>
          <td style="background:#5fbadd; ">&nbsp;</td>
          <td style="background:#78bb4b; ">&nbsp;</td>
          <td style="background:#e4e344; ">&nbsp;</td>
          <td style="background:#ee9f42; ">&nbsp;</td>
          <td style="background:#d8232a; ">&nbsp;</td>
<!-- old values
          <td style="background:#2153b4; ">&nbsp;</td>
          <td style="background:#21b437; ">&nbsp;</td>
          <td style="background:#d0d527; ">&nbsp;</td>
          <td style="background:#d79527; ">&nbsp;</td>
          <td style="background:#d73027; ">&nbsp;</td>
-->
        </tr>
        <tr>
          <?php if ($_GET["risk"]=='very_low'): ?>
            <td style="font-size:18px">&#x25B2;</td>
          <?php else: ?>
            <td style="font-size:18px;">&nbsp;</td>
          <?php endif ?>
          <?php if ($_GET["risk"]=='low'): ?>
            <td style="font-size:18px;">&#x25B2;</td>
          <?php else: ?>
            <td style="font-size:18px;">&nbsp;</td>
          <?php endif ?>
          <?php if ($_GET["risk"]=='medium'): ?>
            <td style="font-size:18px;">&#x25B2;</td>
          <?php else: ?>
            <td style="font-size:18px;"></td>
          <?php endif ?>
          <?php if ($_GET["risk"]=='high'): ?>
            <td style="font-size:18px;">&#x25B2;</td>
          <?php else: ?>
            <td style="font-size:18px;">&nbsp;</td>
          <?php endif ?>
          <?php if ($_GET["risk"]=='very_high'): ?>
            <td style="font-size:18px;">&#x25B2;</td>
          <?php else: ?>
            <td style="font-size:18px;">&nbsp;</td>
          <?php endif ?>

        </tr>
      </table>
    </div>
    <?php endif ?>
    <div style="font-family:Verdana, sans-serif; font-style:bold; font-color:#C0C0C0; font-size:11px;"><a href="/lmes/patterns_of_risk" style="float:left; font-weight:bold" target="_blank">Read about "Identifying patterns of risk among LMEs"</a>
      <a href="/data#460" style="float:right" target="_blank">Get indicator description, data and meta-information</a></div>
    <div style="clear:both"></div>
  </body>
</html>