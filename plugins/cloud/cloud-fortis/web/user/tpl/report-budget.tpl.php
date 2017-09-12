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
    .plan .plan-title {
        font-size: 1.5em;
        font-weight: 100;
    }
    .plan .plan-icon {
        padding: 0.5em 0;
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
                <h3>Name & Timeframe</h3>
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

                <h3>Budget Limits</h3>
                    <section>

                        <div class="col-lg-12">
                           <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-4 col-xs-4 selectype">
                                <div class="panel plan">
                                    <div class="panel-body">
                                        <span class="plan-title">CPU</span>
                                        <div class="plan-icon">
                                            <i class="fa fa-desktop"></i>
                                        </div>
                                        <p class="text-muted pad-btm">
                                            <label for="budgetcpu">Monthly Price Limit in $: </label>
                                            <input type="text" name="input" id="budgetcpu">
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-4 col-lg-4 col-xs-4 selectype">
                                <div class="panel plan">
                                    <div class="panel-body">
                                        <span class="plan-title">Memory</span>
                                        <div class="plan-icon">
                                            <i class="fa fa-database"></i>
                                        </div>
                                        <p class="text-muted pad-btm">
                                            <label for="budgetmemory">Monthly Price Limit in $: </label>
                                            <input type="text" name="input" id="budgetmemory">
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-4 col-lg-4 col-xs-4 selectype">
                                <div class="panel plan">
                                    <div class="panel-body">
                                        <span class="plan-title">Storage</span>
                                        <div class="plan-icon">
                                            <i class="fa fa-hdd-o"></i>
                                        </div>
                                        <p class="text-muted pad-btm">
                                            <label for="budgetstorage">Monthly Price Limit in $: </label>
                                            <input type="text" name="input" id="budgetstorage">
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-4 col-lg-4 col-xs-4 selectype">
                                <div class="panel plan">
                                    <div class="panel-body">
                                        <span class="plan-title">Networking</span>
                                        <div class="plan-icon">
                                            <i class="fa fa-globe"></i>
                                        </div>
                                        <p class="text-muted pad-btm">
                                            <label for="budgetnetwork">Monthly Price Limit in $: </label>
                                            <input type="text" name="input" id="budgetnetwork">
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4 col-xs-4 selectype">
                                <div class="panel plan">
                                    <div class="panel-body">
                                        <span class="plan-title">Virtualization</span>
                                        <div class="plan-icon">
                                            <i class="fa fa-cloud"></i>
                                        </div>
                                        <p class="text-muted pad-btm">
                                            <label for="budgetvm">Monthly Price Limit in $: </label>
                                            <input type="text" name="input" id="budgetvm">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                    </section>
                <h3>Alerts</h3>
                    <section>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <span>Notify me when costs exceed&nbsp;</span><input type="text" id="percentbudg"><span>&nbsp;% of budgeted costs</span>
                            </div>
                        </div>
                    </section>
            </form>
        </div>
    </div>
</div>

