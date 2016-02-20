<?php
/**
 * https://github.com/chousmith/restaurant-search (c) 2016 alex chousmith 
 *
 * reading in a restaurants.csv and filtering via PHP and also jQuery
 */
?>
<!doctype html>
<html>
<head>
  <title>Tasty Restaurants in the Kearny Mesa area</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  
  <meta propert="og:title" content="Tasty Restaurants in the Kearny Mesa area">
  <meta propert="og:description" content="https://github.com/chousmith/restaurant-search">
  
  <!-- Bootstrap : Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Lato Google font is a nice one -->
  <link href="https://fonts.googleapis.com/css?family=Lato:400,300,700" rel="stylesheet" type="text/css">

  <!-- a little extra css -->
  <style type="text/css">
  /* font adjusts */
  body {
    font-family: Lato, Arial, sans-serif;
  }
  h1 {
    font-weight: 700;
  }
  h1 small {
    font-weight: 400;
    white-space: nowrap;
  }
  .jumbotron p {
    font-weight: 300;
  }
  /* sticky footer */
  html {
    position: relative;
    min-height: 100%;
  }
  body {
    /* Margin bottom by footer height */
    margin-bottom: 60px;
  }
  .footer {
    position: absolute;
    bottom: 0;
    width: 100%;
    /* Set the fixed height of the footer here */
    height: 60px;
    background-color: #f5f5f5;
  }
  .footer p {
    margin: 20px 0;
  }
  /* top form styling */
  .navbar-form {
    border: none;
  }
  @media (max-width: 767px) {
    .navbar-form {
      margin: 0
    }
  }
  @media (min-width: 768px) {
    .navbar-header {
      width: 100%;
    }
    .navbar-default .navbar-form {
      width: 100%;
    }
    .navbar-form .input-group {
      width: 100%;
    }
  }
  </style>
</head>
<body>

  <!-- top navbar -->
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <form action="index.php" class="navbar-form navbar-left" role="search">
          <div class="input-group">
            <input type="search" name="filter" id="filter" value="<?php if ( isset( $_GET['filter'] ) ) echo $_GET['filter']; ?>" class="form-control" placeholder="Search Name or Cuisine" title="Search Name or Cuisine" tabindex="1" autofocus>
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
            </span>
          </div>
        </form>
      </div>
    </div>
  </nav>

  <!-- main content area -->
  <div class="container">

    <div class="jumbotron">
      <h1>Tasty Restaurants <small>in the Kearny Mesa area</small></h1>
      <p>How tasty are they? Quite tasty indeed.</p>
    </div>
  
    <div class="row">
      <div class="col-xs-12">
        <?php
        // this csv just makes me hungry for all sorts of foods...
        $csv = 'restaurants.csv';
        // error check if the file exists and opens ok
        if ( file_exists($csv) && ( ( $handle = fopen($csv, 'r') ) !== false ) ) {
        ?>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Restaurant</th>
                <th>Cuisine</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // the file opened ok! let's see about a search filter..
              $filter = '';
              $flength = 0;
              if ( isset( $_GET['filter'] ) ) {
                $filter = strtolower( ''. $_GET['filter'] );
                $flength = strlen( $filter );
              }
              $i = 0;
              $oot = '';
              $matches = true;
              $atleastone = false;
              // loop through line by line
              while (($line = fgets($handle)) !== false) {
                if ( $i > 0 ) {
                  $info = explode( ',',  $line );
                  if ( count($info) > 1 ) {
                    // see if we should try and match against filter or not
                    if ( ( $filter != '' ) && ( $flength > 0 ) ) {
                      $matches = false;
                      if ( ( substr( strtolower( $info[0] ), 0, $flength ) == $filter )
                        || ( substr( strtolower( $info[1] ), 0, $flength ) == $filter ) ) {
                          $matches = true;
                          $atleastone = true;
                      }
                    } else {
                      // no search but at least 1 result
                      $atleastone = true;
                    }
                    
                    if ( $matches ) {
                      // then output the row
                      $oot .= '<tr>';
                      $oot .= '<th scope="row">'. $i .'</th>';
                      $oot .= '<td>'. $info[0] .'</td>';
                      $oot .= '<td>'. $info[1] .'</td>';
                      $oot .= '</tr>' ."\n";
                    }
                  }
                }
                $i++;
              }
              if ( !$atleastone ) {
                $oot .= '<tr class="noresults"><td>&mdash;</td><td colspan="2"><p class="warning">Nothing found matching your search. <a href="index.php">Please try again</a>.</p></td></tr>';
              }
              // save printing til end of loop for better
              print $oot;
              // always fclose
              fclose($handle);
            ?>
            </tbody>
          </table>
        </div>
        <?php
        } else {
          // something happened with file reading
          ?>
          <div class="alert alert-warning" role="alert">
            <strong>Oops!</strong> There was an error in reading <em><?php echo $csv; ?></em>. Perhaps you should <a href="http://5f1429b9e1e83b83ac5e-3721cc30b0d63259b2211381d1431a50.r60.cf1.rackcdn.com/restaurants.csv" target="_blank">download it again</a>?
          </div>
        <?php } ?>
      </div>
    </div>
  </div> <!-- /container -->
  
  <footer class="footer">
    <div class="container">
      <p class="text-muted"><span class="pull-left">&copy; <?php echo date('Y'); ?> <a href="https://github.com/chousmith" target="_blank">alex chousmith</a></span> <span class="pull-right"><a href="https://github.com/chousmith/restaurant-search" target="_blank">github.com/chousmith/restaurant-search</a></span></p>
    </div>
  </footer>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript">
  jQuery(function($) {
    // only run the filter js if there are some rows to filter initially
    if ( $('.noresults').size() == 0 ) {
      // add our filtering via js too now that we have php fallback
      $('#filter').bind('keyup', function() {
        // remove prev empty
        $('.noresults').remove();
        // convert search filter term to all lowercase for case-insensitive
        var ftext = $(this).val().toLowerCase();
        if ( ftext == '' ) {
          $('tbody tr').show();
        } else {
          // convert search filter term to all lowercase for case-insensitive
          ftext= ftext.toLowerCase();
          // filter
          $('tbody tr').hide().children().not(':first-child').filter(function() {
            // filter down to those matching
            return $(this).text().toLowerCase().indexOf( ftext ) == 0;
          }).parent().show();
          // check empty
          if ( $('tbody tr:visible').size() == 0 ) {
            $('tbody').append('<tr class="noresults"><td>&mdash;</td><td colspan="2"><p class="warning">Nothing found matching your search. <a href="index.php">Please try again</a>.</p></td></tr>')
              .find('a').click(function() {
                // clicking that "Please search again" should reset
                $('#filter').val('').trigger('keyup').focus();
                return false;
              });
          }
        }
      }).parents('form').bind('submit', function() {
        // now all filtering done via jQuery so disable that php fallback
        return false;
      });
    }
  });
  </script>
</body>
</html>