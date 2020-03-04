<div class="page-title col-xs-12">
    <div class="title_left">
        <h3>Dashboard</h3>
    </div>
</div>
<div class="x_content">

    {{ partial('dashbord/filter') }}

    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><i class="fa fa-usd"></i> Bookings (B)</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: block;">
                            <h1 id="bookingsCount"></h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><i class="fa fa-automobile"></i> New Orders</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: block;">
                            <h1 id="newOrdersCount"></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><i class="fa fa-usd"></i> Finished Orders (FO)</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: block;">
                            <h1 id="finishedOrdersCount"></h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><i class="fa fa-usd"></i> New Orders Amount (NOA)</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: block;">
                            <h1 id="newOrderAmount"></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content" style="display: block;">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th colspan="3" style="padding-bottom: 0; padding-top: 0;">
                                        <h2><i class="fa fa-automobile"></i> Workflow status</h2>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">In Queue</th>
                                    <td id="countInQueue"></td>
                                    <td class="green" id="amountInQueue"></td>

                                </tr>
                                <tr>
                                    <th scope="row">Changed</th>
                                    <td id="countChanged"></td>
                                    <td class="green" id="amountChanged"></td>
                                </tr>
                                <tr>
                                    <th scope="row">In progress</th>
                                    <td id="countInProgress"></td>
                                    <td class="green" id="amountInProgress"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Archive</th>
                                    <td id="countArchive"></td>
                                    <td class="green" id="amountArchive"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><i class="fa fa-bar-chart"></i>Bookings for last 3 months</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: block;">
                            <div id="bookingsForLast3Months" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Registered Users For last 3 Months</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: block;">
                            <div id="usersLast3Months" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {#правый блок#}
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><i class="fa fa-usd"></i> Revenue (REV)</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: block;">
                            <h1 class="green" id="revenueAmount"></h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><i class="fa fa-usd"></i> Total Service Change (SC)</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: block;">
                            <h1 class="red" id="changedAmount"></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><i class="fa fa-usd"></i> Revenue breakdown</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: block;">
                            <div id="chartServiceCategory" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Recent Bookings</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" style="display: block;" id="recentBookings">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
