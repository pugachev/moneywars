
<?php
  // dd($tgt_date,$tgt_sumvalue);
  $tgt_date = json_encode($tgt_date);
  $tgt_sumvalue = json_encode($tgt_sumvalue);

?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <title>タイトル</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top mb-5" >
        <a class="navbar-brand" href="#">MoneyWars</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-start" id="navbarSupportedContent">
          <ul class="navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="#">集計グラフ <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">新規作成</a>
            </li>
          </ul>
        </div>
    </nav>
    <div class="mx-auto col-12" style="text-align:center;">
      <div class="container">
          <div class="row">
              <div class="container" style="text-align:center;">
                  <canvas id="myChart"></canvas>
              </div>
          </div>
      </div>
   </div>

    <!-- ここに本文を記述します -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script>

      var ctx = document.getElementById("myChart").getContext('2d');
      var context = document.getElementById('myChart');
      var chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: <?php echo $tgt_date; ?>,
          datasets: [
            {
              label: '日別 支出合計',
              data: <?php echo $tgt_sumvalue; ?>,
              backgroundColor: "rgba(219,39,91,0.5)"
            }
          ]
        },

        // options: {}
        options: {
          title: {
            display: false,
            text: '支店別 来客数'
          },
          scales: {
            x: {
              display: true,
              title: {
                display: true,
                text: '日付',
                font: { size: 14 },
              },
            },
            y: {
              display: true,
              title: {
                display: true,
                text: '出費額',
                font: { size: 14 },
              },
              reverse: false,   // 逆向き目盛
              ticks: {
                callback: function(value){
                  return value+'円'; // 目盛の編集
                }
              }
            }
          }
        }
      });

      context.addEventListener( 'click', function( evt ){
          var item = chart.getElementAtEvent( evt );
          if( item.length == 0 ){
              return;
          }

          item = item[0];
          var index = item._index;
          var item_data = item._chart.config.data.datasets;
          alert( item_data[0]['data'][index] );
      });
    </script>
  </body>
</html>