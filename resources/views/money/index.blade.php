<?php
header("Cache-Control: private");
session_cache_limiter('none');
  $firstDate = $tgt_date[0];
  $tgt_date = json_encode($tgt_date);
  $tgt_sumvalue = json_encode($tgt_sumvalue);
  $cate_data="";
  //戻りがオブジェクト型
  foreach($categories as $val){
      $cate_data .= "<option value='". $val->cate_num;
      $cate_data .= "'>". $val->cate_name. "</option>";
  }
?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('/money.png') }}">
    <title>MoneyWars</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top mb-0" >
        <a class="navbar-brand" href="{{route('money.index')}}">MoneyWars</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-start" id="navbarSupportedContent">
          <ul class="navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="{{route('money.index')}}">集計グラフ</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="#" data-toggle="modal" data-target="#dataCreate">新規作成</a>
            </li>
          </ul>
        </div>
    </nav>
    @if(session('message'))
    <div id="alert" class="alert alert-success">{{session('message')}}</div>
    @endif
    <div class="mx-auto col-12" style="text-align:center;margin-top:85px;">
      <div class="container">
          <div class="row">
              <div class="container d-flex justify-content-between" style="text-align:center;">
                <div class="justify-content-center">
                  <form method="post" action="{{route('money.preweek')}}" class="form-inline" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <button id="preweek" type="submit" class="btn btn-outline-danger btn-lg">前週</button>
                    <input type="hidden" value='<?php echo $firstDate; ?>' name="preweek">
                  </form>
                </div>
                <div class="justify-content-center">
                  <label class="col-form-label">月間目標値</label>
                  <label class="col-form-label"><mark><strong>100,000</strong></mark></label>
                </div>
                <div class="justify-content-center">
                  <label class="col-form-label"><?php echo date('m'); ?>月合計</label>
                  <label class="col-form-label"><mark><strong><?php echo number_format($actualresults) ?></strong></mark></label>
                </div>
                <div class="justify-content-center">
                  <form method="post" action="{{route('money.nextweek')}}" class="form-inline" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <button id="nextweek" type="submit" class="btn btn-outline-danger btn-lg">次週</button>
                    <input type="hidden" value='<?php echo $firstDate; ?>' name="nextweek">
                  </form>
                </div>
              </div>
              <div class="container" style="text-align:center;position: relative; width: 100%;aspect-ratio: 1.7;">
                  <canvas id="myChart"></canvas>
              </div>
          </div>
      </div>
   </div>

    <!-- ここに本文を記述します -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ja.min.js"></script>
    <script type="text/javascript">
      $(function(){
        setTimeout(function () {
            //保存後に画面がリダイレクトされることを利用している
            $('#alert').fadeOut(3000);
        }, 3000);


        $('.datepicker.datepicker-dropdown').datepicker({
            language:'ja', // 日本語化
            format: 'yyyy-mm-dd', // 日付表示をyyyy/mm/ddにフォーマット
            beforeShow: function(input, inst){
                setTimeout(function(){
                    $('#tgt_date')
                        .css(
                            'z-index',
                            String(parseInt($(input).parents('.modal').css('z-index'),10) + 1)
                        );
                },0);
            }
        });

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
            responsive: true,
            maintainAspectRatio: false,
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
            // var item_data = item._chart.config.data.datasets;
            var item_data = item._chart.config.data.labels;
            // alert( item_data[index]);
            var tmp = item_data[index];
            // location.href="{{URL::to('money/')}}";
            // location.href="{{URL::to('money/')}}?param="+tmp;
            location.replace("{{ URL::to('money/show') }}?tgt_date="+tmp);
        });
      });
    </script>
  </body>
</html>
<div class="modal fade" id="dataCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">新規作成</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="post" action="{{route('money.store')}}" class="form-inline" enctype="multipart/form-data" autocomplete="off">
              @csrf
              <div class="modal-body">
                  <div class="row">
                      <div class="form-group mb-1" style="width:100%;">
                          <span class="col-3">日付</span>
                          <input type="text" class="datepicker datepicker-dropdown" id="tgt_date" name="tgt_date">
                      </div>
                      <br />
                      <div class="form-group mb-1" style="width:100%;">
                          <span class="col-3">種類</span>
                          <select name="tgt_item" id="tgt_item" class="browser-default custom-select">
                              <?php echo $cate_data; ?>
                          </select>
                      </div>
                      <div class="form-group mb-1" style="width:100%;">
                          <span class="col-3">項目名</span>
                          <input type="text" id="tgt_name" name="tgt_name" class="form-control">
                      </div>
                      <div class="form-group mb-1" style="width:100%;">
                          <span class="col-3">支出額</span>
                          <input type="text" id="tgt_payment" name="tgt_payment" class="form-control">
                      </div>
                  </div>
                  <div class="modal-footer text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                  </div>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>