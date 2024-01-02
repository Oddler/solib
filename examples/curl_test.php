<?
  session_start();
  
  $sSession = isset($_SESSION['MySession'])?$_SESSION['MySession']:'---';
  $_SESSION['MySession'] = 'zzz1zzz';

?>
<h2>CURL тест</h2>
<table border="1">
  <tr>
    <td><b>IP:</b></td>
    <td><?=$_SERVER['REMOTE_ADDR']?></td>
  </tr>

  <tr>
    <td><b>HTTP_USER_AGENT:</b></td>
    <td><?=$_SERVER['HTTP_USER_AGENT']?></td>
  </tr>

  <tr>
    <td><b>Referer:</b></td>
    <td><?=$_SERVER['HTTP_REFERER']?></td>
  </tr>

  <tr>
    <td><b>Cookie:</b></td>
    <td><?=$sSession?></td>
  </tr>

  <tr>
    <td><b>headers:</b></td>
    <td>
<?
  echo '<pre>';
    print_r(apache_request_headers() );
  echo '</pre>';
?>
    </td>
  </tr>
  
  <tr>
    <td><b>$_GET:</b></td>
    <td>
<?
  echo '<pre>';
    print_r($_GET);
  echo '</pre>';
?>
    </td>
  </tr>


  <tr>
    <td><b>$_POST:</b></td>
    <td>
<?
  echo '<pre>';
    print_r($_POST);
  echo '</pre>';
?>
    </td>
  </tr>
</table>