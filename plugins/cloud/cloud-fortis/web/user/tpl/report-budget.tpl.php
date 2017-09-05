<style>
    #project_tab_ui { display: none; }  /* hack for tabmenu issue */
    tbody {
        display: inline-block;
        width: 100%;
        overflow: auto;
        height: 15.4rem;
    }
    tr {
        width: 100%;
        display: table;
    }
    ::-webkit-scrollbar {
    width: 5px;
    }
     
    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.3); 
        border-radius: 3px;
    }
     
    ::-webkit-scrollbar-thumb {
        border-radius: 3px;
        -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.5); 
    }
</style>
<link href="/cloud-fortis/css/jquery.steps.css" rel="stylesheet" type="text/css">
<script src="/cloud-fortis/js/c3/d3.v3.min.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/c3/c3.min.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/chartjs/Chart.bundle.min.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/chartjs/utils.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/fetch-report.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/jquery.steps.min.js" type="text/javascript"></script>
<script>
    var budgetpage = true;
    var datepickeryep = true;

$(document).ready(function() {

    var form = $("#create-budget-form");

    form.steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        onInit: function(event, currentIndex)
        {
            $(".date").datepicker();
        }
    });
});


</script>
<div class="cat__content">
    <cat-page>
    <div class="row" id="chart-row">
        <div class="col-sm-12">
            <section class="card">  
                <div class="card-header">
                    <span class="cat__core__title d-inline-block" style="min-width: 500px;">
                        <label class="d-inline"><strong>Budget Planning</strong></label>
                        <!-- <a class="d-inline" id="prev-budget" style="padding: 0 1rem;"><i class="fa fa-backward disabled"></i></a> 
                        <h5 class="d-inline" id="budget-name" style="padding: 0 2rem; text-align: center;">BUDGET NAME</h5>
                        <a class="d-inline" id="next-budget" style="padding: 0 1rem;"><i class="fa fa-forward"></i></a> -->
                        <select id="budgets" class="form-control d-inline" style="max-width: 19rem;"></select>
                    </span>
                    <div class="pull-right d-inline-block">
                    <a id="create-budget" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#create-budget-modal"><i class="fa fa-plus"></i>&nbsp;Create Budget</a>
                    </div>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-4 dashboard">
                            <section class="card">  
                                <div class="card-block">
                                    <div class="panel-heading">
                                        <div class="panel-control">
                                            <h3 class="panel-title">Budget Resources</h3>
                                        </div>
                                    </div>
                                    <div>
                                        <div id="budgets-setting" style="height: 15rem;">
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section class="card">  
                                <div class="card-block">
                                    <div class="panel-heading">
                                        <div class="panel-control">
                                            <h3 class="panel-title">Budget Alerts</h3>
                                        </div>
                                    </div>
                                    <div>
                                        <div id="budgets-alert" style="height: 15rem;">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="col-sm-8 dashboard">
                            <section class="card">  
                                <div class="card-block">
                                    <div class="panel-heading">
                                        <div class="panel-control">
                                             <h3 class="panel-title">Spent vs Budget</h3>
                                        </div>
                                    </div>
                                    <div>
                                        <div id="budget-vs-spent-chart" style="height: 34.7rem;">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    </cat-page>
</div>

<div id="create-budget-modal" class="modal" data-backdrop="static" style="display: none;" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-black">Create A New Budget</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="create-budget-form">
                <h3>Step 1</h3>
                    <section>
                        <!--<div class="row"> -->
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="budgetname">Budget Name</label>
                                    <input type="text" class="form-control" id="budgetname">
                                </div>
                                <div class="form-group">
                                    <label for="budgetdatestart">Budget Start Date</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker-only-init date" id="budgetdatestart">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="budgetdateend">Budget End Date</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker-only-init date" id="budgetdateend">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <!-- </div> -->
                    </section>

                <h3>Step 2</h3>
                    <section>
                        <!-- <div class="row">  -->
                            <!--
                            <div class="col-sm-1">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </div>
                            -->
                            <div class="col-lg-12">

                                <div class="form-group d-inline-block" style="width: 100%;">
                                    <div class="owl-carousel uninitiated owl-theme">
                                        <div class="item">
                                            {cloud_application_select_0_label}
                                            {cloud_application_select_0_checkbox}
                                        </div>
                                        <div class="item">
                                            {cloud_application_select_1_label}
                                            {cloud_application_select_1_checkbox}
                                        </div>
                                        <div class="item">
                                            {cloud_application_select_2_label}
                                            {cloud_application_select_2_checkbox}
                                        </div>
                                        <div class="item">
                                            {cloud_application_select_3_label}
                                            {cloud_application_select_3_checkbox}
                                        </div>
                                        <div class="item">
                                            {cloud_application_select_4_label}
                                            {cloud_application_select_4_checkbox}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                    </section>
                <h3>Step3</h3>
                    <section>
                        <!-- <div class="row">  -->
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="cloud_disk_select">Disk *</label>
                                    {cloud_disk_select}
                                </div>
                            </div>
                        <!-- </div> -->
                    </section>
            </form>
        </div>
    </div>
</div>

