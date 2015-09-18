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
    </style>
  </head>
  <body>


    <div style="width:500px; margin: 0 auto;">
      <table>
        <tr >
          <td>Very low</td><td>Low</td><td>Medium</td><td>High</td><td>Very High</td>
        </tr>
        <tr>
          <td style="background:#2153b4; ">&nbsp;</td>
          <td style="background:#21b437; ">&nbsp;</td>
          <td style="background:#d0d527; ">&nbsp;</td>
          <td style="background:#d79527; ">&nbsp;</td>
          <td style="background:#d73027; ">&nbsp;</td>
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
      <div>

  </body>
</html>