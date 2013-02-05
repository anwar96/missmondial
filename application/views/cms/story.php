<style type="text/css">
    .list {margin-bottom: 5px}
    .col2 {font-weight: bold;}
    .pp {margin-right: 5px;}
</style>
<h1>Data Story</h1>
<form class="well form-inline" method="post" action="<?= site_url('cms/datastory')?>" name="form-date">
    <div class="alert alert-error" id="alert"><strong></strong></div>
    <label>Start Date</label>
    <div data-date-format="yyyy-mm-dd" data-date="2013-01-01" id="date-start" class="input-append date">
        <input type="text" readonly="" value="2013-01-01" size="16" class="span2" name="start" id="start"/>
        <span class="add-on"><i class="icon-th"></i></span>
    </div>
    <label>End Date</label>
    <div data-date-format="yyyy-mm-dd" data-date="2013-01-01" id="date-end" class="input-append date">
        <input type="text" readonly="" value="2013-01-01" size="16" class="span2" name="end" id="end"/>
        <span class="add-on"><i class="icon-th"></i></span>
    </div>
    <input type="submit" class="btn" value="Submit" name="submit" />
    <a class="btn btn-primary" href="<?= site_url('cms/export_story/'.$search)?>">Export to excel</a><br/><br/>
</form>
<?php
    if(!empty($datastorys)){
        ?>
        <table class="table table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Endorsed Person</th>
                    <th>Suggest Place</th>
                    <th>Reason</th>
                    <th>Total Like</th>
                    <th>Date Insert</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($datastorys as $datastory){
                    ?>
                <tr>
                    <td><?= $i?></td>
                    <td><?= $datastory['name']?></td>
                    <td><?= $datastory['endorsed_person']?></td>
                    <td><?= $datastory['suggest_places']?></td>
                    <td><?= $datastory['reason']?></td>
                    <td><?= $datastory['total_like']?></td>
                    <td><?= $datastory['date_insert']?></td>
                    <td><a href="#" onclick="getDetail(<?= $datastory['id_story']?>)">Detail</a></td>
                </tr>
                    <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
<?php
    }
    else echo "Tidak ada data";
?>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel"></h3>
    </div>
    <div class="modal-body">
        <div class="col2">Endorsed Person :</div><div class="list" id="endorse"></div>
        <div class="col2">Suggest Place :</div><div class="list" id="place"></div>
        <div class="col2">Total Like :</div><div class="list" id="ttl_like"></div>
        <div class="col2">Date Insert :</div><div class="list" id="date"></div>
        <div class="col2">Reason :</div><div class="list" id="reason"></div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>
<!-- Modal -->

<script type="text/javascript">
    $(document).ready(function(){
        var startDate = new Date(2012,1,20);
        var endDate = new Date(2012,1,25);
        $('#alert').hide();
        $('#date-start')
            .datepicker()
                .on('changeDate', function(ev){
                    if (ev.date.valueOf() > endDate.valueOf()){
                        $('#alert').show().find('strong').text('The start date must be before the end date.');
                    }
                    else{
                        $('#alert').hide();
                        startDate = new Date(ev.date);
                        $('#date-start-display').text($('#date-start').data('date'));
                    }
            $('#date-start').datepicker('hide');
        });

        $('#date-end')
            .datepicker()
                .on('changeDate', function(ev){
                    if (ev.date.valueOf() < startDate.valueOf()){
                        $('#alert').show().find('strong').text('The end date must be after the start date.');
                    }
                    else{
                        $('#alert').hide();
                        endDate = new Date(ev.date);
                        $('#date-end-display').text($('#date-end').data('date'));
                    }
            $('#date-end').datepicker('hide');
        });
    });
    
    function search(){
        var search = $("#txtsearch").val();
        top.location.href = "<?= site_url()?>cms/datauser/"+search;
    }

    function getDetail(id){
        $.ajax({
            type		: "POST" ,
            url		    : "<?PHP echo site_url('cms/detailstory') ?>" ,
            data		: {
                "id"                : id
            },
            dataType	: "json",
            success		: function(response){
                $('#myModalLabel').html('<img class="pp img-polaroid" src="http://graph.facebook.com/'+response.fb_uid+'/picture" />'+response.name);
                $('#endorse').html(response.endorse);
                $('#place').html(response.place);
                $('#ttl_like').html(response.ttl_like);
                $('#date').html(response.date);
                $('#reason').html(response.reason);
                $('#myModal').modal('toggle');
            }
        });
    }
</script>